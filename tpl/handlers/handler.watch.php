<?php
   
   if(!empty($_POST) && !isset($_GET['Path_2']) )
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
   else if(isset($_GET['Path_2']))
   {
       $videoTools->videoActionHandler($_POST, $_GET['Path_2']);
   }

   if(isset($_POST['deleteCommentSubmit'])) 
   {
       $commentID = $filter->sanatizeInput($_POST['deleteCommentID'], "int");
       $videoTools->deleteComment($commentID);
   }


   if(isset($_GET['Path_1']))
   {
        $id = $filter->sanatizeInput($_GET['Path_1'], "int");

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
                                    WHERE video.video_id= ?
                                    LIMIT 1", [$id])[0];

                
        if (empty(array_filter($videoResult))) 
        {
            $core->Redirect("/error");
        }
        $this->Set("pageTitle", $videoResult['titel']);
        $voortgangResult = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam
                                            FROM video 
                                            INNER JOIN gebruiker
                                            ON gebruiker.gebruiker_id = video.gebruiker_id
                                            WHERE video.video_id != ?
                                            ORDER BY RAND()
                                            LIMIT 3", [$videoResult['video_id']]);
        

        $videoTools->videoContainer($videoResult);

    
        if (!empty(array_filter($voortgangResult))) 
        {
            $videoTools->thumbnailContainer($voortgangResult);
        } 
        else 
        {
           $this->Set("thumbnailContainer", $this->Get("VIDEO_GEEN"));
        }
   }
   else
   {
       header("Location: /error");
   }

?>