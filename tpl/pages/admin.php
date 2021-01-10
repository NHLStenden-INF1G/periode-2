<style>
.mainWrapper > main {
    height: max-content;
    grid-template-rows: unset;
}

body { 
    grid-template-rows: 110px 1vw auto 2vw;
}
#canvas {
    height: 100%;
}
</style>

<div class="mainWrapper"> 
    <main>
        <?= AdminContent; ?>
    </main>
</div>