<?php

/**
 * This is the model class for table "Confusion".
 *
 * The followings are the available columns in table 'Confusion':
 * @property integer $id
 * @property integer $compileSessionId
 * @property integer $isConfused
 *
 * The followings are the available model relations:
 * @property CompileSession $compileSession
 */
class Confusion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Confusion the static model class
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
		return 'Confusion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('compileSessionId, isConfused', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, compileSessionId, isConfused', 'safe', 'on'=>'search'),
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
			'compileSession' => array(self::BELONGS_TO, 'CompileSession', 'compileSessionId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'compileSessionId' => 'Compile Session',
			'isConfused' => 'Is Confused',
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
		$criteria->compare('compileSessionId',$this->compileSessionId);
		$criteria->compare('isConfused',$this->isConfused);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function calculate() {
	
	}
}