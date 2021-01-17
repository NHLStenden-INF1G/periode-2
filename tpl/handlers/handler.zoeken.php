<?php 
        $this->Set("pageTitle", $this->Get("NAV_ZOEKEN"));
        $this->Set("extraCSS", '<link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/canvas_special.css">
                                <link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/zoeken.css">');

        if(!empty($_POST['searchTerm']) && empty($_GET))
        {
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

            if(isset($searchTermInt))
            {
                $searchResult['opleidingResult'] = $search->getOpleidingen($searchTermString, $searchTermInt);//Opleidingnaam + jaar
            }

            $searchResult['docentVideoResult'] = $search->getDocent($searchTermString);//Docent
            $searchResult['videoResult'] = $search->getVideo($searchTermString);//Videotitel
            $searchResult['tagsResult'] = $search->getTags($searchTermString);//Videotags
            $searchResult['vakResult'] = $search->getVak($searchTermString);//Vakresultaat
            
            $searchResultJSON = json_encode($searchResult);
            header("searchResult: {$searchResultJSON}");
        }
        else if(empty($_GET))
        {
            $core->Redirect("/error");
        }
        else if(!empty($_GET))
        {
            $search->searchHandler($_GET);
        }
?>