<?php

/**
 * This is the model class for table "User".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'User':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $computer
 * @property integer $roleId
 *
 * A user.
 */
class User extends CActiveRecord {
	const ROLE_ADMIN = 1;
	const ROLE_RESEARCHER = 2;
	const ROLE_TEACHER = 3;
	const ROLE_STUDENT = 4;

	private $_oldPassword = '';
	private $_currentPassword = '';
	private $_passwordAgain = '';

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, computer, roleId', 'required'),
			array('password', 'required', 'on'=>array('insert')),
			array('username', 'unique', 'on'=>array('insert', 'update')),
			array('roleId', 'numerical', 'integerOnly'=>true, 'min'=>1, 'tooSmall'=>'Please select a role', 'on'=>array('insert', 'update')),
			array('username, password', 'length', 'max'=>128),

			array('password, currentPassword, passwordAgain', 'safe', 'on'=>array('changePassword')),
			array('password, currentPassword, passwordAgain', 'required', 'on'=>array('changePassword')),
			array('currentPassword', 'authenticate', 'on'=>array('changePassword')),
			array('password', 'compare', 'compareAttribute'=>'passwordAgain', 'on'=>array('changePassword')),
			array('name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, name, roleId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'sessions' => array(self::HAS_MANY, 'Import', 'userId'),
			'sections' => array(self::MANY_MANY, 'Section', 'UserSection(userId, sectionId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'name' => 'Name',
			'computer' => 'Computer',
			'roleId' => 'Role',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('username',$this->username,true);

		$criteria->compare('password',$this->password,true);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('computer',$this->computer,true);

		$criteria->compare('roleId',$this->roleId);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function getStatistics() {
		$statistics = array();
		$criteria = new CDbCriteria;
		$criteria->select = 'COUNT(*) AS count';
		$criteria->condition = 'userId = '.$this->id;
		$command = Yii::app()->db->getCommandBuilder()->createFindCommand('Import', $criteria);
		$statistics['sessionCount'] = $command->queryScalar();

		$criteria->join = 'JOIN Import ON Import.id = compileSessionId';
		$command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria);
		$statistics['compileCount'] = $command->queryScalar();

		$criteria->condition .= ' AND messageType="ERROR"';
		$command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria);
		$statistics['errorCount'] = $command->queryScalar();

		$criteria->select = 'AVG(eq)';
		$criteria->condition = 'userId = '.$this->id;
		$command = Yii::app()->db->getCommandBuilder()->createFindCommand('EqCalculation', $criteria);
		$statistics['eq'] = $command->queryScalar();

		$criteria->select = 'AVG(confusion)';
		$command = Yii::app()->db->getCommandBuilder()->createFindCommand('Confusion', $criteria);
		$statistics['confusion'] = $command->queryScalar();

		return $statistics;
	}

	/**
	 * @return string the role of the user
	 */
	public function getRole() {
		$roles = $this->roles();
		return $roles[$this->roleId+0];
	}

	/**
	 * @return array of possible roles a user may have
	 */
	public function roles() {
		return array(
			1=>'Administrator',
			'Researcher',
			'Teacher',
			'Student',
		);
	}

	/**
	 * Run before actually saving a record. Hashes the password if it was changed.
	 * @return boolean whether the record should be saved
	 */
	public function beforeSave() {
		if(isset($this->password)) {
			$this->password = $this->hashPassword($this->password);
		}
		return parent::beforeSave();
	}

	/**
	 * Run after every find/load operation. Stores the old password in case of changes.
	 */
	public function afterFind() {
		$this->_oldPassword = $this->password;
		return parent::afterFind();
	}

	/**
	 * Checks if the password is correct.
	 * @param string the password
	 * @return boolean whether or not the password was correct
	 */
	public function validatePassword($password) {
		return $this->hashPassword($password) === $this->_oldPassword;
	}

	/**
	 * @param string the unhashed password
	 * @return string the hashed password
	 */
	public function hashPassword($password) {
		return sha1($password);
	}

	/**
	 * Authenticate used by CWebUser.
	 */
	public function authenticate($attribute,$params) {
		if(!$this->validatePassword($this->currentPassword)) {
			$this->addError('currentPassword', 'The password is wrong');
		}
	}

	/**
	 * Gets all users with a certain role
	 * @param integer the role id
	 * @return array of User objects
	 */
	public function getUsers($userType) {
		return User::model()->findAllByAttributes(array('roleId' => $userType));
	}

	public static function compare($userA, $userB) {
		return $userA->id - $userB->id;
	}

	// Getters and setters
	public function getCurrentPassword() {
		return $this->_currentPassword;
	}

	public function setCurrentPassword($password) {
		$this->_currentPassword = $password;
	}

	public function getPasswordAgain() {
		return $this->_passwordAgain;
	}

	public function setPasswordAgain($password) {
		$this->_passwordAgain = $password;
	}
}
