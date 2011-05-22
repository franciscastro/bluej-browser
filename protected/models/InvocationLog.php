<?php

/**
 * This is the model class for table "InvocationLog".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'InvocationLog':
 * @property integer $id
 * @property string $deltaVersion
 * @property string $extensionVersion
 * @property string $systemUser
 * @property string $home
 * @property string $osName
 * @property string $osVersion
 * @property string $osArch
 * @property string $ipAddress
 * @property string $hostName
 * @property string $locationId
 * @property string $projectId
 * @property string $logId
 * @property string $projectPath
 * @property string $packagePath
 * @property string $deltaName
 *
 * This stores information related to an invocation log. It also
 * handles the log and export logic for invocation logs. Also,
 * it holds many instances of InvocationLogEntry.
 */
class InvocationLog extends AbstractLog {
	/**
	 * Returns the static model of the specified AR class.
	 * @return InvocationLog the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'InvocationLog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('deltaVersion, extensionVersion, systemUser, home, osName, osVersion, osArch, ipAddress, hostName, locationId, projectId, logId, projectPath, packagePath, deltaName', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, deltaVersion, extensionVersion, systemUser, home, osName, osVersion, osArch, ipAddress, hostName, locationId, projectId, logId, projectPath, packagePath, deltaName', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'log' => array(self::BELONGS_TO, 'Log', 'id'),
			'entries' => array(self::HAS_MANY, 'InvocationLogEntry', 'logId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'deltaVersion' => 'Delta Version',
			'extensionVersion' => 'Extension Version',
			'systemUser' => 'System User',
			'home' => 'Home',
			'osName' => 'Os Name',
			'osVersion' => 'Os Version',
			'osArch' => 'Os Arch',
			'ipAddress' => 'Ip Address',
			'hostName' => 'Host Name',
			'locationId' => 'Location',
			'projectId' => 'Project',
			'logId' => 'Session',
			'projectPath' => 'Project Path',
			'packagePath' => 'Package Path',
			'deltaName' => 'Delta Name',
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

		$criteria->compare('deltaVersion',$this->deltaVersion,true);

		$criteria->compare('extensionVersion',$this->extensionVersion,true);

		$criteria->compare('systemUser',$this->systemUser,true);

		$criteria->compare('home',$this->home,true);

		$criteria->compare('osName',$this->osName,true);

		$criteria->compare('osVersion',$this->osVersion,true);

		$criteria->compare('osArch',$this->osArch,true);

		$criteria->compare('ipAddress',$this->ipAddress,true);

		$criteria->compare('hostName',$this->hostName,true);

		$criteria->compare('locationId',$this->locationId,true);

		$criteria->compare('projectId',$this->projectId,true);

		$criteria->compare('logId',$this->logId,true);

		$criteria->compare('projectPath',$this->projectPath,true);

		$criteria->compare('packagePath',$this->packagePath,true);

		$criteria->compare('deltaName',$this->deltaName,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * A conversion table between parameter names and table columns in sqlite files.
	 * @return array the conversion table
	 */
	protected function externalLabels() {
		return array(
			'id' => 'ID',
			'deltaVersion' => 'DELTA_VERSION',
			'extensionVersion' => 'BJ_EXT_VERSION',
			'systemUser' => 'SYSUSER',
			'home' => 'HOME',
			'osName' => 'OSNAME',
			'osVersion' => 'OSVER',
			'osArch' => 'OSARCH',
			'ipAddress' => 'IPADDR',
			'hostName' => 'HOSTNAME',
			'locationId' => 'LOCATION_ID',
			'projectId' => 'PROJECT_ID',
			'logId' => 'SESSION_ID',
			'projectPath' => 'PROJECT_PATH',
			'packagePath' => 'PACKAGE_PATH',
			'deltaName' => 'DELTA_NAME',
			'timestamp' => 'TIMESTAMP',
			'deltaSequenceNumber' => 'DELTA_SEQ_NUMBER',
			'deltaStartTime' => 'DELTA_START_TIME',
			'deltaEndTime' => 'DELTA_END_TIME',
			'package' => 'PACKAGE',
			'className' => 'CLASS_NAME',
			'objectName' => 'OBJECT_NAME',
			'methodName' => 'METHOD_NAME',
			'parameterTypes' => 'PARAMETER_TYPES',
			'parameters' => 'PARAMETERS',
			'result' => 'RESULT',
			'invocationStatus' => 'INVOCATION_STATUS',
		);
	}

	/**
	 * Run before deleting a record. Cascades deletions.
	 */
	protected function beforeDelete() {
		foreach($this->entries as $entry) {
			$entry->delete();
		}
		return parent::beforeDelete();
	}

	/**
	 * Creates a new log
	 * @param integer id of the log
	 * @param array log information from a row
	 * @return InvocationLog the new log
	 */
	protected function createSession($logId, $row) {
		$log = new InvocationLog;
		$log->id = $logId;
		$log->deltaVersion = $row['DELTA_VERSION'];
		$log->extensionVersion = $row['BJ_EXT_VERSION'];
		$log->systemUser = $row['SYSUSER'];
		$log->home = $row['HOME'];
		$log->osName = $row['OSNAME'];
		$log->osVersion = $row['OSVER'];
		$log->osArch = $row['OSARCH'];
		$log->ipAddress = $row['IPADDR'];
		$log->hostName = $row['HOSTNAME'];
		$log->locationId = $row['LOCATION_ID'];
		$log->projectId = $row['PROJECT_ID'];
		$log->logId = $row['SESSION_ID'];
		$log->projectPath = $row['PROJECT_PATH'];
		$log->packagePath = $row['PACKAGE_PATH'];
		$log->deltaName = $row['DELTA_NAME'];
		$log->save();

		return $log;
	}

	/**
	 * Inserts a row into the log
	 * @param array the row to be inserted
	 */
	protected function insertEntry($row) {
		$newData = new InvocationLogEntry;
		$newData->logId = $this->id;
		$newData->timestamp = isset($row['TIMESTAMP']) ? $row['TIMESTAMP'] : time();
		$newData->deltaSequenceNumber = $row['DELTA_SEQ_NUMBER'];
		$newData->deltaStartTime = $row['DELTA_START_TIME'];
		$newData->deltaEndTime = $row['DELTA_END_TIME'];
		$newData->package = $row['PACKAGE'];
		$newData->className = $row['CLASS_NAME'];
		$newData->objectName = $row['OBJECT_NAME'];
		$newData->methodName = $row['METHOD_NAME'];
		$newData->parameterTypes = $row['PARAMETER_TYPES'];
		$newData->parameters = $row['PARAMETERS'];
		$newData->result = $row['RESULT'];
		$newData->invocationStatus = $row['INVOCATION_STATUS'];
		$newData->save();
	}

	protected function getEvent($entry) {
		return array(
			'title' => $entry->className.'.'.$entry->methodName.'('.$entry->parameters.') -> '.(($entry->result == '') ? 'void' : $entry->result),
			'description' => '',
			'start' => date('D, d M Y H:i:s O', $entry->timestamp),
			'icon' => Yii::app()->baseURL . '/images/arrow_right_blue_round.png',
		);
	}
}
