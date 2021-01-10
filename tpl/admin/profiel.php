<?php 
    $docentID = intval($_COOKIE['userID']);
   
    $result = $DB->Get("SELECT * FROM docenten WHERE docent_id = '".$docentID."'");
    $docentData = $result->fetch_assoc();
 
if($result->num_rows > 0){
    if(isset($_POST['submitAction'])){
        if(!empty($_POST['docentVoornaam'])){
            if(!empty($_POST['docentAchternaam'])){
                if(!empty($_POST['docentEmail'])){
                    if(!empty($_POST['docentGebruikersnaam'])){
                        if(!empty($_POST['docentWachtwoord'])){
                            if($_POST['docentWachtwoord'] == $_POST['docentWachtwoordHerhaal']){
                                
                                $docentWachtwoord = md5($_POST['docentWachtwoordHerhaal']);

                        
                                if(!empty($_FILES["docentFoto"]["name"])) { 
                                    $fileName = basename($_FILES["docentFoto"]["name"]); 
                                    
                                    $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
                                    
                                    $allowTypes = array('jpg','png','jpeg','gif'); 
                                    if(in_array($fileType, $allowTypes)){ 
                                        $image = $_FILES['docentFoto']['tmp_name']; 
                                        $imgContent = addslashes(file_get_contents($image)); 
                                        $DB->Get("UPDATE docenten SET foto = '{$imgContent}' WHERE docent_id = '{$docentID}'");
                                    }
                                } 
                                    

                                $updateStatement = $DB->Get("UPDATE docenten SET 
                                    voornaam = '{$_POST["docentVoornaam"]}', 
                                    achternaam = '{$_POST["docentAchternaam"]}', 
                                    email = '{$_POST["docentEmail"]}',
                                    telefoonnummer = '{$_POST["docentTelefoonnummer"]}',
                                    gebruikersnaam = '{$_POST["docentGebruikersnaam"]}',
                                    wachtwoord = '{$docentWachtwoord}',
                                    twitter = '{$_POST["docentTwitter"]}',
                                    linkedin = '{$_POST["docentLinkedin"]}',
                                    instagram = '{$_POST["docentInstagram"]}'
                                        WHERE docent_id = '{$docentID}'");

                                    header('Location: profiel-bewerken');

                            }
                            else {
                                echo $lang["PROFIEL_ERRORS_WWG"];
                            }
                        }
                        else {
                            echo $lang["PROFIEL_ERRORS_WWI"];
                        }
                    }
                    else {
                        echo $lang["PROFIEL_ERRORS_GB"];
                    }
                }
                else {
                    echo $lang["PROFIEL_ERRORS_EM"];
                }
            }
            else {
                echo $lang["PROFIEL_ERRORS_AI"];
            }
        }
        else {
            echo $lang["PROFIEL_ERRORS_VI"];
        }   
    }
}
?>
<main class="content">

<div class='contentBlock-nohover'>
    <div class='contentBlock-side'></div>
    <div class='contentBlock-content'>
    <div class='contentBlock-text-normal'>
    <form method="POST" enctype="multipart/form-data"> 
        <?php 
        if(!empty($docentData['foto'])){
            echo '<img alt="'.$lang["PROFIEL_INFO_FOTO"].'"class="bewerkenFoto" style="max-width: 65%;" src="data:image/jpg;charset=utf8;base64,'.base64_encode($docentData['foto']).'" alt="foto van '.$docentData['voornaam'].'>" /><br />';
        } else {
            echo '<label for="docentFoto">'.$lang["PROFIEL_INFO_FOTO"].'</label><br />';
        }
        ?>
        <input type="file" name="docentFoto" style="width: 65%;"><br />
        <br />
        <div class="subTitle"><?PHP echo $lang["PROFIEL_INFO_PI"]?></div><br />
        <label for="docentVoornaam"><?PHP echo $lang["CONTACT_VOORNAAM"]?>*</label><br />
        <input type="text" value="<?= $docentData['voornaam'] ?>" name="docentVoornaam" id="docentVoornaam" placeholder="<?PHP echo $lang["CONTACT_VOORNAAM"]?>" required><br />
        
        <label for="docentAchternaam"><?PHP echo $lang["CONTACT_ACHTERNAAM"]?>*</label><br />
        <input type="text" id="docentAchternaam" value="<?= $docentData['achternaam'] ?>" name="docentAchternaam" placeholder="<?PHP echo $lang["CONTACT_ACHTERNAAM"]?>" required><br />
        
        <label for="docentEmail"><?PHP echo $lang["CONTACT_EMAIL"]?>*</label><br />
        <input type="text" id="docentEmail" value="<?= $docentData['email'] ?>" name="docentEmail" placeholder="Docent@nhlstenden.com" required><br />
       
        <label for="docentTelefoonnummer"><?PHP echo $lang["CONTACT_TELL"]?></label><br />
        <input type="text" id="docentTelefoonnummer" value="<?= $docentData['telefoonnummer'] ?>" name="docentTelefoonnummer" placeholder="0612345678"><br />
        
        <label for="docentGebruikersnaam"><?PHP echo $lang["DOCENTEN_BEHEER_GEBRUIKERSNAAM"]?>*</label><br />
        <input type="text" id="docentGebruikersnaam" value="<?= $docentData['gebruikersnaam'] ?>" name="docentGebruikersnaam" placeholder="<?PHP echo $lang["DOCENTEN_BEHEER_GEBRUIKERSNAAM"]?>" required><br />
        <br />
        <div class="subTitle"><?PHP echo $lang["DOCENTEN_BEHEER_SOCIAL"]?></div><br />
        <label for="docenttwitter"><?PHP echo $lang["DOCENTEN_BEHEER_SOCIAL_TWITTER"]?></label><br />
        <input type="text" id="docenttwitter" value="<?= $docentData['twitter'] ?>" name="docentTwitter" placeholder="Twitter"><br />
        
        <label for="docentLinkedin"><?PHP echo $lang["DOCENTEN_BEHEER_SOCIAL_LINKEDIN"]?></label><br />
        <input type="text" id="docentLinkedin" value="<?= $docentData['linkedin'] ?>" name="docentLinkedin" placeholder="Linkedin"><br />
        
        <label for="docentInstagram"><?PHP echo $lang["DOCENTEN_BEHEER_SOCIAL_INSTA"]?></label><br />
        <input type="text" id="docentInstagram" value="<?= $docentData['instagram'] ?>" name="docentInstagram" placeholder="Instagram"><br />
        <br />
        <div class="subTitle"><?PHP echo $lang["DOCENTEN_BEHEER_BEVEILIGING"]?></div><br />
        <label for="docentWachtwoord"><?PHP echo $lang["DOCENTEN_BEHEER_WACHTWOORD"]?>*</label><br />
        <input type="password" id="docentWachtwoord" name="docentWachtwoord" placeholder="<?PHP echo $lang["DOCENTEN_BEHEER_WACHTWOORD"]?>" required><br />

        <label for="docentWachtwoordHerhaal"><?PHP echo $lang["DOCENTEN_BEHEER_WACHTWOORD_HERHALEN"]?> *</label><br />
        <input type="password" id="docentWachtwoordHerhaal" name="docentWachtwoordHerhaal" placeholder="<?PHP echo $lang["DOCENTEN_BEHEER_WACHTWOORD_HERHALEN"]?>" required><br />


        <p><?PHP echo $lang["DOCENTEN_BEHEER_VERPLICHT"]?></p>
        <button type="submit" name="submitAction"><?PHP echo $lang["BESCHIKBAARHEID_SAVE"]?></button>
    </form>
    </div>
    </div>
</div>
    </div>
</main>