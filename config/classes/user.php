<?php
class User
{
	public $logged_in = false;
	public $username, $id, $rank = 0;

	private $data;
	function data($field)
	{
		return ((isset($this->data[$field]) && $this->logged_in) ? $this->data[$field] : '');
	}

	function __construct()
	{
		global $site, $DB;

		if (isset($_SESSION['user']))
		{
			$this->username = $_SESSION['user'];
			$this->logged_in = true;

			$this->data = $DB->FilterRow($DB->Select('username, id, password, mail', 'users', 'username', $this->username));
			if ($this->data === false)
			{
				$this->Logout();
			}

			$this->id = intval($this->data('id'));
			$this->rank = intval($this->data('rank'));
		}
		else if (isset($_COOKIE['l_username']) && isset($_COOKIE['l_hash']))
		{
			if ($this->Login($_COOKIE['l_username'], $_COOKIE['l_password'], false) === false)
			{
				$site->Redirect('/');
			}
		}
	}

	function Login($name, $pass, $set_cookies = false)
	{
		global $DB, $site;

		$DB->Filter($name);
		$DB->Filter($pass);

		$userInfo = $DB->Select('id, password', 'users', 'username', $name);

		if ($userInfo === false)
		{
			return '1';
		}
		if ($pass != $userInfo['password'])
		{
			return '2';
		}

		$this->username = $_SESSION['user'] = $name;
		$this->id = intval($userInfo['id']);

		if ($set_cookies)
		{
			$expire = time() + Config::$cookie_time;
			setcookie('l_username', $name, $expire, '/');
			setcookie('l_password', $pass, $expire, '/');
		}

		return false;
	}

	function checkName($name)
	{
		return (preg_match('/^[a-zA-Z0-9]+$/', $name) && strlen($name) >= 3 && strlen($name) <= 32);
	}

	function checkNameUse($name)
	{
		global $DB;
		return (!$DB->Exists('users', 'username', $DB->In($name)));
	}

	function checkMail($mail)
	{
		return (preg_match("/^[a-zA-Z0-9_\.-]+@([a-zA-Z0-9]+([\-]+[a-zA-Z0-9]+)*\.)+[a-z]{2,7}$/i", $mail) && strlen($mail) >= 3 && strlen($mail) <= 64);
	}

	function checkMailUse($mail)
	{
		global $DB;
		return (!$DB->Exists('users', 'mail', $DB->In($mail)));
	}

	function addUser($name, $mail, $pass, $male = true)
	{
		global $DB;

		$DB->Insert('users',
			Array(
				'username' => $name,
				'password' => $pass,
 			)
		);
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

	function Logout()
	{
		global $site;

		session_destroy();
		$site->Redirect('/index');
	}
}
?>