<?php

/**
 * WebUser represents the current user who is logged in. It provides
 * role based authorization for actions.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class WebUser extends CWebUser {

	/**
	 * Overriding checkAccess to use User role
	 */
	public function checkAccess($operation, $params=array (), $allowCaching=true) {
		$role = $this->getState('role');
		if($operation == '*') {
			return true;
		}
		else if($operation == $role) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Runs after login. Assigns the role to the user.
	 */
	protected function afterLogin($fromCookie) {
		$userModel = User::model()->findByPk($this->id);
		$this->setState('role', $userModel->role);
	}

	/**
	 * @return boolean whether the user belongs to one of the roles given
	 */
	public function hasRole(array $roles) {
		$role = $this->getState('role');
		return in_array($role, $roles);
	}

	/**
	 * @return User the model of the user
	 */
	public function getModel() {
		return User::model()->findByPk($this->id);
	}
}
