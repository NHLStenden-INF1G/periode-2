<?php 
class Video {
    
	function createThumbnail($videoPath, $videoLengte)
	{
		$videoPathEdit = substr($videoPath, 1);
		$thumbNailnaam = explode("uploads/video/", explode(".", $videoPath)[0])[1].'.png';
		$videoLengte = rand(1, ($videoLengte-7));
		$cmd = '"config/_lib/bin/ffmpeg" -i '.$videoPathEdit.' -ss '.$videoLengte.' -vframes 1 uploads/thumbnails/'.$thumbNailnaam.'';
		shell_exec($cmd);

		$this->createVideoPreview($videoPath, ($videoLengte+1));
	}

	function createVideoPreview($videoPath, $videoLengte) 
	{
		$videoPath = substr($videoPath, 1);
		$videoGif = explode("uploads/video/", explode(".", $videoPath)[0])[1].'.gif';

		$cmd = '"config/_lib/bin/ffmpeg" -i '.$videoPath.' -ss '.$videoLengte.' -t 6 -vf "fps=10,scale=320:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" -loop 0 uploads/previews/'.$videoGif.'';
		shell_exec($cmd);
	}

	function getThumbnail($videoPath)
	{
		return 'thumbnails/'.explode("/uploads/video/", explode(".", $videoPath)[0])[1].'.png';
    }
    
	function getVideoGif($videoPath)
	{
		return 'previews/'.explode("/uploads/video/", explode(".", $videoPath)[0])[1].'.gif';
    }
    
	function getVideoName($videoPath)
	{
		return explode("/uploads/video/", explode(".", $videoPath)[0])[1];
	}

	function getRating($starNumber, $videoID, $html = null) 
	{
		
		global $DB, $user;

		if($user->logged_in) {
			$allowedVote = $DB->Select("SELECT * FROM beoordeling WHERE gebruiker_id = ? AND video_id = ?", [$user->id, $videoID]); 
			if(empty($allowedVote)) {
				$html .= '<form class="rating" method="post">';
			}
			else {
				$html .= "<div class='rating'>";
			}
		}
		else {
			$html .= "<div class='rating'>";
		}

		for( $x = 4; $x > -1; $x-- )
		{
			if( floor( $starNumber )-$x >= 1)
			{ 
				$html.= '<button class="fa fa-star star" name="starSubmit'.($x+1).'" type="submit" value="'.($x+1).'"></button>';
			}	
			elseif( $starNumber-$x > 0 )
			{ 
				$html.= '<button class="fa fa-star-half-o star" name="starSubmit'.($x+1).'" type="submit" value="'.($x+1).'"></button>';
			}
			else 
			{ 
				$html.= '<button class="fa fa-star-o star" name="starSubmit'.($x+1).'" type="submit" value="'.($x+1).'"></button>';
			}
		}

		if($user->logged_in) 
		{
			$allowedVote = $DB->Select("SELECT * FROM beoordeling WHERE gebruiker_id = ? AND video_id = ?", [$user->id, $videoID]); 
			if(empty($allowedVote)) {
				$html .= '<input type="hidden" name="videoID" value="'.$videoID.'"></form>';
			}
			else {
				$html .= '</div>';
			}
		}
		else {
			$html .= "</div>";
		}
		return $html;
	}

	function getProgress($timeStamp, $videoLengte)
	{
		global $user;

		if($user->logged_in) 
		{
			$ratio = $timeStamp / $videoLengte;
			$fraction = $ratio - floor($ratio);
			$percentage = 100 * $fraction;

			$percentage = ($percentage == 0) ? 100 : $percentage;
		}
		else 
		{
			$percentage = 0;
		}

		return $percentage;
	}

	function getTimestamp($videoID)
	{
		global $user, $DB;
		if($user->logged_in){
			$queryResult = $DB->Select("SELECT timestamp FROM voortgang WHERE gebruiker_id = ? AND video_id = ?", [$user->id, $videoID]);
			
			if(!empty($queryResult))
			{
				return $queryResult[0]['timestamp'];
			}

			return 0;
		}
		else 
		{
			return 0;
		}		
	}

	function getRatingAvg($videoID)
	{
		global $DB;
		return $DB->Select("SELECT AVG(rating) AS rating FROM beoordeling WHERE video_id = ?",[$videoID])[0]['rating'];
	}

	function getTags($videoID) 
	{
		global $DB;
		return $DB->Select("SELECT * FROM tag_video INNER JOIN tag ON tag.tag_id = tag_video.tag_id WHERE tag_video.video_id = ?", [$videoID]);
	}

