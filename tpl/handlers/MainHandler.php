<?php
$this->Set("extraCSS", "");
$this->Set("extraJS", "");

if ($user->logged_in)
{
	$this->Set('email', $user->email);
	$this->Set('id', $user->data('id'));
	$this->Set('username', $user->naam);

	$this->dropdownMenu();

	$this->Set('loginKnop', '<ul class="dropdown '.$this->Get('activePageAdmin').'">
				<li class="user"><span><i class="fa fa-user" aria-hidden="true"></i> '.$user->naam.'</span></li>
				<li class="dropdown-content">'.$this->Get('dropdownMenu').'
				</li>
			</ul>');
}
else 
{
	$this->Set('loginKnop', '<li class="link" data-link="/login"><span>'.$this->Get('NAV_AANMELDEN').'</span></li>');
}

?>