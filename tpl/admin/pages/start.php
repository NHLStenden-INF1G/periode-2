<?php 
    $this->Set("pageTitle", $this->Get("ADMIN_TITEL"));
?>
<div style="grid-row: 1;">
<div class="sectionTitle" >{ADMIN_KEUZEMENU}</div>
    <p>{ADMIN_TEKST}</p>
    <div class="adminRow">
        <div class="adminBlock link" style="background-color: #794040;" data-link="/admin/suggestiebeheer">
            <span><i class="fa fa-bullhorn"></i> {ADMIN_SUGGESTIEBEHEER}</span>
        </div>
        <div class="adminBlock link" data-link="/admin/videobeheer">
            <span><i class="fa fa-video-camera"></i> {ADMIN_VIDEOBEHEER}</span>
        </div>
        <div class="adminBlock link" style="background-color: #5c4592;" data-link="/admin/gebruikerbeheer">
            <span><i class="fa fa-users"></i> {ADMIN_GEBRUIKERBEHEER}</span>
        </div>
        <div class="adminBlock link" style="background-color: #1e6b2b;" data-link="/admin/vakkenbeheer">
            <span><i class="fa fa-book"></i> {ADMIN_VAKKENBEHEER}</span>
        </div>
        <div class="adminBlock link" style="background-color: #6b1e5a;" data-link="/admin/opleidingbeheer">
            <span><i class="fa fa-building"></i> {ADMIN_OPLEIDINGBEHEER}</span>
        </div>
        <div class="adminBlock link" style="background-color: #859435;" data-link="/admin/tagbeheer">
            <span><i class="fa fa-tags"></i> {ADMIN_TAGBEHEER}</span>
        </div>
    </div>
</div>