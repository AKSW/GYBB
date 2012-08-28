<?php

class User {

	public $id;
	public $name;
	public $email;

	public $oauthProvider = 'facebook';
	public $oauthId = '';


	function __construct($values) {

		if (!is_null($values) && !empty($values)) {
			$this->id = $values['id'];
			$this->email = $values['email'];
			$this->name = $values['name'];
		}

	}

	function getHash() {
		return md5($this->id . '-'. $this->oauthId . '-' . $this->oauthProvider );
	}

	static function putUserInSession($user) {
		session_destroy();
		session_start();
		$_SESSION['_lgd_user'] = $user;
	}

	static function getCurrentUser() {
		if (array_key_exists('_lgd_user', $_SESSION)) {
			return $_SESSION['_lgd_user'];
		}
		return FALSE;
	}
}

?>
