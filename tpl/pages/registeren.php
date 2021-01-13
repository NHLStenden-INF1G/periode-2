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
<?php 
    $this->Set("pageTitle", $this->Get("LOGIN_REGISTREREN"));
?>
<div class="mainWrapper"> 
    <main> 
        <div class="Spotlight">
            <div class="sectionTitle">{LOGIN_REGISTREREN}</div>
                <form method="post">
                    {regError}<br />
                    <label>{GEBRUIKERBEHEER_INVOEGEN_VOORNAAM}: <input type="text" name="voorNaam" placeholder="{GEBRUIKERBEHEER_INVOEGEN_VOORNAAM}" required></label><br />
                    <label>{GEBRUIKERBEHEER_INVOEGEN_ACHTERNAAM}: <input type="text" name="achterNaam" placeholder="{GEBRUIKERBEHEER_INVOEGEN_ACHTERNAAM}" required></label><br />
                    <label>Email: <input type="email" name="regEmail" placeholder="{GEBRUIKERBEHEER_INVOEGEN_EMAIL}" required></label><br />
                    <label>{PROFIEL_AANPASSEN_WACHTWOORD}: <input type="password" name="regPass1" placeholder="{PROFIEL_AANPASSEN_WACHTWOORD}" required></label><br />
                    <label>{GEBRUIKERBEHEER_INVOEGEN_HERHAAL_WACHTWOORD}: <input type="password" name="regPass2" placeholder="{GEBRUIKERBEHEER_INVOEGEN_HERHAAL_WACHTWOORD}" required></label><br />

                    <button type="submit" name="regSubmit" required>{LOGIN_REGISTREREN}</button>
                </form>
            </div>
        </div>
    </main>     
</div>