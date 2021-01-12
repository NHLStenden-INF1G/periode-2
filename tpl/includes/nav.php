<canvas id="canvas" class="pageCanvas"></canvas>

<nav>
    <img class="link" data-link="/start" src="{assetsFolder}/images/logo.png"/>
    <div class="inputSearch">
        <i class="fa fa-search icon"></i>
        <input id="liveInput" type="text" onkeyup="showResult(this.value)" placeholder="{NAV_ZOEKEN}...">
    </div>
    <div id="livesearch"></div>
    <ul>
    {loginKnop}

        <li class="link" data-link="/">
            <span>Start</span>
        </li>

        <li class="link" data-link="/populair">
            <span>{NAV_POPULAIR}</span>
        </li>
        {taalKnop}

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