	function getComments($videoID)
	{
		global $DB;

		return $DB->Select("SELECT opmerking.*, gebruiker.voornaam, gebruiker.achternaam, gebruiker.level, gebruiker.gebruiker_id 
								FROM opmerking 
								INNER JOIN gebruiker 
									ON gebruiker.gebruiker_id = opmerking.gebruiker_id
								WHERE video_id = ?
								ORDER BY datum DESC", [$videoID]);
	}

	function thumbnailContainer($queryResult, $outputVar = "thumbnailContainer", $html = null) 
	{
		global $TPL;

		if(!empty($queryResult))
		{
			$html .= '<div class="thumbnailContainer">';

			foreach ($queryResult as $key => $value) 
			{

				$videoID = $value['video_id'];

				$value['videoTags'] 	= $this->getTags($videoID);
				$value['rating'] 		= $this->getRatingAvg($videoID);
				$value['percentage'] 	= $this->getProgress($this->getTimestamp($videoID), $value['videoLengte']);

				$html .= "<div class='videoThumbBlock'>
							<div class='videoThumbBlockRand'></div>
								<div class='videoThumb link' data-link='/watch/{$videoID}' data-video='{$this->getVideoName($value['videoPath'])}'>
									<img class='videoThumbImg' alt='Thumbnail' data-video-thumb='{$this->getVideoName($value['videoPath'])}' src='".$TPL->Get('uploadsFolder')."/".$this->getThumbnail($value['videoPath'])."'>
								<div class='videoThumbTags'> ";

					foreach ($value['videoTags'] as $key1 => $tag_value) 
					{
						$html .= "<a class='videoTag' href='/zoeken/tags/".$tag_value['tag_id']."'>#{$tag_value['naam']}</a>";
					}

					$html .= "</div>
							<div class='videoDetailContainer'>
								<div class='videoThumbTitle'><strong class='videoDetailsTitle'>{$value['titel']}</strong></div>
								<div class='videoThumbDocent'>{$value['voornaam']} {$value['achternaam']}</div>
								<div class='videoThumbDetails'>{$value['uploadDatum']}
								<div class='videoThumbRating'>".$this->getRating($value['rating'], $videoID)."</div>
								<p>(".gmdate("H:i:s", $value['videoLengte']).")</p>
							</div>
						</div>
							<div class='videoProgress' style='width: {$value['percentage']}%;'></div>
						</div>
					</div>";
			}

			$html .= "</div>";
		}
		else 
		{
			$html .= $TPL->Get("VIDEO_GEEN");
		}
		
		return $TPL->Set($outputVar, $html);
	}

	function videoData($queryResult = null) 
	{
		if(!empty($queryResult)) {
			foreach (array_keys($queryResult) as $key => $value) {
				if(!empty($queryResult[$value])) {
					$videoID = $queryResult['video_id'];

					$queryResult['videoComments'] = $this->getComments($videoID);
					$queryResult['videoTags'] = $this->getTags($videoID); 
				}
			}
		}

		return $queryResult;
	}

	function videoContainer($videoData, $html = null)
	{
		global $TPL, $user;
		$videoData = $this->videoData($videoData);
		if(!empty($videoData)) 
		{
			$html .= '<div class="videoBlock">
				<video playsinline controls id="'.$videoData['video_id'].'" 
					data-video="'.$this->getVideoName($videoData['videoPath']).'" 
					data-docent="'.$videoData['voornaam'].' '.$videoData['achternaam'].'"
					data-titel="'.$videoData['titel'].'" 
					data-vak="'.$TPL->Get("VIDEOBEHEER_UPLOADEN_VAK").': '.$videoData['vak_naam'].'"
					data-opleiding="'.$TPL->Get("OPLDEIDINGBEHEER_OPLEIDINGNAAM").': '.$videoData['naam'].'"
					data-jaar="'.$TPL->Get("OPLDEIDINGBEHEER_JAAR").': '.$videoData['jaar'].'"
					data-periode="'.$TPL->Get("OPLDEIDINGBEHEER_PERIODE").':'.$videoData['periode'].'"
					>
					<source src="'.$videoData['videoPath'].'" type="video/mp4" >
					Browser doesn\'t support this video.
				</video>
				<div class="videoBlockRand"></div>
				<div class="videoComments">
					<span>'.$TPL->Get("VIDEO_REACTIES").'</span>
					<ul>';
					//Videocomments
					if(!empty($videoData['videoComments'])) {
						foreach ($videoData['videoComments'] as $key => $value) {

							foreach ($value as $key1 => $value1) 
							{
								$commentUserID 	= $value['gebruiker_id'];
								$commentUser 	= $value['voornaam']. '&nbsp;'. $value['achternaam'];
								$commentTekst 	= $value['tekst'];
								$commentDate 	= $value['datum'];
								$commentID 		= $value['opmerking_id'];
							}

							if($user->rank >= 2) 
							{
								$html .= '<li> 
										<form method="post" style="display: flex;">
											<span class="videoComments link" data-link="/profiel/'.$commentUserID.'">'.$commentUser.'</span>
											<button type="submit" class="star" style="font-size: 13px" name="deleteCommentSubmit">
												<i class="fa fa-trash"></i>
											</button>
											<input type="hidden" value="'.$commentID.'" name="deleteCommentID">
										</form>
										<span class="videoCommentsText">'.$commentTekst.'</span> 
										<span class="videoCommentsDate">'.$TPL->Get("REACTIE_DATUM").': '.$commentDate.'</span> 
									</li>';
							}
							else 
							{
								$html .= '<li> 
											<span class="videoComments link" data-link="/profiel/'.$commentUserID.'">'.$commentUser.'</span>
											<span class="videoCommentsText">'.$commentTekst.'</span> 
											<span class="videoCommentsDate">'.$TPL->Get("REACTIE_DATUM").': '.$commentDate.'</span> 
										</li>';
							}
						}
					}
					else 
					{
						$html .= $TPL->Get('REACTIE_EERSTE');
					}
					
						$html .= "</ul><div class='postComment'>";

					if($user->logged_in)
					{
						$html .= '<form method="POST">
								<input type="text" name="commentText" placeholder="'.$TPL->Get('REACTIE_PLAATS').'....">
								<button type="submit" name="commentSubmit"><i class="fa fa-paper-plane"></i></button>
								<input type="hidden" name="commentVideoID" value="'.$videoData['video_id'].'">
							</form>';
					}
					else 
					{
						$html .= '<input type="text" placeholder="'.$TPL->Get('REACTIE_LOGIN').'" disabled>';
					}

					$html .= '</div>
							</div>
					<div class="videoDetails">
						<div class="videoUpload">
							<span class="videoTitle tooltip">'.$videoData['titel'].'
								<span class="tooltiptext" >
								'.$TPL->Get("VIDEOBEHEER_UPLOADEN_VAK").': '.$videoData['vak_naam'].' <br /> 
								'.$TPL->Get("OPLDEIDINGBEHEER_OPLEIDINGNAAM").': '.$videoData['naam'].' ['.$TPL->Get("OPLDEIDINGBEHEER_JAAR").': '.$videoData['jaar'].' '.$TPL->Get("OPLDEIDINGBEHEER_PERIODE").':'.$videoData['periode'].']</span></span>
							<span class="link" data-link="/profiel/'.$videoData['gebruiker_id'].'">'.$videoData['voornaam'].' '.$videoData['achternaam'].'</span>
							<span>'.$videoData['views'].' '.$TPL->Get('VIDEO_WEERGAVEN').'</span>
							<span>'.$videoData['uploadDatum'].'</span>
						</div>
						<div class="videoRatings">
							'.$this->getRating($videoData['rating'], $videoData['video_id']).'
						</div>
						<div class="videoTagsContainer">';

							foreach ($videoData['videoTags'] as $key => $value) {
								foreach ($value as $key1 => $value1) {
									$tagID = $value['tag_id'];
									$tagNaam = $value['naam'];
								}
								$html .= '<span class="videoTag link" data-link="/zoeken/tags/'.$tagID.'">#'.$tagNaam.'</span>';
							}

						$html .= '</div>
								</div>
							</div>';
		}	
		else 
		{
			$html .= $TPL->Get("VIDEO_GEEN");
		}

		$TPL->Set("videoBlock", $html);
	}

	function deleteComment($commentID)
	{
		global $DB;
		return $DB->Delete("DELETE FROM opmerking WHERE opmerking_id = ?", [$commentID]);
	}

	function insertComment($commentTekst, $videoID)
	{
		global $DB, $user;
		$DB->Insert("INSERT INTO opmerking (tekst, video_id, gebruiker_id) VALUES (?, ?, ?)", [$commentTekst, $videoID, $user->id]);
		header("Refresh:0");
	}

	function insertBeoordeling($chosenRating, $videoID)
	{
		global $DB, $user;
		$DB->Insert("INSERT INTO beoordeling (rating, video_id, gebruiker_id) VALUES (?, ?, ?)", [$chosenRating, $videoID, $user->id]);
		header("Refresh:0");
	}

	function updateView($videoID)
	{
		global $DB;
		return $DB->Update("UPDATE video SET views = views + 1 WHERE video_id = ?", [$videoID]);
	}

	function videoActionHandler($POSTVars, $videoHandlerAction)
	{
		global $filter, $DB, $user;

		$videoHandlerAction = $filter->sanatizeInput($videoHandlerAction, "string");
		$videoID = $filter->sanatizeInput($POSTVars['videoID'], "int");
		$selectVoortgang = $this->getTimestamp($videoID);


		switch ($videoHandlerAction) 
		{
			
			case 'viewupdate':
					$this->updateView($videoID);
				break;
			case 'watchupdate':
				if($user->logged_in)
				{
					$timestamp = $filter->sanatizeInput($POSTVars['timestamp'], "int");

					$DB->Insert("INSERT INTO voortgang (gebruiker_id, video_id, timestamp) VALUES (?, ?, ?)", 
					[$user->id, $videoID, $timestamp]);

					$DB->Update("UPDATE voortgang SET timestamp = ?, datum = current_timestamp() WHERE video_id = ? AND gebruiker_id = ?", 
						[$timestamp, $videoID, $user->id]);


				}
				break;
			case 'watchprogess':
				if($user->logged_in)
				{			
	   
					if(!empty($selectVoortgang))
					{
						header("timestamp: {$selectVoortgang}");
					}
					else 
					{
					   header("timestamp: 0");
					}
				}
				break;
		}
	}
}