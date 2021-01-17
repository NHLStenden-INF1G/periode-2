<?php 
    ($this->path == 'populair') ? $this->Set("activePagePopulair", "active") : '';
    $this->Set("pageTitle", $this->Get("NAV_POPULAIR"));

    $datumDag = date("Y-m-d", strtotime("-1 day")); 
    $datumWeek = date("Y-m-d", strtotime("-1 week")); 
    $datumMaand = date("Y-m-d", strtotime("-1 month")); 

    $voortgangResult['POPULAR_DAY'] = $DB->Select("SELECT video.*, gebruiker.voornaam, gebruiker.achternaam
                                        FROM video
                                        INNER JOIN gebruiker ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE uploadDatum > ?
                                        ORDER BY views DESC
                                        LIMIT 3", [$datumDag]);

    $videoIDs = array_column($voortgangResult['POPULAR_DAY'], 'video_id');
    $clause = "AND video.video_id NOT IN (" . implode(',', array_fill(0, count($videoIDs), '?')) . ")";
    
    if(empty($videoIDs))
    {
        $clause = "";
    }
          
    $voortgangResult['POPULAR_WEEK'] = $DB->Select("SELECT *
                                        FROM video
                                        INNER JOIN gebruiker ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE uploadDatum > ?
                                        {$clause}
                                        ORDER BY views DESC
                                        LIMIT 3",array_merge([$datumWeek], $videoIDs));

    $videoIDs = array_merge($videoIDs, array_column($voortgangResult['POPULAR_WEEK'], 'video_id'));
    $clause = "AND video.video_id NOT IN (" . implode(',', array_fill(0, count($videoIDs), '?')) . ")";
    
    if(empty($videoIDs))
    {
        $clause = "";
    }

    $voortgangResult['POPULAR_MONTH'] = $DB->Select("SELECT *
                                        FROM video
                                        INNER JOIN gebruiker ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE uploadDatum > ?
                                        {$clause}
                                        ORDER BY views DESC
                                        LIMIT 3",array_merge([$datumMaand], $videoIDs));

    if(!empty($voortgangResult)) 
    {
        foreach ($voortgangResult as $key => $value) 
        {
            $videoTools->thumbnailContainer($voortgangResult[$key], $key.'_container');
        }     
    }
    else 
    {
        echo '{VIDEO_GEEN}';
    }
?>