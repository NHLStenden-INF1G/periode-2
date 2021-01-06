<?php


if ($user->logged_in)
{
	$this->Set('email', $user->email);
	$this->Set('id', $user->data('id'));
	$this->Set('username', $user->naam);

	$dropdownMenu = null;
	foreach ($user->userPermissions($user->rank) as $key => $value) 
	{
		$dropdownMenu .= "<div class='link' data-link='{$value[1]}'>
								<p>
									<i class='{$value[0]}' aria-hidden='true'></i>
									{$this->Get($key)}
								</p>
							</div>";
	}

		$this->Set('loginKnop', '<li class="dropdown">
								<span><i class="fa fa-user" aria-hidden="true"></i>
									'.$user->naam.'</span>
								<div class="dropdown-content">
									'.$dropdownMenu.'
								</div>
							</li>');
}
else {
	$this->Set('loginKnop', '<li class="link" data-link="/login"><span>Aanmelden</span></li>');
}

?>