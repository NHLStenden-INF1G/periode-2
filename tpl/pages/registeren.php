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
        <div class="Spotlight">
            <div class="sectionTitle">Registreren</div>
                <form method="post">
                    {regError}<br />
                    <label>Voornaam: <input type="text" name="voorNaam" placeholder="Voornaam" required></label><br />
                    <label>Achternaam: <input type="text" name="achterNaam" placeholder="Achternaam" required></label><br />
                    <label>Email: <input type="email" name="regEmail" placeholder="Emailadres" required></label><br />
                    <label>Wachtwoord: <input type="password" name="regPass1" placeholder="Wachtwoord" required></label><br />
                    <label>Herhaal wachtwoord: <input type="password" name="regPass2" placeholder="Herhaal Wachtwoord" required></label><br />

                    <button type="submit" name="regSubmit" required>Registeren</button>
                </form>
            </div>
        </div>
    </main>     
</div>