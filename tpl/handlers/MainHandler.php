<?php
if ($user->logged_in)
{
	$this->Set('email', $user->email);
	$this->Set('id', $user->data('id'));
	$this->Set('loginKnop', '<li class="link" data-link="/profiel/'.$user->id.'"><span><i class="fa fa-user" aria-hidden="true"></i>	'.$user->naam.'</span></li>');
}
else {
	$this->Set('loginKnop', '<li class="link" data-link="login"><span>Aanmelden</span></li>');
}

?>