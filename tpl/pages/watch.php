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
					$query = mysqli_query($con,	"SELECT video.titel, video.omschrijving, video.uploadDatum, video.video_id, video.videoPath, video.gebruiker_id, gebruiker.voornaam, gebruiker.achternaam
												FROM `video` 
												JOIN gebruiker ON video.gebruiker_id=gebruiker.gebruiker_id
												WHERE video.video_id='$id'");
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
					
					//ophalen ratings
					$query3 = mysqli_query($con, "SELECT AVG(rating) as avgrating FROM `beoordeling` WHERE video_id='$id'");
					while($row = mysqli_fetch_assoc($query3))
					{
						$beoordeling = $row['avgrating'];
					}
					
					//opleiding selecteren
					$query4 = mysqli_query($con, 	"SELECT opleiding.naam 
													FROM `opleiding`
													JOIN vak_video ON  opleiding.opleiding_id=vak_video.opleiding_id
													WHERE video_id='$id'");
					while($row = mysqli_fetch_assoc($query4))
					{
						$opleiding = $row['naam'];
					}
					
					//tags selecteren, werkt nog niet want geen id bij tags
					$query5 = mysqli_query($con, "SELECT naam FROM `tag` WHERE video_id='$id'");
					while($row = mysqli_fetch_assoc($query5))
					{
						$tag = $row['naam'];
					}
					
					// vak naam selecteren
					$query6 = mysqli_query($con, 	"SELECT vak_naam
													FROM `vak`
													JOIN vak_video ON vak.vak_id=vak_video.vak_id
													WHERE video_id='$id'");
					while($row = mysqli_fetch_assoc($query6))
					{
						$vak = $row['vak_naam'];
					}
					
					//query opmerkingen
					$query2 = mysqli_query($con,	"SELECT opmerking.tekst, gebruiker.voornaam, gebruiker.achternaam
													FROM `opmerking`
													JOIN gebruiker ON opmerking.gebruiker_id=gebruiker.gebruiker_id
													WHERE video_id='$id'"); 
					
					echo "<div><video src='{assetsFolder}/uploads/".$location."' controls width='320px' height='200px' ></div><br>";
					echo "<div>";
					echo "Je kijkt: ".$name."<br>";
					echo "Geupload door: ".$docentVnaam." ".$docentAnaam." op:".$datum."<br>";
					echo "Beoordeling: ".$beoordeling."<br>";
					echo "Opleiding: ".$opleiding."<br>";
					echo "Vak : ".$vak."<br>";
					echo "Tags: ".$tag."<br>";
					echo "Beschrijving: ".$omschrijving."<br>";
					//ophalen opmerkingen en weergeven
					$query2 = mysqli_query($con,	"SELECT opmerking.tekst, gebruiker.voornaam, gebruiker.achternaam
													FROM `opmerking`
													JOIN gebruiker ON opmerking.gebruiker_id=gebruiker.gebruiker_id
													WHERE video_id='$id'"); // opmerking moet ook nog een join krijgen ivm naam van commenter en een loopje voor alle comments
					while($row = mysqli_fetch_assoc($query2))
					{
						$opmerking = $row['tekst'];
						$gebruikerVnaam = $row['voornaam'];
						$gebruikerAnaam = $row['achternaam'];
						echo $gebruikerVnaam." ".$gebruikerAnaam."zegt: ".$opmerking."<br>";
					}
					echo "</div>";
					

				}
				else
				{
					echo "Error!";
				}
			?>
		</div>
	</main>
</div>