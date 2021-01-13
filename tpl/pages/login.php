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
    $this->Set("pageTitle", $this->Get("LOGIN_INLOGGEN"));
?>
<div class="mainWrapper"> 
    <main> 
        <div class="Spotlight">
            <div class="sectionTitle">{LOGIN_INLOGGEN}</div>
                <form method="post">
                    {loginError}<br />
                    <label>Email: <input type="email" name="loginEmail" placeholder="{GEBRUIKERBEHEER_INVOEGEN_EMAIL}" required></label><br />
                    <label>{PROFIEL_AANPASSEN_WACHTWOORD}: <input type="password" name="loginPassword" placeholder="{PROFIEL_AANPASSEN_WACHTWOORD}" required></label><br />
                    <button type="submit" name="loginSubmit" required>{LOGIN_SIGN}</button>
                    <span class="link button" data-link="registeren" style="background-color: #7B241C;">{LOGIN_AANMELDEN}</span>
                </form>
               
            </div>
        </div>
    </main>     
</div>