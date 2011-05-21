<?php

/**
 * This is the model class for table "InvocationLogEntry".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'InvocationLogEntry':
 * @property integer $id
 * @property integer $logId
 * @property integer $timestamp
 * @property integer $deltaSequenceNumber
 * @property integer $deltaStartTime
 * @property integer $deltaEndTime
 * @property string $package
 * @property string $className
 * @property string $objectName
 * @property string $methodName
 * @property string $parameterTypes
 * @property string $parameters
 * @property string $result
 * @property string $invocationStatus
 *
 * Stores information on one invocation in an invocation log.
 */
class InvocationLogEntry extends CActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @return InvocationLogEntry the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'InvocationLogEntry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('logId, timestamp, deltaSequenceNumber, deltaStartTime, deltaEndTime', 'numerical', 'integerOnly'=>true),
			array('package, className, objectName, methodName, parameterTypes, parameters, result, invocationStatus', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, logId, timestamp, deltaSequenceNumber, deltaStartTime, deltaEndTime, package, className, objectName, methodName, parameterTypes, parameters, result, invocationStatus', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'invocationLog' => array(self::BELONGS_TO, 'InvocationLog', 'logId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'logId' => 'Invocation Session',
			'timestamp' => 'Timestamp',
			'deltaSequenceNumber' => 'Delta Sequence Number',
			'deltaStartTime' => 'Delta Start Time',
			'deltaEndTime' => 'Delta End Time',
			'package' => 'Package',
			'className' => 'Class Name',
			'objectName' => 'Object Name',
			'methodName' => 'Method Name',
			'parameterTypes' => 'Parameter Types',
			'parameters' => 'Parameters',
			'result' => 'Result',
			'invocationStatus' => 'Invocation Status',
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

		$criteria->compare('logId',$this->logId);

		$criteria->compare('timestamp',$this->timestamp);

		$criteria->compare('deltaSequenceNumber',$this->deltaSequenceNumber);

		$criteria->compare('deltaStartTime',$this->deltaStartTime);

		$criteria->compare('deltaEndTime',$this->deltaEndTime);

		$criteria->compare('package',$this->package,true);

		$criteria->compare('className',$this->className,true);

		$criteria->compare('objectName',$this->objectName,true);

		$criteria->compare('methodName',$this->methodName,true);

		$criteria->compare('parameterTypes',$this->parameterTypes,true);

		$criteria->compare('parameters',$this->parameters,true);

		$criteria->compare('result',$this->result,true);

		$criteria->compare('invocationStatus',$this->invocationStatus,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
