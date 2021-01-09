<?php
    if(!empty($_POST) && !isset($_GET['Path_2'])){
        
        if($_POST[array_keys ($_POST)[0]]){
            $chosenRating = $filter->sanatizeInput($_POST[array_keys ($_POST)[0]], "int");


            $videoID =  $filter->sanatizeInput($_POST['videoID'], "int");

            $DB->Insert("INSERT INTO beoordeling (rating, video_id, gebruiker_id) VALUES (?, ?, ?)", [$chosenRating, $videoID, $user->id]);

            header("Refresh:0");
        }
    }
    else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'viewupdate') {
        $videoID = $filter->sanatizeInput($_POST['videoID'], "int");
        $DB->Update("UPDATE video SET views = views + 1 WHERE video_id = ?", [$videoID]);
    }
    else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'watchupdate'  && $user->logged_in) {
        $videoID = $filter->sanatizeInput($_POST['videoID'], "int");
        $timestamp = $filter->sanatizeInput($_POST['timestamp'], "int");
        
        $selectVoortgang = $DB->Select("SELECT * FROM voortgang WHERE video_id = ? AND gebruiker_id = ?", [$videoID, $user->id]);
        if(empty($selectVoortgang))
        {
            $DB->Insert("INSERT INTO voortgang (gebruiker_id, video_id, timestamp) VALUES (?, ?, ?)", [$user->id, $videoID, $timestamp]);
        }
        else {
            $DB->Update("UPDATE voortgang SET timestamp = ?, datum = current_timestamp() WHERE video_id = ? AND gebruiker_id = ?", [$timestamp, $videoID, $user->id]);
        }
    }
    else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'watchprogess' && $user->logged_in) {
        $videoID = $filter->sanatizeInput($_POST['videoID'], "int");
        $selectVoortgang = $DB->Select("SELECT timestamp FROM voortgang WHERE video_id = ? AND gebruiker_id = ?", [$videoID, $user->id])[0]['timestamp'];
       
        if(!empty($selectVoortgang)){
            header("timestamp: {$selectVoortgang}");
        }
        else {
           // header("timestamp: 0");
        }
    }

?>