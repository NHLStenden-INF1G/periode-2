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
            <div class="sectionTitle">Inloggen</div>
                <form method="post">
                    {loginError}<br />
                    <label>Email: <input type="email" name="loginEmail" placeholder="Emailadres" required></label><br />
                    <label>Wachtwoord: <input type="password" name="loginPassword" placeholder="Wachtwoord" required></label><br />
                    <button type="submit" name="loginSubmit" required>Aanmelden</button>
                    <span class="link button" data-link="registeren" style="background-color: #7B241C;">Registreren</span>
                </form>
               
            </div>
        </div>
    </main>     
</div>