<?php
	//connectie met database, dit is tijdelijk tot we remco zijn class implementeren
	$host = "localhost"; /* Host name */
	$user = "root"; /* User */
	$password = ""; /* Password */
	$dbname = "stendenflix"; /* Database name */

	$con = mysqli_connect($host, $user, $password,$dbname);
	// Check connection
	if (!$con) {
	  die("Connection failed: " . mysqli_connect_error());
	}
?>
<div class="mainWrapper"> 
    <main>
		<div class="Spotlight">
			 <?php
				// SELECT tekst FROM `opmerking` WHERE video_id='$id'"
				if(isset($_GET['id']))
				{
					$id = $_GET['id'];
					//ophalen video en informatie van video
					$query = mysqli_query($con,	"SELECT video.titel, video.omschrijving, video.uploadDatum, video.video_id, video.videoPath, gebruiker.voornaam, gebruiker.achternaam
												FROM `video` 
												JOIN gebruiker ON video.gebruiker_id=gebruiker.gebruiker_id
												WHERE video.video_id='$id'"); // moeten nog een join schrijven om docent naam op te halen
					while($row = mysqli_fetch_assoc($query))
					{
						$location = $row['videoPath'];
						$id = $row['video_id'];
						$name = $row['titel'];
						$docent = $row['gebruiker_id'];
						$docentVnaam = $row['voornaam'];
						$docentAnaam = $row['achternaam'];
						$omschrijving = $row['omschrijving'];
						$datum = $row['uploadDatum'];
					}
					//ophalen opmerkingen
					$query2 = mysqli_query($con,	"SELECT opmerking.tekst, gebruiker.voornaam, gebruiker.achternaam
													FROM `opmerking`
													JOIN gebruiker ON opmerking.gebruiker_id=gebruiker.gebruiker_id
													WHERE video_id='$id'"); // opmerking moet ook nog een join krijgen ivm naam van commenter en een loopje voor alle comments
					while($row = mysqli_fetch_assoc($query2))
					{
						$opmerking = $row['tekst'];
					}
					//ophalen ratings
					$query3 = mysqli_query($con, "SELECT AVG(rating) FROM `beoordeling` WHERE video_id='$id'");
					while($row = mysqli_fetch_assoc($query3))
					{
						$beoordeling = $row['rating'];
					}
					
					// query 4 voor opleiding
					// query 5 voor tags
					// query 6 voor views
					// query 7 voor vakken
					echo "<div><video src='{assetsFolder}/uploads/".$location."' controls width='320px' height='200px' ></div><br>"; //video pad gaat vanuit index.php, met test in root werkt het
					echo "<div>";
					echo "Je kijkt: ".$name."<br>";
					echo "Geupload door: ".$docent." op:".$datum."<br>";
					echo "Beoordeling: ".$beoordeling."<br>";
					//echo "Aantal views: ".$views."<br>"; // moet ook nog een query voor
					echo "Beschrijving: ".$omschrijving."<br>";
					echo "Opmerkingen: ".$opmerking."<br>";
					echo "</div>";
					//beoordeling moet nog, tags, vakken/opleiding, beoordeling moet ook gemiddelde, tags en vakken ook loop om alle weer te geven

				}
				else
				{
					echo "Error!";
				}
			?>
		</div>
	</main>
</div>