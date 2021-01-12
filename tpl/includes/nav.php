<canvas id="canvas" class="pageCanvas"></canvas>

<nav>
    <img class="link" data-link="/start" src="{assetsFolder}/images/logo.png"/>
    <div class="inputSearch">
        <i class="fa fa-search icon"></i>
        <input id="liveInput" type="text" onkeyup="showResult(this.value)" placeholder="Zoeken...">
    </div>
    <div id="livesearch"></div>
    <ul>
        <li class="link" data-link="/">
            <span>Start</span>
        </li>
        <?php 
        if($user->logged_in) {
            echo '<li class="link" data-link="/suggestie">
                    <span>Suggestie</span>
                </li>';
        }
        ?>

        <li class="link" data-link="/populair">
            <span>Populair</span>
        </li>
        {loginKnop}
        <li>
            <span>NL</span>
        </li>
    </ul>
    <div class="mobileBurger">
        <div></div>
        <div></div>
        <div></div>
    </div>
</nav>

<div class="pageTitle">
    <span>{pageTitle}</span>
</div>