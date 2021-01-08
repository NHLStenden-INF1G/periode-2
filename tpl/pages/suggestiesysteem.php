<div class="mainWrapper"> 
    <main>
    <div style="grid-row: 1;">
    <?php
            if(isset($_POST["submit"])) {
                $datum = date('Y-m-d H:i:s');
                $gebruikerID = $user->id;
                $status = 0; // 0 = nog niet bekeken, 1 = geaccepteerd, 2 = geweigerd.
                $tekst = $_POST["tekst"]; // moet nog sanitised worden

                if($link = $filter->validateInput($_POST["link"], "url")) {
                    $DB->Insert("INSERT INTO suggestie (gebruiker_id, datum, link, tekst, status) VALUES (?, ?, ?, ?, ?)", [$gebruikerID, $datum, $link, $tekst, $status]);
                }
                else {
                    echo "<p><b>Geen geldige URL</b></p><br>";
                }
            }
        ?> 
    <p>Doe hier je suggestie voor een video. De suggestie moet een link zijn van bijvoorbeeld een youtube video, met een tekst waarom je deze video wil, of gewoon een suggestie als tekst.</p><br>
    <form method="post">
        <p>Link: <input type="text" name="link"></p>
        <p>Tekst: <input type="text" name="tekst"></p>
        <button type="submit" name="submit">Verzenden</button>
    </form>
    </div>
    </main>
</div>

