<?php 
	if(isset($_POST['deleteCommentSubmit'])) {
		$commentID = $filter->sanatizeInput($_POST['deleteCommentID'], "int");
		$DB->Delete("DELETE FROM opmerking WHERE opmerking_id = ?", [$commentID]);
	}
	
	if(isset($_GET['Path_1']))
			{
				$id = $filter->sanatizeInput($_GET['Path_1'], "int");
				//ophalen video en informatie van video
				$videoResult = $DB->Select("SELECT vak.vak_naam, AVG(beoordeling.rating) AS rating, 
											video.videoPath, video.uploadDatum, video.video_id, video.titel, video.videolengte, video.views,
											gebruiker.voornaam, gebruiker.achternaam 
											FROM video 
											INNER JOIN gebruiker 
												ON video.gebruiker_id = gebruiker.gebruiker_id
											LEFT JOIN beoordeling
												ON beoordeling.video_id = video.video_id
											INNER JOIN video_vak 
												ON video_vak.video_id = video.video_id
											INNER JOIN vak 
												ON vak.vak_id = video_vak.vak_id
											INNER JOIN opleiding
												ON opleiding.opleiding_id = vak.opleiding_id
											WHERE video.video_id= ?
											LIMIT 1", [$id])[0];

				foreach (array_keys($videoResult) as $key => $value) {
					if(empty($videoResult[$value])) {
						$error = true;
					}
					else {
						$error = false;
					}
				}
				
				if(!$error) 
				{
					$videoResult['videoComments'] = $DB->Select("SELECT opmerking.*, gebruiker.voornaam, gebruiker.achternaam, gebruiker.level FROM opmerking 
										INNER JOIN gebruiker 
											ON gebruiker.gebruiker_id = opmerking.gebruiker_id
										WHERE video_id = ?", [$id]);

					$videoResult['videoTags'] = $DB->Select("SELECT tag.naam FROM tag_video 
										INNER JOIN tag 
											ON tag_video.tag_id = tag.tag_id
										WHERE tag_video.video_id = ?", [$id]);	
										
					$this->Set("pageTitle", $videoResult['titel']);
				}
				else
				{
					header("Location: /error");
				}
			}
			else
			{
				header("Location: /error");
			}
		?>
		<div class="mainWrapper"> 
    <main>
		<div class="spotlightVideo">
                    <div class="videoBlock">
                        <video playsinline controls id="<?= $videoResult['video_id']; ?>">
                            <source src="<?= $videoResult['videoPath'] ?>" type="video/mp4" preload>
                        </video>
                        <div class="videoBlockRand"></div>
                        <div class="videoComments">
                            <span>{VIDEO_REACTIES}</span>
                            <ul> 
								<?php 
								if(!empty($videoResult['videoComments'])) {
									foreach ($videoResult['videoComments'] as $key => $value) {
										
										foreach ($value as $key1 => $value1) {
											$commentUser = $value['voornaam']. ' '. $value['achternaam'];
											$commentTekst = $value['tekst'];
											$commentDate = $value['datum'];
											$commentID = $value['opmerking_id'];
										}

                                        if($user->rank >= 2) {
                                            echo '<li> 
                                            <form method="post" style="display: flex;">
                                                <span class="videoComments">'.$commentUser.'</span>
                                                <button type="submit" class="star" style="font-size: 13px" name="deleteCommentSubmit">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <input type="hidden" value="'.$commentID.'" name="deleteCommentID">
                                            </form>
                                            
											<span class="videoCommentsText">'.$commentTekst.'</span> 
											<span class="videoCommentsDate">'.$this->Get("REACTIE_DATUM").': '.$commentDate.'</span> 
										</li>';
                                        }
                                        else {
                                            echo '<li> 
											<span class="videoComments">'.$commentUser.'</span> 
											<span class="videoCommentsText">'.$commentTekst.'</span> 
											<span class="videoCommentsDate">'.$this->Get("REACTIE_DATUM").': '.$commentDate.'</span> 
										</li>';
                                        }
									}
								}
								else {
									echo "{REACTIE_EERSTE}";
								}

								?>
							</ul>
							<div class='postComment' style="grid-row: 3; grid-column: 2;">
                                <?php 

                                if($user->logged_in){
                                    echo '<form method="POST">
                                            <input type="text" name="commentText" style="width: 80%;" placeholder="{REACTIE_PLAATS}...">
                                            <button type="submit" name="commentSubmit"><i class="fa fa-paper-plane"></i></button>
                                            <input type="hidden" name="commentVideoID" value="'.$videoResult['video_id'].'">
                                        </form>';
                                }
                                else {
                                    echo '<input type="text" placeholder="{REACTIE_LOGIN}" disabled>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="videoDetails">
                            <div class="videoUpload">
                                <span class="videoTitle"><?= $videoResult['titel'] ?></span>
                                <span><?= $videoResult['voornaam'] ?> <?= $videoResult['achternaam'] ?></span>
                                <span><?= $videoResult['views'] ?> {VIDEO_WEERGAVEN}</span>
								<span><?= $videoResult['uploadDatum'] ?> </span>
                            </div>
                            <div class="videoRatings">
								<?= $videoTools->getRating($videoResult['rating'], $videoResult['video_id']); ?>
                            </div>
                            <div class="videoTagsContainer">
							<?php 

									foreach ($videoResult['videoTags'] as $key => $value) {
										foreach ($value as $key1 => $value1) {
											$tagNaam = $value['naam'];
										}
										echo '<span class="videoTag">#'.$tagNaam.'</span>';
									}
							?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="Aanbevolen">
                    <div class="sectionTitle">{VIDEO_AANBEVOLEN}</div>
                    <div class="thumbnailContainer">
					<?php 
                        $voortgangResult = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam
                                                        FROM video 
                                                        INNER JOIN gebruiker
                                                        ON gebruiker.gebruiker_id = video.gebruiker_id
														WHERE video.video_id != ?
														ORDER BY RAND()
                                                        LIMIT 3", [$videoResult['video_id']]);

                        if(!empty($voortgangResult)) {

                        foreach ($voortgangResult as $key => $value) {

                        $value['videoTags'] = $DB->Select("SELECT * FROM tag_video 
                                INNER JOIN tag 
                                    ON tag.tag_id = tag_video.tag_id
                                WHERE tag_video.video_id = ?", [$value['video_id']]);

                        if($user->logged_in) {
                            $voortgangTime = $DB->Select("SELECT timestamp FROM voortgang WHERE gebruiker_id = ? AND video_id = ?", [$user->id, $value['video_id']]);
                            
                            if(!empty($voortgangTime[0]['timestamp'])) {
                                $percentage = $videoTools->getProgress($voortgangTime[0]['timestamp'], $value['videoLengte']);
                            }
                            else {
                                $percentage = 0;
                            }
                        }
                        else {
                            $percentage = 0;
                        }


                        $value['rating'] = $DB->Select("SELECT AVG(rating) AS rating FROM beoordeling WHERE video_id = ?",[$value['video_id']])[0]['rating'];

                            echo "<div class='videoThumbBlock'>
                            <div class='videoThumbBlockRand'></div>
                            <div class='videoThumb link' data-link='/watch/{$value['video_id']}' id='{$videoTools->getVideoName($value['videoPath'])}'>
                            <img class='videoThumbImg' src='{uploadsFolder}/{$videoTools->getThumbnail($value['videoPath'])}' id='thumb-{$videoTools->getVideoName($value['videoPath'])}'>
                            <div class='videoThumbTags'> ";

                            foreach ($value['videoTags'] as $key1 => $value1) {
                                echo "<li class='videoTag link' data-link=''>#{$value1['naam']}</li>";
                            }

                            echo "</div>
                            <div class='videoDetailContainer'>
                            <div class='videoDetailsTitle'><strong style='font-size: clamp(27px, 0.3vw, 30px);'>{$value['titel']}</strong></div>
                            <div class='videoThumbDocent'>{$value['voornaam']} {$value['achternaam']}</div>
                            <div class='videoThumbDetails'>{$value['uploadDatum']}
                            <div style='margin-left: -0.4vw; margin-bottom: 0.5vw;'>
                            ".$videoTools->getRating($value['rating'], $value['video_id'])."</div>
                            <p>(".gmdate("H:i:s", $value['videoLengte']).")</p>

                            </div>
                            </div>
                            <div class='videoProgress' style='grid-row: 5; grid-column: 1; background-color: red; width: {$percentage}%;'></div>
                            </div>
                            </div>";
                            }
                        } else {
                            echo '{VIDEO_GEEN}';
                        }
                            echo '</div>
                                </div>';     
                        ?>    
                    </div>
                </div>
	</main>
</div>
<script src="/tpl/assets/js/video.js"></script>
