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
 
     <?php
		
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
			$query = mysqli_query($con, "SELECT * FROM `video` WHERE video_id='$id'");
			while($row = mysqli_fetch_assoc($query))
			{
				$location = $row['path'];
				$id = $row['video_id'];
				$name = $row['titel'];
				$docent = $row['docent_id'];
				$vak = $row['vak_id'];
				$beschrijving = $row['beschrijving'];
				$datum = $row['datum'];
				$views = $row['views'];
			}
			echo "<div><video src='".$location."' controls width='320px' height='200px' ></div><br>";
			echo "<div>";
			echo "Je kijkt: ".$name."<br>";
			echo "Geupload door: ".$docent." op:".$datum."<br>";
			echo "Aantal views: ".$views."<br>";
			echo "Beschrijving: ".$beschrijving."<br>";
			echo "</div>";
		}
		else
		{
			echo "Error!";
		}
	?>
	</main>
</div>