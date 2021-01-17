<?php 
class Search {

    function getOpleidingen($searchTermString, $searchTermInt, $queryLimit = 10)
    {
        global $DB;
        return $DB->Select("SELECT DISTINCT * FROM opleiding  WHERE opleiding.naam LIKE CONCAT('%',?,'%') AND opleiding.jaar = ? LIMIT ?", 
                            [$searchTermString, $searchTermInt, $queryLimit]);
    }

    function getDocent($searchTermString, $queryLimit = 10)
    {
        global $DB;
        return $DB->Select("SELECT DISTINCT gebruiker.voornaam, gebruiker.achternaam, gebruiker.gebruiker_id
                                    FROM gebruiker
                                    WHERE gebruiker.achternaam LIKE CONCAT('%',?,'%') OR gebruiker.voornaam LIKE CONCAT('%',?,'%')
                                    AND gebruiker.level > 2 LIMIT ?", [$searchTermString, $searchTermString, $queryLimit]);
    }

    function getVideo($searchTermString, $queryLimit = 10)
    {
        global $DB;
        return $DB->Select("SELECT DISTINCT vak.*, video.*, gebruiker.voornaam, gebruiker.achternaam
                        FROM video 
                        INNER JOIN video_vak 
                            ON video_vak.video_id = video.video_id
                        INNER JOIN vak 
                            ON vak.vak_id = video_vak.vak_id
                        INNER JOIN gebruiker
                            ON gebruiker.gebruiker_id = video.gebruiker_id
                        WHERE video.titel LIKE CONCAT('%',?,'%')  LIMIT ?", [$searchTermString, $queryLimit]);
    }

    function getTags($searchTermString, $queryLimit = 5)
    {
        global $DB;
        return $DB->Select("SELECT DISTINCT * FROM tag WHERE tag.naam LIKE CONCAT('%',?,'%') LIMIT ?", [$searchTermString, $queryLimit]);
    }

    function getVak($searchTermString, $queryLimit = 10)
    {
        global $DB;
        return $DB->Select("SELECT DISTINCT * FROM vak WHERE vak.vak_naam LIKE CONCAT('%',?,'%') LIMIT ?", [$searchTermString, $queryLimit]);
    }

    function searchHandler($GET)
    {
        global $TPL;

        switch ($GET['Path_1']) {
            case 'opleiding':
                $html = '<div class="sectionTitle">'.$TPL->Get("EDUCATION").'</div>'; 
                (isset($GET['Path_2'])) ? $this->opleidingContainer($GET['Path_2']) : '';
                break;
            case 'tags':
                $html = '<div class="sectionTitle">'.$TPL->Get("TAGS").'</div>'; 
                (isset($GET['Path_2'])) ? $this->tagContainer($GET['Path_2']) : '';
                break;
            case 'vak':
                $html = '<div class="sectionTitle">'.$TPL->Get("SEARCH_VAK").'</div>'; 
                (isset($GET['Path_2'])) ? $this->vakContainer($GET['Path_2']) : '';
                break;
        }
        return $TPL->Set("searchResult", $html);
    }

    function opleidingContainer($searchID) 
    {
        global $DB, $filter, $videoTools;
        $searchID = $filter->sanatizeInput($searchID, "int");
        $opleidingResultVideo = $DB->Select("SELECT DISTINCT vak.*, video.*, gebruiker.voornaam, gebruiker.achternaam  
                                                        FROM opleiding 
                                                        INNER JOIN vak 
                                                        ON vak.opleiding_id = opleiding.opleiding_id
                                                        INNER JOIN video_vak 
                                                        ON video_vak.vak_id = vak.vak_id
                                                        INNER JOIN video
                                                        ON video.video_id = video_vak.video_id
                                                        INNER JOIN gebruiker
                                                        ON gebruiker.gebruiker_id = video.gebruiker_id
                                                        WHERE opleiding.opleiding_id = ?", [$searchID]);
                                                        
        $videoTools->thumbnailContainer($opleidingResultVideo);

    }

    function tagContainer($searchID) 
    {
        global $DB, $filter, $videoTools;
        $searchID = $filter->sanatizeInput($searchID, "int");
        $tagResultVideo = $DB->Select("SELECT * FROM tag_video 
                                                INNER JOIN video 
                                                ON video.video_id = tag_video.video_id
                                                INNER JOIN gebruiker 
                                                ON video.gebruiker_id = gebruiker.gebruiker_id
                                                WHERE tag_video.tag_id = ?", [$searchID]);
                                                        
        $videoTools->thumbnailContainer($tagResultVideo);

    }

    function vakContainer($searchID) 
    {
        global $DB, $filter, $videoTools;
        $searchID = $filter->sanatizeInput($searchID, "int");
        $vakResultVideo = $DB->Select("SELECT * FROM vak 
                                                INNER JOIN video_vak 
                                                ON video_vak.vak_id = vak.vak_id
                                                INNER JOIN video
                                                ON video.video_id = video_vak.video_id
                                                INNER JOIN gebruiker
                                                ON video.gebruiker_id = gebruiker.gebruiker_id
                                                WHERE vak.vak_id = ?", [$searchID]);
                                                        
        $videoTools->thumbnailContainer($vakResultVideo);

    }
}

?>