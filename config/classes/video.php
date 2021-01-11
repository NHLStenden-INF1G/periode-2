<?php 

Class Video {
    
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

	function getRating($starNumber, $videoID, $html = null) {
		
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

		if($user->logged_in) {
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
}