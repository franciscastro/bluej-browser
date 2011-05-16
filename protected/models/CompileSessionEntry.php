<?php

/**
 * This is the model class for table "CompileSessionEntry".
 *
 * The followings are the available columns in table 'CompileSessionEntry':
 * @property integer $id
 * @property integer $compileSessionId
 * @property integer $timestamp
 * @property integer $deltaSequenceNumber
 * @property integer $deltaStartTime
 * @property integer $deltaEndTime
 * @property string $filePath
 * @property string $fileName
 * @property string $fileContents
 * @property string $fileEncoding
 * @property boolean $compileSuccessful
 * @property string $messageType
 * @property string $messageText
 * @property integer $messageLineNumber
 * @property integer $messageColumnNumber
 * @property integer $compilesPerFile
 * @property integer $totalCompiles
 *
 * Stores an individual compilation in a compilation session.
 */
class CompileSessionEntry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CompileSessionEntry the static model class
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
		return 'CompileSessionEntry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('compileSuccessful', 'boolean'),
			array('compileSessionId, timestamp, deltaSequenceNumber, deltaStartTime, deltaEndTime, messageLineNumber, messageColumnNumber, compilesPerFile, totalCompiles', 'numerical', 'integerOnly'=>true),
			array('filePath, fileName, fileContents, fileEncoding, messageType, messageText', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, compileSessionId, timestamp, deltaSequenceNumber, deltaStartTime, deltaEndTime, filePath, fileName, fileContents, fileEncoding, compileSuccessful, messageType, messageText, messageLineNumber, messageColumnNumber, compilesPerFile, totalCompiles', 'safe', 'on'=>'search'),
		);
	}

  public function behaviors()
  {
    return array(
      'class'=>'application.components.AssignErrorClass',
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
			'timestamp' => 'Timestamp',
			'deltaSequenceNumber' => 'Delta Sequence Number',
			'deltaStartTime' => 'Delta Start Time',
			'deltaEndTime' => 'Delta End Time',
			'filePath' => 'File Path',
			'fileName' => 'File Name',
			'fileContents' => 'File Contents',
			'fileEncoding' => 'File Encoding',
			'compileSuccessful' => 'Compile Successful',
			'messageType' => 'Message Type',
			'messageText' => 'Message Text',
			'messageLineNumber' => 'Message Line Number',
			'messageColumnNumber' => 'Message Column Number',
			'compilesPerFile' => 'Compiles Per File',
			'totalCompiles' => 'Total Compiles',
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

		$criteria->compare('timestamp',$this->timestamp);

		$criteria->compare('deltaSequenceNumber',$this->deltaSequenceNumber);

		$criteria->compare('deltaStartTime',$this->deltaStartTime);

		$criteria->compare('deltaEndTime',$this->deltaEndTime);

		$criteria->compare('filePath',$this->filePath,true);

		$criteria->compare('fileName',$this->fileName,true);

		$criteria->compare('fileContents',$this->fileContents,true);

		$criteria->compare('fileEncoding',$this->fileEncoding,true);

		$criteria->compare('compileSuccessful',$this->compileSuccessful);

		$criteria->compare('messageType',$this->messageType,true);

		$criteria->compare('messageText',$this->messageText,true);

		$criteria->compare('messageLineNumber',$this->messageLineNumber);

		$criteria->compare('messageColumnNumber',$this->messageColumnNumber);

		$criteria->compare('compilesPerFile',$this->compilesPerFile);

		$criteria->compare('totalCompiles',$this->totalCompiles);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
