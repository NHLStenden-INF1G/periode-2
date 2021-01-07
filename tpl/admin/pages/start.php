
<div style="grid-row: 1;">
<div class="sectionTitle" >admin keuzemenu</div>
    <div class="adminRow">
        <div class="adminBlock link" data-link="/admin/suggestiebeheer">
            <span><i class="fa fa-bullhorn"></i> Suggestiebeheer</span>
        </div>
        <div class="adminBlock link" data-link="/admin/videobeheer">
            <span><i class="fa fa-video-camera"></i> Videobeheer</span>
        </div>
        <div class="adminBlock link" data-link="/admin/gebruikerbeheer">
            <span><i class="fa fa-users"></i> Gebruikerbeheer</span>
        </div>
        <div class="adminBlock link" data-link="/admin/vakkenbeheer">
            <span><i class="fa fa-book"></i> vakkenbeheer</span>
        </div>
        <div class="adminBlock link" data-link="/admin/opleidingbeheer">
            <span><i class="fa fa-building"></i> opleidingbeheer</span>
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