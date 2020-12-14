<div class="mainWrapper"> 
    <main> 
        <div class="Spotlight">
            <div class="sectionTitle">Inloggen</div>
                <form method="post">
                    {loginError}<br />
                    <label>Email: <input type="email" name="loginEmail" placeholder="Emailadres" required></label><br />
                    <label>Wachtwoord: <input type="password" name="loginPassword" placeholder="Wachtwoord" required></label><br />
                    <button type="submit" name="loginSubmit" required>Aanmelden</button>
                    <div class="link button" data-link="registeren">Registreren</div>
                </form>
               
            </div>
        </div>
    </main>     
</div>