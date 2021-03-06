    <canvas id="canvas" class="pageCanvas"></canvas>
    <canvas class="videoCanvas"></canvas>

    <nav>
        <img alt="Logo" class="link" data-link="/start" src="{assetsFolder}/images/logo.png"/>
        <div class="inputSearch">
            <i class="fa fa-search icon"></i>
            <input id="liveInput" type="text" onkeyup="showResult(this.value)" placeholder="{NAV_ZOEKEN}...">
        </div>
        <div id="livesearch"></div>
        <ul class="navigationLink">
            {loginKnop}
            <li class="link {activePageStart}" data-link="/">
                <span>Start</span>
            </li>
            <li class="link {activePagePopulair}" data-link="/populair">
                <span>{NAV_POPULAIR}</span>
            </li>
            <li class="langSwitch">{taalKnop}</li>

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
    