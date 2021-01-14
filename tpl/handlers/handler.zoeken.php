
<?php 
    
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
           // .Gebruiker,  .opleiding, .tag, vak, video_vak, .video

            
            //Opleidingnaam + jaar
            if(isset($searchTermInt))
            {
                $opleidingResultVideo = $DB->Select("SELECT DISTINCT * FROM opleiding 
                                                        WHERE opleiding.naam LIKE CONCAT('%',?,'%')
                                                        AND opleiding.jaar = ? LIMIT 10", [$searchTermString, $searchTermInt]);

                $searchResult['opleidingResult'] = $opleidingResultVideo;
            }

           //Docent achternaam
            $searchResultAchternaam = $DB->Select("SELECT DISTINCT gebruiker.voornaam, gebruiker.achternaam, gebruiker.gebruiker_id
                                                    FROM gebruiker
                                                    WHERE gebruiker.achternaam LIKE CONCAT('%',?,'%') OR gebruiker.voornaam LIKE CONCAT('%',?,'%')
                                                    AND gebruiker.level > 2 LIMIT 10", [$searchTermString, $searchTermString]);

            $searchResult['docentVideoResult'] = $searchResultAchternaam;

            //Videotitel
            $searchResultVideo = $DB->Select("SELECT DISTINCT vak.*, video.*, gebruiker.voornaam, gebruiker.achternaam
                                            FROM video 
                                            INNER JOIN video_vak 
                                                ON video_vak.video_id = video.video_id
                                            INNER JOIN vak 
                                                ON vak.vak_id = video_vak.vak_id
                                            INNER JOIN gebruiker
                                                ON gebruiker.gebruiker_id = video.gebruiker_id
                                            WHERE video.titel LIKE CONCAT('%',?,'%') LIMIT 10", [$searchTermString]);

            $searchResult['videoResult'] = $searchResultVideo;

            //Videotags
            $tagResultVideo = $DB->Select("SELECT DISTINCT * FROM tag
                                            WHERE tag.naam LIKE CONCAT('%',?,'%') LIMIT 5", [$searchTermString]);

            $searchResult['tagsResult'] = $tagResultVideo;

            //Vakresultaat
            $vakResultVideo = $DB->Select("SELECT DISTINCT *
                                            FROM vak 
                                            WHERE vak.vak_naam LIKE CONCAT('%',?,'%') LIMIT 10", [$searchTermString]);

            $searchResult['vakResult'] = $vakResultVideo; 

            $searchResultJSON = json_encode($searchResult);
            header("searchResult: {$searchResultJSON}");
        }
    
?>