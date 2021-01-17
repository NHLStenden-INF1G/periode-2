<?php
    //Kijkgeschiedenis & Beoordeling
    ($this->path == 'start') ? $this->Set("activePageStart", "active") : '';

    if(!empty($_POST) && !isset($_GET['Path_1']) )
    {
        if(!isset($_POST['commentSubmit']) && !isset($_POST['taalKnop']))
        {
            
            if($_POST[array_keys ($_POST)[0]])
            {
                $chosenRating = $filter->sanatizeInput($_POST[array_keys ($_POST)[0]], "int");
                $videoID =  $filter->sanatizeInput($_POST['videoID'], "int");

                $videoTools->insertBeoordeling($chosenRating, $videoID);
            }
        }
        else if(isset($_POST['commentSubmit'])) 
        {
            $commentTekst = $filter->sanatizeInput($_POST['commentText'], "string");
            $videoID = $filter->sanatizeInput($_POST['commentVideoID'], "int");

            $videoTools->insertComment($commentTekst, $videoID);
        }
    }
    else if(isset($_GET['Path_1']))
    {
        $videoTools->videoActionHandler($_POST, $_GET['Path_1']);
    }

    if(isset($_POST['deleteCommentSubmit'])) 
    {
        $commentID = $filter->sanatizeInput($_POST['deleteCommentID'], "int");
        $videoTools->deleteComment($commentID);
    }
               
    $voortgangResult = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam FROM video 
                                    INNER JOIN gebruiker 
                                        ON gebruiker.gebruiker_id = video.gebruiker_id
                                    ORDER BY RAND() LIMIT 3");


    $videoResult = $DB->Select("SELECT  vak.vak_naam, AVG(beoordeling.rating) AS rating, video.videoPath, 
                                                video.uploadDatum, video.video_id, video.titel, video.videolengte, 
                                                video.views,gebruiker.voornaam, gebruiker.achternaam, gebruiker.gebruiker_id, opleiding.*
											FROM gebruiker 
											LEFT JOIN video 
												ON video.gebruiker_id = gebruiker.gebruiker_id
											LEFT JOIN beoordeling
												ON beoordeling.video_id = video.video_id
											LEFT JOIN video_vak 
												ON video_vak.video_id = video.video_id
											LEFT JOIN vak 
												ON vak.vak_id = video_vak.vak_id
                                            LEFT JOIN opleiding
												ON opleiding.opleiding_id = vak.opleiding_id
											WHERE video.views = (SELECT MAX(views) FROM video)
                                            LIMIT 1")[0];

    if (!empty(array_filter($videoResult))) 
    {
        $videoTools->videoContainer($videoResult);
    } 
    else 
    {
       $this->Set("videoBlock", $this->Get("VIDEO_GEEN"));
    }

    if (!empty(array_filter($voortgangResult))) 
    {
        $videoTools->thumbnailContainer($voortgangResult);
    } 
    else 
    {
       $this->Set("thumbnailContainer", $this->Get("VIDEO_GEEN"));
    }


    $tagData = $DB->Select("SELECT * FROM tag ORDER BY RAND() LIMIT 10");
    if(!empty($tagData))
    {   
        $tagContainer = null;
        foreach ($tagData as $key => $value) 
        {
            $tagContainer .= '<span class="videoTag link" data-link="/zoeken/tags/'.$value['tag_id'].'" style="width: max-content;">'.$value['naam'].'</span>';
        }

        $this->Set("tagContainer", $tagContainer);
    }
    else 
    {
        $this->Set("tagContainer", "No tags found.");
    }

    $docentData = $DB->Select("SELECT * FROM gebruiker WHERE level > 2  ORDER BY RAND() LIMIT 10");
    if(!empty($docentData))
    {
        $docentContainer = null;
        foreach ($docentData as $key => $value) 
        {
            $docentContainer .= '<span class="videoTag link" data-link="/profiel/'.$value['gebruiker_id'].'" style="width: max-content;">'.$value['voornaam'].' '.$value['achternaam'].'</span>';
        }

        $this->Set("docentContainer", $docentContainer);
    }
    else 
    {
        $this->Set("docentContainer", "No teachers found.");
    }

    $vakData = $DB->Select("SELECT * FROM vak  ORDER BY RAND() LIMIT 10");
    if(!empty($vakData))
    {
        $vakContainer = null;
        foreach ($vakData as $key => $value) 
        {
            $vakContainer .= '<span class="videoTag link" data-link="/zoeken/vak/'.$value['vak_id'].'" style="width: max-content;">'.$value['vak_naam'].'</span>';
        }

        $this->Set("vakContainer", $vakContainer);
    }
    else 
    {
        $this->Set("vakContainer", "No courses found.");
    }

?>