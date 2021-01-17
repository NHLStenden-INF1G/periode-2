<?php 
    $this->Set("pageTitle", $this->Get("PROFIEL_TITEL"));
    $this->Set("extraCSS", '<link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/canvas_special.css">
                            <link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/profiel.css">');

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

    if ($user->logged_in && isset($_GET['Path_1']) == $user->id && isset($_GET['Path_2']) == 'edit') {
        $this->Set("subpageTitle", $this->Get("PROFIEL_AANPASSEN_TITEL"));
        $this->Set("pageTitle", $this->Get("PROFIEL_AANPASSEN_TITEL"));
        //Overwrite extraCSS
        $this->Set("extraCSS", '<link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/canvas_special.css">');

        $this->Set("profielPage", "<form method='post'>
                                    <label>".$this->Get('PROFIEL_AANPASSEN_WACHTWOORD').": <input type='password' name='passwordOne' placeholder='".$this->Get('PROFIEL_AANPASSEN_WACHTWOORD')."' required /></label><br />
                                    <label>".$this->Get('PROFIEL_AANPASSEN_HERHAAL').": <input type='password' name='passwordTwo' placeholder='".$this->Get('PROFIEL_AANPASSEN_HERHAAL')."' required /></label><br />
                                    <button type='submit' name='submitButton'>".$this->Get('PROFIEL_AANPASSEN_VERANDEREN')."</button>
                                    <span class='button link' data-link='/profiel/{$user->id}'>".$this->Get('PROFIEL_AANPASSEN_ANNULEREN')."</span>
                                </form>");
    }
    else if(isset($_GET['Path_1']) && !isset($_GET['Path_2']))
    {
        if($user->logged_in && $_GET['Path_1'] == $user->id) 
        {
            $gebruikerID = $user->id;
    
            $this->Set("editButton", "<button class='link star' data-link='/profiel/{$gebruikerID}/edit'><i class='fa fa-pencil'></i></button>");
            $voortgangResult = $DB->Select("SELECT  video.*, voortgang.*, gebruiker.voornaam, gebruiker.achternaam
                                            FROM voortgang 
                                            INNER JOIN video
                                            ON video.video_id = voortgang.video_id
                                            INNER JOIN gebruiker
                                            ON gebruiker.gebruiker_id = video.gebruiker_id
                                            WHERE voortgang.gebruiker_id = ? AND video.gebruiker_id != ?
                                            ORDER BY voortgang.datum DESC
                                            LIMIT 6", [$gebruikerID, $gebruikerID]);
            
            $videoTools->thumbnailContainer($voortgangResult, "recentlyWatched");
    
            $this->Set('profielPage', '<div class="Aanbevolen"><div class="sectionTitle">'.$this->Get("PROFIEL_RECENTE_VIDEOS").'</div>'.$this->Get("recentlyWatched"));
        }
        else 
        {
            $this->Set("editButton", "");
            $gebruikerID =  $filter->sanatizeInput(intval($_GET['Path_1']), "int");
        }
    
        $gebruikerResult = $DB->Select("SELECT * FROM gebruiker WHERE gebruiker_id = ? LIMIT 1", [$gebruikerID]);
    
        if(!empty($gebruikerResult)) 
        {
            $userlevel =  $user->userLevel($gebruikerResult[0]['gebruiker_id']);
            $this->Set("subpageTitle", "{$gebruikerResult[0]['voornaam']} {$gebruikerResult[0]['achternaam']} ({$userlevel}) {$this->Get("editButton")}");
    
            if($gebruikerResult[0]['level'] >= 2) 
            {
                $uploadResult = $DB->Select("SELECT * FROM video 
                                            INNER JOIN gebruiker 
                                                ON gebruiker.gebruiker_id = video.gebruiker_id
                                            WHERE video.gebruiker_id = ?
                                            ORDER BY video.uploaddatum DESC", [$gebruikerID]);
                
                $videoTools->thumbnailContainer($uploadResult, "uploadResults");
                //TODO: STYLE
                $this->Set('profielPage', '<div style="grid-row: 3;"><div class="sectionTitle">'.$this->Get('PROFIEL_GEUPLOADE_VIDEOS').'</div>'.$this->Get('uploadResults'));

            }
        }   
        else 
        {
            $this->Set("subpageTitle", $this->Get("PROFIEL_NIET"));
        }
    }
    
    ?>