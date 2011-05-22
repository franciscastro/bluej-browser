<?php

/**
 * This is the model class for table "Log".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'Log':
 * @property integer $id
 * @property integer $logSessionId
 * @property string $path
 * @property integer $userId
 * @property string $date
 * @property string $type
 *
 * Links a log to an log log. Also stores log information
 * for delayed loging.
 */
class Log extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'Log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('logSessionId, userId', 'numerical', 'integerOnly'=>true),
			array('path', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, logSessionId, userId, path', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'logSession' => array(self::BELONGS_TO, 'LogSession', 'logSessionId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'compileLog' => array(self::HAS_ONE, 'CompileLog', 'id'),
			'invocationLog' => array(self::HAS_ONE, 'InvocationLog', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'logSessionId' => 'Log Session',
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

		$criteria->compare('logSessionId',$this->logSessionId);

		$criteria->compare('userId',$this->userId);

		$criteria->compare('date',$this->date);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Run before deleting a log, cascades the deletions.
	 */
	protected function beforeDelete() {
		if($this->compileLog != null) $this->compileLog->delete();
		if($this->invocationLog != null) $this->invocationLog->delete();
		return parent::beforeDelete();
	}

	public function getTimeline() {
		$timeline = array();
		$timeline['events'] = array();
		if($this->compileLog != null) $timeline['events'] = array_merge($timeline['events'], $this->compileLog->getEvents());
		if($this->invocationLog != null) $timeline['events'] = array_merge($timeline['events'], $this->invocationLog->getEvents());
		return $timeline;
	}
}
