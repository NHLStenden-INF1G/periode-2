
<div style="grid-row: 1;">
<div class="sectionTitle" >admin keuzemenu</div>
    <p>Welkom op het beheerpaneel van {siteName}! Via onderstaande blokken kunt u navigeren naar de gekozen beheerpagina.</p>
    <div class="adminRow">
        <div class="adminBlock link" style="background-color: #794040;" data-link="/admin/suggestiebeheer">
            <span><i class="fa fa-bullhorn"></i> Suggestiebeheer</span>
        </div>
        <div class="adminBlock link" data-link="/admin/videobeheer">
            <span><i class="fa fa-video-camera"></i> Videobeheer</span>
        </div>
        <div class="adminBlock link" style="background-color: #5c4592;" data-link="/admin/gebruikerbeheer">
            <span><i class="fa fa-users"></i> Gebruikerbeheer</span>
        </div>
        <div class="adminBlock link" style="background-color: #1e6b2b;" data-link="/admin/vakkenbeheer">
            <span><i class="fa fa-book"></i> vakkenbeheer</span>
        </div>
        <div class="adminBlock link" style="background-color: #6b1e5a;" data-link="/admin/opleidingbeheer">
            <span><i class="fa fa-building"></i> opleidingbeheer</span>
        </div>
        <div class="adminBlock link" style="background-color: #859435;" data-link="/admin/tagbeheer">
            <span><i class="fa fa-tags"></i> tagbeheer</span>
        </div>
    </div>
</div>
<style>

.adminRow {
    display: flex; 
    justify-content: space-between;
}

.adminBlock {
    background-color: #318c91; 
    width: 11vw; 
    display: flex; 
    justify-content: center; 
    align-items: center;
    height: 10vw;
    transition: 0.3s;
}
.adminBlock > span {
    font-family: 'Bebas Neue', cursive;
    color: white;
    font-size: 1vw;
    letter-spacing: 0.15vw;
}
.adminBlock:hover {
    transform: scale(1.05);
}
</style>