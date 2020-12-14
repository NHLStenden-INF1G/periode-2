<?php
class User
{
	public $logged_in = false;
	public $email, $naam, $id, $rank = 0;

	private $data;

	function data($field)
	{
		return ((isset($this->data[$field]) && $this->logged_in) ? $this->data[$field] : '');
	}

	function __construct()
	{
		global $core, $DB;

		if (isset($_SESSION['email']))
		{
			$this->email = $_SESSION['email'];
			$this->logged_in = true;

			$this->data = $DB->Select("SELECT * FROM gebruikers WHERE email = ?", array($this->email))->fetch_assoc();

			if ($this->data === false)
			{
				$this->Logout();
			}

			$this->id = intval($this->data('gebruiker_id'));
			$this->rank = intval($this->data('level'));
			$this->naam = $this->data('voornaam').' '.$this->data('achternaam');
		} 
	}

	function Login($email, $password)
	{
		global $DB, $filter;

		$email 		= $filter->sanatizeInput($email, 'email');
		$password 	= $filter->sanatizeInput($password, 'string');

		$userInfo 	= $DB->Select("SELECT * FROM gebruikers WHERE email = ?", array($email));

		if ($userInfo === false)
		{
			return 1;
		}

		$userData   = $userInfo->fetch_assoc();

		if (!password_verify($password, $userData['wachtwoord']))
		{
			return 2;
		}

		$_SESSION['email'] = $email;

		
		return false;
	}

	function Register($voorNaam, $achterNaam, $regEmail, $regPass1, $regPass2)
	{
		global $DB, $filter;

		$voorNaam 		= $filter->sanatizeInput($voorNaam, 'string');
		$achterNaam 	= $filter->sanatizeInput($achterNaam, 'string');
		$regEmail 		= $filter->sanatizeInput($regEmail, 'email');
		$regPass1 		= $filter->sanatizeInput($regPass1, 'string');
		$regPass2 		= $filter->sanatizeInput($regPass2, 'string');

		$userInfo 	= $DB->Exists("SELECT * FROM gebruikers WHERE email = ?", array($regEmail));
	
		if ($userInfo)
		{
			return 1;
		}

		if($regPass1 != $regPass2)
		{
			return 2;
		}

		$regPass2 = password_hash($regPass2, PASSWORD_DEFAULT);
		$DB->Insert("INSERT INTO gebruikers (email, voornaam, achternaam, wachtwoord, level) 
							VALUES ('{$regEmail}', '{$voorNaam}', '{$achterNaam}', '{$regPass2}', '1')");

		return false;
	}

	function IdName($id)
	{
		global $DB;

		$row = $DB->GetRow("SELECT username FROM users WHERE id = '".$id."'", Array('username' => ''));
		return $DB->Out($row['username']);
	}

	function NameId($name)
	{
		global $DB;

		$row = $DB->GetRow("SELECT id FROM users WHERE username = '".$name."'", Array('id' => '0'));
		return intval($row['id']);
	}

	function Redirect($if_logged_in)
	{
		global $core;

		if ($this->logged_in == $if_logged_in)
		{
			$core->Redirect(Config::$loginStartpage);
		}
	}

	function LoginCheck()
	{
		global $core;

		if (!$this->logged_in)
		{
			$core->Redirect('/start');
		}
	}

	function Logout()
	{
		global $core;

		session_destroy();
		$core->Redirect('/start');
	}
}
?>