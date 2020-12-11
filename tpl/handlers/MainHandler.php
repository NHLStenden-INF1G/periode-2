<?php
if ($user->logged_in)
{
	$this->Set('username', $user->username);
	$this->Set('id', $user->data('id'));
}

?>