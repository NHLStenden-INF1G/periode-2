<?php
    //V1 blabla comment enzo yeah.
    //form categorie, titel, youtube link, upload knop, submit button.
?>
<div class="mainWrapper"> 
    <main>
        <form method="POST">
            <label for="categorie">Categorie:</label><br>
            <input type="text" id="categorie" name="categorie"><br>
            <label for="titel">Video Titel:</label><br>
            <input type="text" id="Titel" name="titel"><br>
            <label for="ytlink">Upload hier je YT link:</label>
            <input type="url" id="ytlink" name="ytlink">
            <label for="file">Selecteer een bestand:</label>
            <input type="file" id="file" name="file"><br><br>
            <input type="submit" value="Submit">
        </form>

        <?php
            if(isset($_POST["submit"])) {
                //file upload
                $uploadDir = "uploads";
                $tmp_name = $_FILES["file"]["tmp_name"];



                //Open verbinding met de databse
                $conn = mysqli_connect("localhost", "root", "", ""); //moet nog replaced worden :)
                mysqli_select_db($conn, "stendenflix");
                // Haal ingevoerde form op
                $categorie = $_POST["categorie"];
                $titel = $_POST["titel"];
                $ytlink = $_POST["ytlink"];
                $file = $_POST["file"];
                $playbackID = str_replace("https://www.youtube.com/watch?v=", "", $ytlink);
                $docentID = 1; // komt later
                $categorieID = 1; // komt later
                $uploadDir = "tmp"; // komt later

                $sql = "INSERT INTO video VALUES ('$docentID', '$categorieID', '$playbackID', '$uploadDir', CURRENT_DATE())";
                $stmt = mysqli_prepare($conn, $sql) OR die("Preperation error");
                mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                echo "Succes!";

            }




        ?>
    
    </main>
</div>