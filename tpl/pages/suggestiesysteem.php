<div class="mainWrapper"> 
    <main>
    <div style="grid-row: 1;">
    <?php
            if(isset($_POST["submit"])) {
                $datum = date('Y-m-d H:i:s');
                $gebruikerID = 1; //dit moet later weg
                //$gebruikerID = $user->id;

                if($link = $filter->validateInput($_POST["link"], "url")) {
                    $DB->Insert("INSERT INTO suggestie (gebruiker_id, datum, link) VALUES (?, ?, ?)", [$gebruikerID, $datum, $link]);
                }
                else {
                    echo "Geen geldige URL";
                }
            }
        ?> 
    <p>Doe hier je suggestie voor een video. De suggestie moet een link zijn van bijvoorbeeld een youtube video.</p>
    <form method="post">
        <p>Link: <input type="text" name="link"></p>
        <button type="submit" name="submit">Verzenden</button>
    </form>
    </div>
    </main>
</div>

