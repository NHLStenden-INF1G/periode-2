<?php 
    if(isset($_POST['submitButton'])) {
        if(!empty($_POST['passwordOne'])) {
            if(!empty($_POST['passwordTwo'])) {
                if($_POST['passwordOne'] === $_POST['passwordTwo']) {
                    $passwordOne = $filter->sanatizeInput($_POST['passwordOne'], "string");
                    $passwordTwo = $filter->sanatizeInput($_POST['passwordTwo'], "string");
                    $passwordTwo =  password_hash($passwordTwo, PASSWORD_DEFAULT);
                    $DB->Update("UPDATE gebruiker SET wachtwoord = ? WHERE gebruiker_id = ?", [$passwordTwo, $user->id]);
                }
            }
        }
 
    }

?>

<div class="mainWrapper"> 
    <main> 
        <div class="Spotlight">
            <div class="sectionTitle">{subpageTitle}</div>
            <?php

            if ($user->logged_in && isset($_GET['Path_1']) == $user->id && isset($_GET['Path_2']) == 'edit') {
                $this->Set("subpageTitle", "Mijn profiel aanpassen");

                echo "<form method='post'>
                        <label>Wachtwoord: <input type='password' name='passwordOne' placeholder='Wachtwoord' required /></label><br />
                        <label>Wachtwoord (herhaal): <input type='password' name='passwordTwo' placeholder='Wachtwoord' required /></label><br />
                        <button type='submit' name='submitButton'>wachtwoord veranderen</button>
                        <span class='button link' data-link='/profiel/{$user->id}'>annuleren</span>
                        </form>";
                //todo: profielcreatie
            }
            else if(isset($_GET['Path_1']) && !isset($_GET['Path_2'])){

                if($user->logged_in && $_GET['Path_1'] == $user->id) {
                    $gebruikerID = $user->id;
                    echo "<button class='link' data-link='/profiel/{$gebruikerID}/edit'>profiel aanpassen</button>";


                        
                    $voortgangResult = $DB->Select("SELECT * FROM voortgang 
                                                    INNER JOIN video
                                                    ON video.video_id = voortgang.video_id
                                                    INNER JOIN gebruiker
                                                    ON gebruiker.gebruiker_id = video.gebruiker_id
                                                    WHERE voortgang.gebruiker_id = ? ORDER BY datum
                                                    LIMIT 3", [$gebruikerID]);
                    
                      echo '
                    <div class="Aanbevolen">
                    <div class="sectionTitle">recent bekeken video\'s</div>
                    <div class="thumbnailContainer">';

                    foreach ($voortgangResult as $key => $value) {

                        $value['videoTags'] = $DB->Select("SELECT * FROM tag_video 
                                                            INNER JOIN tag 
                                                                ON tag.tag_id = tag_video.tag_id
                                                            WHERE tag_video.video_id = ?", [$value['video_id']]);

                        $ratio = $value['timestamp'] / $value['videoLengte'];
                        $fraction = $ratio - floor($ratio);
                        $percentage = 100 * $fraction;

                    echo "<div class='videoThumbBlock'>
                            <div class='videoThumbBlockRand'></div>
                                <div class='videoThumb link' data-link='/watch/{$value['video_id']}' id='{$videoTools->getVideoName($value['videoPath'])}'>
                                <img class='videoThumbImg' src='{uploadsFolder}/{$videoTools->getThumbnail($value['videoPath'])}' id='thumb-{$videoTools->getVideoName($value['videoPath'])}'>
                                <div class='videoThumbTags'> ";
                                foreach ($value['videoTags'] as $key1 => $value1) {
                                    echo "<li class='videoTag link' data-link=''>{$value1['naam']}</li>";
                                }
                                echo "
                                </div>
                                <div class='videoDetailContainer'>
                                    <div class='videoDetailsTitle'><strong>{$value['titel']}</strong></div>
                                    <div class='videoThumbDocent'>{$value['voornaam']} {$value['achternaam']}</div>
                                    <div class='videoThumbDetails'><!-- sterren --> {$value['datum']}</div>
                                </div>
                                <div class='videoProgress' style='grid-row: 5; grid-column: 1; background-color: red; width: {$percentage}%;'></div>
                            </div>
                    </div>";
                    }
?>
                  <script>
                        const elements = document.querySelectorAll(".videoThumb");
                        elements.forEach((link, index) => {
                            const thumbImage = document.getElementById('thumb-' + link.id);

                            link.addEventListener('mouseenter', ()=> {
                                thumbImage.src = "/uploads/previews/" + link.id + ".gif";
                            });
                            link.addEventListener('mouseleave', ()=>{
                                thumbImage.src = "/uploads/thumbnails/" + link.id + ".png";
                            });
                        });
                  </script>
                  <?php
                    echo '</div>
                    </div>';
                }
                else {
                    $gebruikerID =  $filter->sanatizeInput(intval($_GET['Path_1']), "int");
                }


                
                /*
                $beoordelingResult = $DB->Select("SELECT * FROM beoordeling WHERE gebruiker_id = ?", [$gebruikerID]);

                foreach ($beoordelingResult as $key => $value) 
                {

                }
                */

                $gebruikerResult = $DB->Select("SELECT * FROM gebruiker WHERE gebruiker_id = ? LIMIT 1", [$gebruikerID]);

                if(!empty($gebruikerResult)) {
                $userlevel =  $user->userLevel($gebruikerResult[0]['gebruiker_id']);

                $this->Set("subpageTitle", "Profiel van {$gebruikerResult[0]['voornaam']} {$gebruikerResult[0]['achternaam']}");

                    $commentsResult = $DB->Select("SELECT * FROM opmerking WHERE gebruiker_id = ?", [$gebruikerID]);
                    foreach ($commentsResult as $key => $value) 
                    {
                        
                    }

                    if($gebruikerResult[0]['level'] >= 2) {
                        $uploadResult = $DB->Select("SELECT * FROM video WHERE gebruiker_id = ?", [$gebruikerID]);

                        foreach ($uploadResult as $key => $value) 
                        {
                            
                        }
                    }
                }   
                else {
                    $this->Set("subpageTitle", "Profiel niet gevonden!");
                }
            }

            
            
?>
        </div>
    </main>     
</div>