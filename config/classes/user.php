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

		$this->data = $DB->Select("SELECT * FROM gebruiker WHERE email = ?", [$this->email])[0];

			if ($this->data === false)
			{
				$this->Logout();
			}

			$this->id = intval($this->data('gebruiker_id'));
			$this->rank = intval($this->data('level'));
			$this->naam = $this->data('voornaam').'	&nbsp;'.$this->data('achternaam');
		} 
	}

	function Login($email, $password)
	{
		global $DB, $filter;

		$email 		= $filter->sanatizeInput($email, 'email');
		$password 	= $filter->sanatizeInput($password, 'string');

		$userInfo 	= $DB->Select("SELECT * FROM gebruiker WHERE email = ? LIMIT 1",[$email]);

		if (empty($userInfo))
		{
			return 1;
		}

		if (!password_verify($password, $userInfo[0]['wachtwoord']))
		{
			return 2;
		}

		$_SESSION['email'] = $email;

		
		return false;
	}

	function Register($voorNaam, $achterNaam, $regEmail, $regPass1, $regPass2, $level = 1)
	{
		global $DB, $filter;

		$voorNaam 		= $filter->sanatizeInput($voorNaam, 'string');
		$achterNaam 	= $filter->sanatizeInput($achterNaam, 'string');
		$regEmail 		= $filter->sanatizeInput($regEmail, 'email');
		$regPass1 		= $filter->sanatizeInput($regPass1, 'string');
		$regPass2 		= $filter->sanatizeInput($regPass2, 'string');

		$emailLijst = array(
			'student.nhlstenden.com',
			'nhlstenden.com'
		);
		
		$emailDomein = explode("@", $regEmail)[1];
		
		
		if (!in_array($emailDomein, $emailLijst))
		{
			return 3;
		}

		$userInfo 	= $DB->Select("SELECT * FROM gebruiker WHERE email = ?", [$regEmail]);

		if (!empty($userInfo))
		{
			return 1;
		}

		if($regPass1 != $regPass2)
		{
			return 2;
		}

		$regPass2 = password_hash($regPass2, PASSWORD_DEFAULT);

		$DB->Insert("INSERT INTO gebruiker (email, voornaam, achternaam, wachtwoord, level) 
							VALUES (?, ?, ?, ?, ?)", 
							[$regEmail, $voorNaam, $achterNaam, $regPass2, $level]);

		return false;
	}

	function Edit($voorNaam, $achterNaam, $regEmail, $regPass1, $regPass2, $level, $userID)
	{
		global $DB, $filter;

		$voorNaam 		= $filter->sanatizeInput($voorNaam, 'string');
		$achterNaam 	= $filter->sanatizeInput($achterNaam, 'string');
		$regEmail 		= $filter->sanatizeInput($regEmail, 'email');
		$regPass1 		= $filter->sanatizeInput($regPass1, 'string');
		$regPass2 		= $filter->sanatizeInput($regPass2, 'string');

		$emailLijst = array(
			'student.nhlstenden.com',
			'nhlstenden.com'
		);
		
		$emailDomein = explode("@", $regEmail)[1];
		
		
		if (!in_array($emailDomein, $emailLijst))
		{
			return 3;
		}

		if($regPass1 != $regPass2)
		{
			return 2;
		}

		$regPass2 = password_hash($regPass2, PASSWORD_DEFAULT);

		$DB->Update("UPDATE gebruiker SET email = ?, voornaam = ?, achternaam = ?, wachtwoord = ?, level = ?
						WHERE gebruiker_id = ?", 
							[$regEmail, $voorNaam, $achterNaam, $regPass2, $level, $userID]);

		return false;
	}

	function userLevel($id) {
		global $DB;

		$gebruikerResult = $DB->Select("SELECT level FROM gebruiker WHERE gebruiker_id = ? LIMIT 1", [$id])[0];

			switch ($gebruikerResult['level']) 
			{
				case 1:
				default:
					return 'Student';
					break;
				case 2:
					return 'Docent';
					break;			
				case 3:
					return 'Administrator';
					break;		
			}
		
	}

	function userLevelName($type) {
		global $DB;

			switch ($type) 
			{
				case 1:
				default:
					return 'Student';
					break;
				case 2:
					return 'Docent';
					break;			
				case 3:
					return 'Administrator';
					break;		
			}
		
	}
	
	function userPermissions($level)
	{
		switch ($level) 
		{
			case 1:
			default:
				//Student
				return array(
					'NAV_MIJNPROFIEL' => array('fa fa-user-circle-o', '/profiel/'.$this->id),
					'NAV_UITLOGGEN' => array('fa fa-sign-out', '/logout'),

				);
			break;
			case 2:
				//Docent
				return array(
					'NAV_MIJNPROFIEL' => array('fa fa-user-circle-o', '/profiel/'.$this->id),
					'NAV_ADMIN' => array('fa fa-cogs', '/admin/'),
					'NAV_UITLOGGEN' => array('fa fa-sign-out', '/logout'),

				);			
			break;			
			case 3:
				//Administrator
				return array(
					'NAV_MIJNPROFIEL' => array('fa fa-user-circle-o', '/profiel/'.$this->id),
					'NAV_ADMIN' => array('fa fa-cogs', '/admin/'),
					'NAV_UITLOGGEN' => array('fa fa-sign-out', '/logout'),
				);			
			break;		
		}
	}

	function Redirect($if_logged_in)
	{
		global $core;

		if ($this->logged_in == $if_logged_in)
		{
			$core->Redirect('/'.Config::$loginStartpage);
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