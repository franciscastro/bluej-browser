<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class UserIdentity extends CUserIdentity {
	private $_id;

	public function authenticate() {
		$userModel = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
		if($userModel == null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		else if(!$userModel->validatePassword($this->password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		else {
			$this->_id = $userModel->id;
			$this->username = $userModel->username;
			$this->errorCode = self::ERROR_NONE;
		}
		return $this->errorCode == self::ERROR_NONE;
	}

	public function getId() {
		return $this->_id;
	}

}
