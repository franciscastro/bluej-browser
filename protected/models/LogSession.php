<?php

/**
 * This is the model class for table "LogSession".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'LogSession':
 * @property integer $id
 * @property string $source
 * @property string $path
 * @property string $start
 * @property string $end
 * @property string $remarks
 *
 * Stores log information for a lab log.
 */
class LogSession extends CActiveRecord {
	public $newTags = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @return LogSession the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'LogSession';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
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
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'logs' => array(self::HAS_MANY, 'Log', 'logSessionId'),
			'tags' => array(
				self::MANY_MANY,
				'Tag',
				'LogSessionTag(logSessionId, tagId)',
				'order'=>'parentId, name',
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
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
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		if(isset($_GET['tags']) && !empty($_GET['tags'])) {
			$tagNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
			$_GET['tags'] = implode(',', $tagNames);
			$criteria->condition = 'id IN (' . Tag::createSubSelect('LogSession', $tagNames) . ')';
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
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
	}

	/**
	 * Run after saving. Updates the tags of the logSession.
	 */
	protected function afterSave() {
		parent::afterSave();
		$oldTags = $this->tags;
		$this->addTags(array_udiff($this->newTags, $oldTags, array('Tag', 'compare')));
		$this->removeTags(array_udiff($oldTags, $this->newTags, array('Tag', 'compare')));
	}

	/**
	 * Run before deleting a log, cascades the deletions.
	 */
	protected function beforeDelete() {
		LogSessionTag::model()->deleteAllByAttributes(array('logSessionId'=>$this->id));
		foreach($this->logs as $log) {
			$log->delete();
		}
		return parent::beforeDelete();
	}

	public function fileLog($file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if($extension == 'csv') {
			$fileName = basename($file);
			$pc = strripos($fileName, '_');
			$logType = substr($fileName, $pc+1);
			$computer = substr($fileName, 0, $pc);

			$reader = new CSVReader($file);
			$row = $reader->current();
			$reader->rewind();
		}
		else if($extension == 'sqlite') {
			$connection = new CDbConnection('sqlite:'.$file);
			$connection->active = true;

			$command = $connection->createCommand('SELECT * FROM sqlite_master WHERE type=\'table\'');
			$temp = $command->queryRow();
			$tableName = $temp['name'];
			$command = $connection->createCommand('SELECT * FROM `' . $tableName . '`');

			$pc = strripos($tableName, '_');
			$logType = substr($tableName, $pc+1);

			$computer = substr($tableName, 0, $pc);

			$row = $command->queryRow();
			$reader = $command->query();
		}
		$logType = LogSession::parseSessionType($logType);
		if($logType === false) {
			return;
		}
		$log = $this->getAssociatedLog($computer, $row['TIMESTAMP']);
		CActiveRecord::model($logType)->doLog($log->id, $row, $reader);
	}

	/**
	 * Run when a live insert is received. Finds any active logs that
	 * accept the record and adds it to that one.
	 * @param string username
	 * @param string type of the log
	 * @param array row of data to be added
	 * @return boolean whether there was an active log that accepted the insert
	 */
	public function liveInsert($computer, $logType, $data) {
		$logType = self::parseSessionType($logType);
		if($logType === false) {
			return false;
		}
		$liveSessions = LogSession::model()->findAll('start IS NOT NULL AND end IS NULL');

		foreach($liveSessions as $liveSession) {
			if($liveSession->path == null || stripos($computer, $liveSession->path) == 0) {
				$log = $liveSession->getAssociatedLog($computer);
				CActiveRecord::model($logType)->liveLog($log->id, $data);
				return true;
			}
		}
		return false;
	}

	/**
	 * Gets the log associated with the specified computer and log type.
	 * If it does not exist, it creates a new one, along with corresponding
	 * users and logs as needed.
	 * @param string computer
	 * @param string log type
	 * @param string where the log came from
	 * @return the associated log
	 */
	public function getAssociatedLog($computer, $time=0) {
		if($this->sectionId > 0) {
			$sectionModel = Section::model()->with(array(
				'students' => array(
					'condition' => 'computer="' . $computer .'"',
				),
			))->findByPk($this->sectionId);
			$userModel = ($sectionModel == null) ? null : $sectionModel->students[0];
		}
		else {
			$userModel = User::model()->findByAttributes(array('computer'=>$computer));
		}
		if($userModel == null) {
			$userModel = new User('log');
			$userModel->username = '';
			$userModel->password = '';
			$userModel->name = $computer;
			$userModel->computer = $computer;
			$userModel->roleId = User::ROLE_STUDENT;
			if(!$userModel->save()) {
				return null;
			}
			if($this->sectionId > 0) {
				$sectionModel = Section::model()->findByPk($this->sectionId);
				$sectionModel->addUsers($userModel);
			}
		}

		$logModel = Log::model()->findByAttributes(array(
			'logSessionId'=>$this->id,
			'userId'=>$userModel->id,
		));

		if($logModel == null) {
			$logModel = new Log;
			$logModel->logSessionId = $this->id;
			$logModel->userId = $userModel->id;
			$logModel->date = ($time == 0) ? time() : $time;
			$logModel->save();
		}

		return $logModel;
	}

	public static function parseSessionType($logType) {
		if(stripos($logType, 'compiledata') !== false) {
			return 'CompileLog';
		}
		else if(stripos($logType, 'invocationdata') !== false) {
			return 'InvocationLog';
		}
		return false;
	}

	/**
	 * Adds tags to the log.
	 * @param array list of Tags to be added
	 */
	public function addTags($tags) {
		foreach($tags as $tag) {
			$relation = new LogSessionTag;
			$relation->logSessionId = $this->id;
			$relation->tagId = $tag->id;
			$relation->save();
		}
	}

	/**
	 * Removes tags from the log.
	 * @param array list of Tags to be removed
	 */
	public function removeTags($tags) {
		foreach($tags as $tag) {
			LogSessionTag::model()->deleteAllByAttributes(array(
				'logSessionId'=>$this->id,
				'tagId'=>$tag->id,
			));
		}
	}

	/**
	 * Checks whether a teacher has access to the log
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
