<?php

/**
 * This is the model class for table "Import".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'Import':
 * @property integer $id
 * @property integer $importSessionId
 * @property string $path
 * @property integer $userId
 * @property string $date
 * @property string $type
 *
 * Links a session to an import session. Also stores import information
 * for delayed importing.
 */
class Import extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @return Import the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'Import';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('importSessionId, userId', 'numerical', 'integerOnly'=>true),
			array('path', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, importSessionId, userId, path', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'importSession' => array(self::BELONGS_TO, 'ImportSession', 'importSessionId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'compileSession' => array(self::HAS_ONE, 'CompileSession', 'id'),
			'invocationSession' => array(self::HAS_ONE, 'InvocationSession', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'importSessionId' => 'Import Session',
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

		$criteria->compare('importSessionId',$this->importSessionId);

		$criteria->compare('userId',$this->userId);

		$criteria->compare('date',$this->date);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Run before deleting a session, cascades the deletions.
	 */
	protected function beforeDelete() {
		if($this->compileSession != null) $this->compileSession->delete();
		if($this->invocationSession != null) $this->invocationSession->delete();
		return parent::beforeDelete();
	}
}
