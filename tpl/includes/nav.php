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
        <li class="link" data-link="/zoeken">
            <span>Zoeken</span>
        </li>
        <li class="link" data-link="/populair">
            <span>Populair</span>
        </li>
        {loginKnop}
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