<?php

/**
 * This is the model class for table "CompileLog".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'CompileLog':
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
 * This stores information related to a compilation log. It also
 * handles the log and export logic for compilation logs. Also,
 * it holds many instances of CompileLogEntry.
 */
class CompileLog extends AbstractLog {
	/**
	 * Returns the static model of the specified AR class.
	 * @return CompileLog the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'CompileLog';
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

	public function behaviors() {
		return array(
			'eq' => array('class'=>'application.components.CalculateEq'),
			'confusion' => array('class'=>'application.components.CalculateConfusion'),
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
			'entries' => array(self::HAS_MANY, 'CompileLogEntry', 'logId'),
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
			'filePath' => 'FILE_PATH',
			'fileName' => 'FILE_NAME',
			'fileContents' => 'FILE_CONTENTS',
			'fileEncoding' => 'FILE_ENCODING',
			'compileSuccessful' => 'COMPILE_SUCCESSFUL',
			'messageType' => 'MSG_TYPE',
			'messageText' => 'MSG_MESSAGE',
			'messageLineNumber' => 'MSG_LINE_NUMBER',
			'messageColumnNumber' => 'MSG_COLUMN_NUMBER',
			'compilesPerFile' => 'COMPILES_PER_FILE',
			'totalCompiles' => 'TOTAL_COMPILES',
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
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
	}

	/**
	 * Creates a new log
	 * @param integer id of the log
	 * @param array log information from a row
	 * @return InvocationLog the new log
	 */
	protected function createSession($logId, $row) {
		$log = new CompileLog;
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
		$newData = new CompileLogEntry;
		$newData->logId = $this->id;
		$newData->timestamp = isset($row['TIMESTAMP']) ? $row['TIMESTAMP'] : time();
		$newData->deltaSequenceNumber = $row['DELTA_SEQ_NUMBER'];
		$newData->deltaStartTime = $row['DELTA_START_TIME'];
		$newData->deltaEndTime = $row['DELTA_END_TIME'];
		$newData->filePath = $row['FILE_PATH'];
		$newData->fileName = $row['FILE_NAME'];
		$newData->fileContents = $row['FILE_CONTENTS'];
		$newData->fileEncoding = $row['FILE_ENCODING'];
		$newData->compileSuccessful = $row['COMPILE_SUCCESSFUL'];
		$newData->messageType = $row['MSG_TYPE'];
		$newData->messageText = $row['MSG_MESSAGE'];
		$newData->messageLineNumber = $row['MSG_LINE_NUMBER'];
		$newData->messageColumnNumber = isset($row['MSG_COLUMN_NUMBER']) ? $row['MSG_COLUMN_NUMBER'] : -1;
		$newData->compilesPerFile = $row['COMPILES_PER_FILE'];
		$newData->totalCompiles = $row['TOTAL_COMPILES'];
		$newData->save();
	}

	protected function getEvent($entry) {
		return array(
			'title' => $entry->fileName.' - '.(($entry->messageText == '') ? 'no error' : $entry->messageText.':'.$entry->messageLineNumber),
			'start' => date('D, d M Y H:i:s O', $entry->timestamp),
			'description' => CHtml::link('View Source', array('compileLog/source', 'id'=>$entry->id)),
			'icon' => Yii::app()->baseURL . ($entry->messageText == '' ? '/images/accept_green.png' : '/images/cancel_round.png'),
		);
	}

	/**
	 * Gets the difference between 2 sources
	 * @return array the lines which are different
	 */
	public static function diff($sourceA, $sourceB) {
		$arrayA = explode("\n", $sourceA);
		$numLinesA = count($arrayA);
		$arrayB = explode("\n", $sourceB);
		$numLinesB = count($arrayB);
		$longer = $arrayA;
		$shorter = $arrayB;
		if($numLinesB > $numLinesA) {
			$longer = $arrayB;
			$shorter = $arrayA;
		}
		$diff = array_diff_assoc($longer, $shorter);
		return $diff;
	}
}
