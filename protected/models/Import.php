<?php

/**
 * This is the model class for table "Import".
 *
 * The followings are the available columns in table 'Import':
 * @property integer $id
 * @property integer $importSessionId
 * @property integer $sessionId
 * @property string $path
 * 
 * Links a session to an import session. Also stores import information
 * for delayed importing.
 */
class Import extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Import the static model class
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
		return 'Import';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('importSessionId, sessionId', 'numerical', 'integerOnly'=>true),
			array('path', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, importSessionId, sessionId, path', 'safe', 'on'=>'search'),
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
			'importSession' => array(self::BELONGS_TO, 'ImportSession', 'importSessionId'),
			'session' => array(self::BELONGS_TO, 'Session', 'sessionId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'importSessionId' => 'Import Session',
			'sessionId' => 'Session',
			'path' => 'Path',
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

		$criteria->compare('importSessionId',$this->importSessionId);

		$criteria->compare('sessionId',$this->sessionId);

		$criteria->compare('path',$this->path,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
  
  /**
   * Run before deleting. Cascades deletions.
   */
  protected function beforeDelete() {
    $this->session->delete();
    return parent::beforeDelete();
  }
	
  /**
   * Runs an import.
   */
	public function doImport() {
    if($this->sessionId != 0) return;
    $connection = new CDbConnection('sqlite:'.$this->path);
    $connection->active = true;
    
    $command = $connection->createCommand('SELECT * FROM sqlite_master WHERE type=\'table\'');
    $temp = $command->queryRow();
    
    $tableName = $temp['name'];
    
    $path = str_replace($this->importSession->path, '', $this->path);
    $path = explode(DIRECTORY_SEPARATOR, $path);
        
    $command = $connection->createCommand('SELECT * FROM `' . $tableName . '`');
    
    $pc = strripos($tableName, '_');
		$userName = substr($tableName, 0, $pc);
		$sessionType = substr($tableName, $pc+1); 

    $userModel = User::model()->findByAttributes(array('name'=>$userName));

		if($userModel == null) {
			$userModel = new User('import');
			$userModel->username = '---';
			$userModel->password = '---';
			$userModel->name = $userName;
			$userModel->roleId = 4;
		
			if(!$userModel->save()) {
				return null;
			}
		}
      
    $session = new Session;
    
    $basename = basename($this->path, '.sqlite');
    if(stripos($basename, 'compiledata')) {
			$session->type = 'CompileSession';
		}
		else if(stripos($basename, 'invocationdata')) {
			$session->type= 'InvocationSession';
		}
		else {
			return;
		}
    
    $row = $command->queryRow();
    $session->userId = $userModel->id;
    $session->date = $row['TIMESTAMP'];
    $session->newTerms = $this->importSession->terms;
    
    if($session->save()) {    
			$reader = $command->query();
			$model = CActiveRecord::model($session->type);
      $temp = $model->doImport($session->id, $row, $reader);
      $this->sessionId = $session->id;
      $this->save();
      
      /*
			$transaction = $model->dbConnection->beginTransaction();
      
			try {
				$temp = $model->doImport($session->id, $row, $reader);
				$this->sessionId = $session->id;
				$this->save();
				$transaction->commit();
			}
			catch (Exception $e) {
				$transaction->rollback();
				$session->delete();
			}*/
		}
  }
}
