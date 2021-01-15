<div class="mainWrapper" style="min-height: 100vh;grid-column: 1;">
            <main>
                <div class="spotlightVideo">
                    <div class="Populair">
                            <?php
                            $datumDag = date("Y-m-d", strtotime("-1 day")); 
                            $datumWeek = date("Y-m-d", strtotime("-1 week")); 
                            $datumMaand = date("Y-m-d", strtotime("-1 month")); 
                            $voortgangResult['POPULAR_MONTH'] = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam
                                                                FROM video
                                                                INNER JOIN gebruiker ON gebruiker.gebruiker_id = video.gebruiker_id
                                                                WHERE uploadDatum > ?
                                                                ORDER BY views
                                                                LIMIT 3", [$datumMaand]);

                            $voortgangResult['POPULAR_WEEK'] = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam
                                                                FROM video
                                                                INNER JOIN gebruiker ON gebruiker.gebruiker_id = video.gebruiker_id
                                                                WHERE uploadDatum > ?
                                                                ORDER BY views
                                                                LIMIT 3", [$datumWeek]);

                            $voortgangResult['POPULAR_DAY'] = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam
                                                                FROM video
                                                                INNER JOIN gebruiker ON gebruiker.gebruiker_id = video.gebruiker_id
                                                                WHERE uploadDatum > ?
                                                                ORDER BY views
                                                                LIMIT 3", [$datumDag]);



                            //print_r($voortgangResult);
                            if(!empty($voortgangResult)) {

                            foreach ($voortgangResult as $key => $value) {
                                echo '<div class="sectionTitle">{'.$key.'}</div>
                                        <div class="thumbnailContainer">';
                                foreach ($voortgangResult[$key] as $key_1 => $value_1) {
                            
                                $value_1['videoTags'] = $DB->Select("SELECT * FROM tag_video 
                                        INNER JOIN tag 
                                            ON tag.tag_id = tag_video.tag_id
                                        WHERE tag_video.video_id = ?", [$value_1['video_id']]);

                                if($user->logged_in) {
                                    $voortgangTime = $DB->Select("SELECT timestamp FROM voortgang WHERE gebruiker_id = ? AND video_id = ?", [$user->id, $value_1['video_id']]);
                                    
                                    if(!empty($voortgangTime[0]['timestamp'])) {
                                        $percentage = $videoTools->getProgress($voortgangTime[0]['timestamp'], $value_1['videoLengte']);
                                    }
                                    else {
                                        $percentage = 0;
                                    }
                                    }
                                    else {
                                        $percentage = 0;
                                    }


                            $value_1['rating'] = $DB->Select("SELECT AVG(rating) AS rating FROM beoordeling WHERE video_id = ?",[$value_1['video_id']])[0]['rating'];
                                    
                            echo "<div class='videoThumbBlock'>
                                    <div class='videoThumbBlockRand'></div>
                                    <div class='videoThumb link' data-link='/watch/{$value_1['video_id']}' data-video='{$videoTools->getVideoName($value_1['videoPath'])}'>
                                    <img alt='Thumbnail' data-video-thumb='{$videoTools->getVideoName($value_1['videoPath'])}' class='videoThumbImg' src='{uploadsFolder}/".$videoTools->getThumbnail($value_1['videoPath'])."'>
                                    <div class='videoThumbTags'> ";

                                    foreach ($value_1['videoTags'] as $key1 => $value1) {
                                        echo "<span class='videoTag link' data-link=''>#{$value1['naam']}</span>";
                                    }

                                    echo "</div>
                                            <div class='videoDetailContainer'>
                                            <div class='videoDetailsTitle'><strong style='font-size: clamp(27px, 0.3vw, 30px);'>{$value_1['titel']}</strong></div>
                                            <div class='videoThumbDocent'>{$value_1['voornaam']} {$value_1['achternaam']}</div>
                                            <div class='videoThumbDetails'>{$value_1['uploadDatum']}
                                            <div style='margin-left: -0.4vw; margin-bottom: 0.5vw;'>
                                            ".$videoTools->getRating($value_1['rating'], $value_1['video_id'])."</div>
                                            <p>(".gmdate("H:i:s", $value_1['videoLengte']).")</p>
                                        </div>
                                    </div>
                                    <div class='videoProgress' style='grid-row: 5; grid-column: 1; background-color: red; width: {$percentage}%;'></div>
                                    </div>
                                    </div>";
                                    
                                    }
                                    echo '</div>';
                                }
                            
                            } 
                            else {
                                echo '{VIDEO_GEEN}';
                            } ?>  
            </div>
        </div>
    </main>
</div>