<?php
    //V1 blabla comment enzo yeah.
    //form categorie, titel, youtube link, upload knop, submit button.
    //De gebruiker mag alleen .mp4 bestanden uploaden met een max grootte van ~5 GB
    //Open verbinding met de databse
    $conn = mysqli_connect("localhost", "root", "", ""); //moet nog replaced worden
    mysqli_select_db($conn, "stendenflix");
?>
<div class="mainWrapper"> 
    <main>
        <form enctype="multipart/form-data" method="POST">
            <label for="vak">Vak:</label><br>
            <!-- <input type="text" id="categorie" name="categorie"><br> -->
            <select>
                <option selected="selected">Selecteer vak:</option>
                <?php
                    $sql = "SELECT vak_id, vak_naam
                    FROM vak";
                    if($result = mysqli_query($conn, $sql)) {
                        if(mysqli_num_rows($result) > 0) {
                            while ($vaklijst = mysqli_fetch_array($result)) {
                                $vakID = $vaklijst['vak_id'];
                                $vaknaam = $vaklijst['vak_naam'];
                                echo "<option value=$vakID>$vaknaam</option>";
                            }
                        }
                    }
                ?>
            </select>
            <br>
            <label for="titel">Video Titel:</label><br>
            <input type="text" id="Titel" name="titel"><br>
            <label for="ytlink">Upload hier je YT link:</label>
            <input type="url" id="ytlink" name="ytlink">
            <label for="file">of selecteer een bestand:</label>
            <input type="file" id="file" name="file"><br><br>
            <input name="submit" type="submit" value="Submit">
        </form>
        <?php
            if(isset($_POST["submit"])) {
                if ((($_FILES["file"]["type"] == "video/mp4") || ($_FILES["file"]["type"] == "video/webm") || ($_FILES["file"]["type"] == "	video/H264")) 
                && ($_FILES["file"]["size"] < 5000000000)) {
                    $uploads_dir = "uploads";
                    $tmp_name = $_FILES["file"]["tmp_name"];
                    $titel = str_replace(" ", "_", $_POST["titel"]);
                    $titel = $titel.'_'.(basename($_FILES["file"]["name"]));
                    $bestandsnaam = (basename($_FILES["file"]["name"]));
                    $uploadDir = "$uploads_dir/$bestandsnaam";
                    if(!file_exists($uploadDir)) { 
                        move_uploaded_file($tmp_name, $uploadDir);
                    }
                    else {
                        while(file_exists($uploadDir)) {
                            $uploadDir .= "(1)";
                        }
                        move_uploaded_file($tmp_name, $uploadDir);
                    } 
                }
                // Haal ingevoerde form op
                $categorie = $_POST["categorie"];
                $titel = $_POST["titel"];
                //$ytlink = $_POST["ytlink"];
                if(empty($_POST["ytlink"])) {
                    $playbackID = "NULL";
                }
                else {
                    $playbackID = "'";
                    $playbackID .= str_replace("https://www.youtube.com/watch?v=", "", $ytlink);
                    $playbackID .= "'";
                }
                $datum = date('Y-m-d H:i:s');
                $docentID = 1; // komt later
                $vakID = 1; // komt later
                $videolengte = 69; //komt ook later
                $sql = "INSERT INTO video VALUES (NULL, '$docentID', '$titel', $playbackID, '$uploadDir', '$videolengte', '$datum')";
                // sql INSERT INTO vak_video VALUES ($id, vakid) ofzo
                //ZONDER DE NULL WERKT DIT NIET HA
                $stmt = mysqli_prepare($conn, $sql) OR die(mysqli_error($conn));
                mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                mysqli_stmt_close($stmt);
                //2e query
                $videoid = mysqli_insert_id($conn);
                $sql = "INSERT INTO vak_video VALUES ('$videoid', $docentID, $ )";
                $stmt = mysqli_prepare($conn, $sql) OR die(mysqli_error($conn));
                mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                mysqli_stmt_close($stmt);

                mysqli_close($conn);
                echo $sql;
            }
        ?>
    
    </main>
</div>