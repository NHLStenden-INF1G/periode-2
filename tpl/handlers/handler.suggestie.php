<?php 

$user->Redirect(false);
$this->Set("extraCSS", '<link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/canvas_special.css">');
$this->Set("pageTitle", $this->Get("NAV_SUGGESTIE"));

if(isset($_POST["submit"])) {
    $tekst = $filter->sanatizeInput($_POST["tekst"], "string");

    if($link = $filter->validateInput($_POST["link"], "url")) {
        echo "Suggestie gemaakt!";
        $DB->Insert("INSERT INTO suggestie (gebruiker_id, link, tekst, status) VALUES (?, ?, ?, ?)", [$user->id, $link, $tekst, 0]);
    }
    else {
        echo "<p><b>{SUGGESTIE_ERROR}</b></p><br>";
    }
}
?>