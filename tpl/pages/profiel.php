<div class="mainWrapper"> 
    <main> 
        <div class="Spotlight">
            <div class="sectionTitle">{subpageTitle}</div>
            <?php

            if ($user->logged_in && $_GET['Path_1'] == $user->id) {
                $this->Set("subpageTitle", "Mijn profiel");

                $gebruikerID =  $user->id;

                //todo: wachtwoord veranderen & profielcreatie

                $voortgangResult = $DB->Select("SELECT * FROM voortgang WHERE gebruiker_id = ?", [$gebruikerID]);

                foreach ($voortgangResult as $key => $value) 
                {

                }
                
                $beoordelingResult = $DB->Select("SELECT * FROM beoordeling WHERE gebruiker_id = ?", [$gebruikerID]);

                foreach ($beoordelingResult as $key => $value) 
                {

                }

            }
            else {
                $gebruikerID =  $filter->sanatizeInput(intval($_GET['Path_1']), "int");
            }

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
            
?>
        </div>
    </main>     
</div>