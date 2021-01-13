<style>
.mainWrapper > main {
    height: max-content;
    grid-template-rows: unset;
}

body { 
    grid-template-rows: 110px 6vw auto 2vw;
}
#canvas {
    height: 100%;
}
</style>
<div class="mainWrapper"> 
    <main>
    <div style="grid-row: 1;">
    <?php
    $this->Set("pageTitle", $this->Get("NAV_SUGGESTIE"));

            if(isset($_POST["submit"])) {
                $tekst = $filter->sanatizeInput($_POST["tekst"], "string"); // moet nog sanitised worden

                if($link = $filter->validateInput($_POST["link"], "url")) {
                    $DB->Insert("INSERT INTO suggestie (gebruiker_id, link, tekst, status) VALUES (?, ?, ?, ?, ?)", [$user->id, $link, $tekst, 0]);
                }
                else {
                    echo "<p><b>{SUGGESTIE_ERROR}</b></p><br>";
                }
            }
        ?> 
    <p>{SUGGESTIE_TEKST}</p><br>
    <form method="post">
        <p>Link: <input type="text" name="link" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ"></p>
        <p>{SUGGESTIEBEHEER_TEKST}: <input type="text" name="tekst" placeholder="{SUGGESTIEBEHEER_TEKST}"></p>
        <button type="submit" name="submit">{BEHEER_OPSLAAN}</button>
    </form>
    </div>
    </main>
</div>

