

<div class="mainWrapper"> 
    <main>
    <form method="POST">

<input type="text" name="searchTerm" placeholder="Zoeken...">
<button type="submit" name="searchButton">Zoeken</button>

</form>
<pre>
<?php 
    if(isset($_POST['searchButton'])) {
        if(!empty($_POST['searchTerm'])){

            $searchTerm = explode(" ", $_POST['searchTerm']);

            if(count($searchTerm) > 1)
            {
                foreach ($searchTerm as $key => $value) 
                {
                    if(intval($value) && !isset($searchTermInt))
                    {
                        $searchTermInt = $searchTerm[$key];
                    }
                }
                
            }

            $searchTermString = $filter->sanatizeInput($searchTerm[0], "string");
    
            $searchResult = [];
           // .Gebruiker,  .opleiding, .tag, vak, vak_video, .video


            //Opleidingnaam + jaar
            if(isset($searchTermInt))
            {
                $opleidingResultVideo = $DB->Select("SELECT * FROM opleiding 
                                                        INNER JOIN vak 
                                                        ON vak.opleiding_id = opleiding.opleiding_id
                                                        INNER JOIN vak_video 
                                                        ON vak_video.vak_id = vak.vak_id
                                                        INNER JOIN video
                                                        ON video.video_id = vak_video.video_id
                                                        WHERE opleiding.naam LIKE CONCAT('%',?,'%')
                                                        AND opleiding.jaar = ?", [$searchTermString, $searchTermInt]);

                $searchResult['opleidingResult'] = $opleidingResultVideo;
            }

           //Docent achternaam
            $searchResultAchternaam = $DB->Select("SELECT * FROM gebruiker 
                                                    INNER JOIN video
                                                    ON gebruiker.gebruiker_id = video.gebruiker_id
                                                    WHERE gebruiker.achternaam LIKE CONCAT('%',?,'%')", [$searchTermString]);

            $searchResult['docentVideoResult'] = $searchResultAchternaam;

            //Videotitel
            $searchResultVideo = $DB->Select("SELECT * FROM video WHERE video.titel LIKE CONCAT('%',?,'%')", [$searchTermString]);

            $searchResult['videoResult'] = $searchResultVideo;

            //Videotags
            $tagResultVideo = $DB->Select("SELECT * FROM tag 
                                            INNER JOIN video 
                                            ON video.video_id = tag.video_id
                                            WHERE tag.naam LIKE CONCAT('%',?,'%')", [$searchTermString]);

            $searchResult['tagsResult'] = $tagResultVideo;

            //Vakresultaat
            $vakResultVideo = $DB->Select("SELECT * FROM vak 
                                            INNER JOIN vak_video 
                                            ON vak_video.vak_id = vak.vak_id
                                            INNER JOIN video
                                            ON video.video_id = vak_video.video_id
                                            WHERE vak.vak_naam LIKE CONCAT('%',?,'%')", [$searchTermString]);

            $searchResult['vakResult'] = $vakResultVideo;



            print_r($searchResult);
        }
    }
?>
    </main>
</div>
