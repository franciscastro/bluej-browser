<?php

/**
 * This is the model class for table "Session".
 *
 * The followings are the available columns in table 'Session':
 * @property integer $id
 * @property integer $userId
 * @property string $date
 * @property string $type
 * 
 * A generic session. Delegates actions to it's "subclasses",
 * CompileSession and InvocationSession (so far).
 */
class Session extends CActiveRecord
{
	public $newTerms = array();
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Session the static model class
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
		return 'Session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId', 'numerical', 'integerOnly'=>true),
			array('date, type', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, date, type', 'safe', 'on'=>'search'),
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
			'compileSession' => array(self::HAS_ONE, 'CompileSession', 'id'),
			'import' => array(self::HAS_ONE, 'Import', 'sessionId'),
			'invocationSession' => array(self::HAS_ONE, 'InvocationSession', 'id'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'terms' => array(self::MANY_MANY, 'Term', 'SessionTerm(sessionId, termId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'date' => 'Date',
			'type' => 'Type',
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

		$criteria->compare('userId',$this->userId);

		$criteria->compare('date',$this->date,true);

		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Run after saving a record. Updates the terms of the session.
	 */
	protected function afterSave() {
		parent::afterSave();
		$oldTerms = $this->terms;
		$this->addTerms(array_udiff($this->newTerms, $oldTerms, array('Term', 'compare')));
		$this->removeTerms(array_udiff($oldTerms, $this->newTerms, array('Term', 'compare')));
	}
	
	/**
	 * Run before deleting a session, cascades the deletions.
	 */
	protected function beforeDelete() {
		$model = CActiveRecord::model($this->type)->findByPk($this->id)->delete();
		return parent::beforeDelete();
	}
	
	/**
	 * Adds terms to the session.
	 * @param array list of Terms to be added
	 */
	public function addTerms($terms) {
		foreach($terms as $term) {
			$relation = new SessionTerm;
			$relation->sessionId = $this->id;
			$relation->termId = $term->id;
			$relation->save();
		}
	}
	
	/**
	 * Removes terms from a session.
	 * @param array list of Terms to be removed
	 */
	public function removeTerms($terms) {
		foreach($terms as $term) {
			SessionTerm::model()->deleteAllByAttributes(array(
				'sessionId'=>$this->id,
				'termId'=>$term->id,
			));
		}
	}
}
