<?php

/**
 * This is the model class for table "ImportSession".
 *
 * The followings are the available columns in table 'ImportSession':
 * @property integer $id
 * @property string $source
 * @property string $path
 * @property string $start
 * @property string $end
 * @property string $remarks
 * 
 * Stores import information for a lab session.
 */
class ImportSession extends CActiveRecord
{
	public $newTerms = array();
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ImportSession the static model class
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
		return 'ImportSession';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('source, path, start, end, remarks', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, source, path, start, end, remarks', 'safe', 'on'=>'search'),
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
			'imports' => array(self::HAS_MANY, 'Import', 'importSessionId'),
			'terms' => array(
				self::MANY_MANY,
				'Term',
				'ImportSessionTerm(importSessionId, termId)',
				'order'=>'parentId, name',
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'source' => 'Source',
			'path' => 'Path',
			'start' => 'Start',
			'end' => 'End',
			'remarks' => 'Remarks',
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
		
		if(isset($_GET['tags'])) {
			$termNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
			$_GET['tags'] = implode(',', $termNames);
			$criteria->condition = 'id IN (' . Term::createSubSelect('ImportSession', $termNames) . ')';
		}

		$criteria->compare('id',$this->id);
		
		$criteria->compare('sectionId',$this->sectionId, true);

		$criteria->compare('source',$this->source,true);

		$criteria->compare('path',$this->path,true);

		$criteria->compare('start',$this->start,true);

		$criteria->compare('end',$this->end,true);

		$criteria->compare('remarks',$this->remarks,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Run after saving. Updates the terms of the importSession.
	 */
	protected function afterSave() {
		parent::afterSave();
		$oldTerms = $this->terms;
		$this->addTerms(array_udiff($this->newTerms, $oldTerms, array('Term', 'compare')));
		$this->removeTerms(array_udiff($oldTerms, $this->newTerms, array('Term', 'compare')));
	}
	
	/**
	 * Run when a live insert is received. Finds any active sessions that
	 * accept the record and adds it to that one.
	 * @param string username
	 * @param string type of the session
	 * @param array row of data to be added
	 * @return boolean whether there was an active session that accepted the insert
	 */
	public function liveInsert($userName, $sessionType, $data) {		
		$liveSessions = ImportSession::model()->findAll('start IS NOT NULL AND end IS NULL');
		
		foreach($liveSessions as $liveSession) {
			if($liveSession->path == null || stripos($userName, $liveSession->path) == 0) {
				$import = $liveSession->getAssociatedImport($userName, $sessionType, 'live');
				$model = CActiveRecord::model($import->session->type);
				$model->liveImport($import->sessionId, $data);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Gets the import associated with the specified username and session type.
	 * If it does not exist, it creates a new one, along with corresponding
	 * users and sessions as needed.
	 * @param string username
	 * @param string session type
	 * @param string where the import came from
	 * @return the associated import
	 */
	public function getAssociatedImport($userName, $sessionType, $path) {
		if(strtolower($sessionType) == 'compiledata') {
			$sessionType = 'CompileSession';
		}
		if(strtolower($sessionType) == 'invocationdata') {
			$sessionType = 'InvocationSession';
		}
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
						
		$importModel = Import::model()->with(array(
			'session'=>array(
				'condition'=>'type = :type AND userId=:id',
				'params'=>array(
					':type'=>$sessionType,
					':id'=>$userModel->id,
				),
			),
		))->findByAttributes(array('importSessionId'=>$this->id));
		
		if($importModel == null) {
			$sessionModel = new Session;
			$sessionModel->userId = $userModel->id;
			$sessionModel->date = time();
			$sessionModel->type = $sessionType;
			$sessionModel->newTerms = $this->terms;
			$sessionModel->save();
			
			$importModel = new Import;
			$importModel->sessionId = $sessionModel->id;
			$importModel->importSessionId = $this->id;
			$importModel->path = $path;
			$importModel->save();
		}
		
		return $importModel;
	}
	
	/**
	 * Adds terms to the session.
	 * @param array list of Terms to be added
	 */
	public function addTerms($terms) {
		foreach($terms as $term) {
			$relation = new ImportSessionTerm;
			$relation->importSessionId = $this->id;
			$relation->termId = $term->id;
			$relation->save();
		}
	}
	
	/**
	 * Removes terms from the session.
	 * @param array list of Terms to be removed
	 */
	public function removeTerms($terms) {
		foreach($terms as $term) {
			ImportSessionTerm::model()->deleteAllByAttributes(array(
				'importSessionId'=>$this->id,
				'termId'=>$term->id,
			));
		}
	}
	
	/**
	 * Checks whether a teacher has access to the session
	 * @param User the teacher
	 * @return whether the teacher is allowed
	 */
	public static function checkTeacherAccess($teacher) {
		$model = self::model()->findByPk($_REQUEST['id']);
		return UserSection::model()->exists(
				'userId = :userId AND sectionId = :sectionId',
				array(
					':userId' => $teacher->id,
					':sectionId' => $model->sectionId,
				)
		);
	}
}
