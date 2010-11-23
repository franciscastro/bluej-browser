<?php

/**
 * This is the model class for table "CompileSession".
 *
 * The followings are the available columns in table 'CompileSession':
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
 * @property string $sessionId
 * @property string $projectPath
 * @property string $packagePath
 * @property string $deltaName
 * 
 * This stores information related to a compilation session. It also
 * handles the import and export logic for compilation sessions. Also,
 * it holds many instances of CompileSessionEntry.
 */
class CompileSession extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CompileSession the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'CompileSession';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('deltaVersion, extensionVersion, systemUser, home, osName, osVersion, osArch, ipAddress, hostName, locationId, projectId, sessionId, projectPath, packagePath, deltaName', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, deltaVersion, extensionVersion, systemUser, home, osName, osVersion, osArch, ipAddress, hostName, locationId, projectId, sessionId, projectPath, packagePath, deltaName', 'safe', 'on'=>'search'),
		);
	}
  
  public function behaviors()
  {
    return array(
      'class'=>'application.components.CalculateEq',
    );
  }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'session' => array(self::BELONGS_TO, 'Session', 'id'),
			'entries' => array(self::HAS_MANY, 'CompileSessionEntry', 'compileSessionId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
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
			'sessionId' => 'Session',
			'projectPath' => 'Project Path',
			'packagePath' => 'Package Path',
			'deltaName' => 'Delta Name',
		);
	}
	
  /**
   * A conversion table between parameter names and table columns in sqlite files.
   * @return array the conversion table
   */
	private function externalLabels()
	{
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
			'sessionId' => 'SESSION_ID',
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
			'compilesPerFile' => 'COMPILES_PER_FILE',
			'totalCompiles' => 'TOTAL_COMPILES',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
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

		$criteria->compare('sessionId',$this->sessionId,true);

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
   * An event raised after importing
   */
  public function onAfterImport($event) {
    $this->raiseEvent('onAfterImport', $event);
  }
  
  protected function afterImport() {
    if($this->hasEventHandler('onAfterImport')) {
      $this->onAfterImport(new CEvent($this));
    }
  }
  
  /**
   * Run before deleting. Cascades deletions.
   */
  protected function beforeDelete() {
    foreach($this->entries as $entry) {
      $entry->delete();
    }
    return parent::beforeDelete();
  }
	
  /**
   * Creates a new session
   * @param integer id of the session
   * @param array session information from a row
   * @return InvocationSession the new session
   */
	private function createSession($sessionId, $row) {
		$session = new CompileSession;
		$session->id = $sessionId;
		$session->deltaVersion = $row['DELTA_VERSION'];
		$session->extensionVersion = $row['BJ_EXT_VERSION'];
		$session->systemUser = $row['SYSUSER'];
		$session->home = $row['HOME'];
		$session->osName = $row['OSNAME'];
		$session->osVersion = $row['OSVER'];
		$session->osArch = $row['OSARCH'];
		$session->ipAddress = $row['IPADDR'];
		$session->hostName = $row['HOSTNAME'];
		$session->locationId = $row['LOCATION_ID'];
		$session->projectId = $row['PROJECT_ID'];
		$session->sessionId = $row['SESSION_ID'];
		$session->projectPath = $row['PROJECT_PATH'];
		$session->packagePath = $row['PACKAGE_PATH'];
		$session->deltaName = $row['DELTA_NAME'];
		$session->save();
		
		return $session;
	}
	
  /**
   * Inserts a row into the session
   * @param array the row to be inserted
   */
	private function insertSessionEntry($row) {
		$newData = new CompileSessionEntry;
		$newData->compileSessionId = $this->id;
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
		$newData->compilesPerFile = $row['COMPILES_PER_FILE'];
		$newData->totalCompiles = $row['TOTAL_COMPILES'];
		$newData->save();
	}
	
  /**
   * Creates a new session and imports data into it
   * @param integer id of the session
   * @param array row containing session information
   * @param CDbReader data source for the row data 
   */
	public function doImport($sessionId, $row, $reader) {
		$session = $this->createSession($sessionId, $row);
		foreach($reader as $row) {
      $session->insertSessionEntry($row);
    }
    $session->afterImport();
	}
	
  /**
   * Creates a new session if it does not already exist, and adds a
   * row to it. Used for live importing.
   * @param integer id of the session
   * @param array the row to be added
   */
	public function liveImport($sessionId, $row) {
		$session = $this->findByPk($sessionId);
		if($session == null) {
			$session = $this->createSession($sessionId, $row);
		}
		$session->insertSessionEntry($row);	
    $session->afterImport();
	}
	
  /**
   * Generates a CSV file containing the data for this session.
   * @param file the file pointer to write to
   */
	public function doExport($fp) {
		$extToInt = array_flip($this->externalLabels());
		$extHeaders = array(
			'id',
			'revision',
			'TIMESTAMP',
			'DELTA_VERSION',
			'BJ_EXT_VERSION',
			'SYSUSER',
			'HOME',
			'OSNAME',
			'OSVER',
			'OSARCH',
			'IPADDR',
			'HOSTNAME',
			'LOCATION_ID',
			'PROJECT_ID',
			'SESSION_ID',
			'PROJECT_PATH',
			'PACKAGE_PATH',
			'DELTA_NAME',
			'DELTA_SEQ_NUMBER',
			'DELTA_START_TIME',
			'DELTA_END_TIME',
			'FILE_PATH',
			'FILE_NAME',
			'FILE_CONTENTS',
			'FILE_ENCODING',
			'COMPILE_SUCCESSFUL',
			'MSG_TYPE',
			'MSG_MESSAGE',
			'MSG_LINE_NUMBER',
			'COMPILES_PER_FILE',
			'TOTAL_COMPILES'
		);
		fputcsv($fp, $extHeaders);
		$counter = 1;
		foreach($this->entries as $entryModel) {
			$toWrite = array();
			foreach($extHeaders as $extHeader) {
				if(array_key_exists($extHeader, $extToInt)) {
					$intLabel = $extToInt[$extHeader];
					if(array_key_exists($intLabel, $entryModel->attributes)) {
						$toWrite[] = $entryModel->attributes[$intLabel];
					}
					else if (array_key_exists($intLabel, $this->attributes)) {
						$toWrite[] = $this->attributes[$intLabel];
					}
					else {
						$toWrite[] = '';
					}
				}
				else if($extHeader == 'id') {
					$toWrite[] = $counter++;
				}
				else if($extHeader == 'revision') {
					$toWrite[] = 0;
				}
				else {
					$toWrite[] = '';
				}
			}
			fputcsv($fp, $toWrite);
		}
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
