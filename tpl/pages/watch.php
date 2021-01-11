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
                        <video controls id="<?= $videoResult['video_id']; ?>">
                            <source src="<?= $videoResult['videoPath'] ?>" type="video/mp4" preload>
                        </video>
                        <div class="videoBlockRand"></div>
                        <div class="videoComments">
                            <span>laatste reacties</span>
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
											<span class="videoCommentsDate">Geplaats op: '.$commentDate.'</span> 
										</li>';
                                        }
                                        else {
                                            echo '<li> 
											<span class="videoComments">'.$commentUser.'</span> 
											<span class="videoCommentsText">'.$commentTekst.'</span> 
											<span class="videoCommentsDate">Geplaats op: '.$commentDate.'</span> 
										</li>';
                                        }
									}
								}
								else {
									echo "Nog geen reacties, wees de eerste! #first";
								}

								?>
							</ul>
							<div class='postComment' style="grid-row: 3; grid-column: 2;">
                                <?php 

                                if($user->logged_in){
                                    echo '<form method="POST">
                                            <input type="text" name="commentText" style="width: 80%;" placeholder="Een nieuwe comment plaatsen...">
                                            <button type="submit" name="commentSubmit"><i class="fa fa-paper-plane"></i></button>
                                            <input type="hidden" name="commentVideoID" value="'.$videoResult['video_id'].'">
                                        </form>';
                                }
                                else {
                                    echo '<input type="text" placeholder="Log in om een reactie te plaatsen" disabled>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="videoDetails">
                            <div class="videoUpload">
                                <span class="videoTitle"><?= $videoResult['titel'] ?></span>
                                <span><?= $videoResult['voornaam'] ?> <?= $videoResult['achternaam'] ?></span>
                                <span><?= $videoResult['views'] ?> weergaven</span>
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
                    <div class="sectionTitle">Aanbevolen</div>
                    <div class="thumbnailContainer">
                        <div class="videoThumbBlock" data-pg-collapsed>
                            <div class="pg-empty-placeholder videoThumbBlockRand"></div>
                            <div class="videoThumb" data-pg-collapsed>
                                <div class="videoThumbImg"></div>
                                <div class="videoThumbTags"> 
                                    <li class="videoTag">test</li>                                     
                                </div>
                                <div class="videoDetailsTitle">Testtitel</div>
                                <div class="videoThumbDocent">videoThumbDocent</div>
                                <div class="videoThumbDetails">videoThumbDetails</div>
                            </div>
                        </div>
                        <div class="videoThumbBlock" data-pg-collapsed>
                            <div class="pg-empty-placeholder videoThumbBlockRand"></div>
                            <div class="videoThumb" data-pg-collapsed>
                                <div class="videoThumbImg"></div>
                                <div class="videoThumbTags"> 
                                    <li class="videoTag">test</li>                                     
                                </div>
                                <div class="videoDetailsTitle">Testtitel</div>
                                <div class="videoThumbDocent">videoThumbDocent</div>
                                <div class="videoThumbDetails">videoThumbDetails</div>
                            </div>
                        </div>
                        <div class="videoThumbBlock" data-pg-collapsed>
                            <div class="pg-empty-placeholder videoThumbBlockRand"></div>
                            <div class="videoThumb" data-pg-collapsed>
                                <div class="videoThumbImg"></div>
                                <div class="videoThumbTags"> 
                                    <li class="videoTag">test</li>                                     
                                </div>
                                <div class="videoDetailsTitle">Testtitel</div>
                                <div class="videoThumbDocent">videoThumbDocent</div>
                                <div class="videoThumbDetails">videoThumbDetails</div>
                            </div>
                        </div>
                    </div>
                </div>
	</main>
</div>
<script src="/tpl/assets/js/video.js"></script>
