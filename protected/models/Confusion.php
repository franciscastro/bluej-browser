<?php

/**
 * This is the model class for table "Confusion".
 *
 * The followings are the available columns in table 'Confusion':
 * @property integer $id
 * @property integer $compileSessionId
 * @property integer $confusion
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
			array('compileSessionId, confusion', 'numerical' ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, compileSessionId, confusion', 'safe', 'on'=>'search'),
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
			'confusion' => 'Is Confused',
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
		$criteria->compare('confusion',$this->confusion);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function calculate() {
		$criteria = new CDbCriteria;
		$criteria->select = 'fileName';
		$criteria->group = 'fileName';
		$criteria->condition = 'compileSessionId=:id';
		$command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria);
		$fileNames = $command->queryColumn(array('id'=>$this->compileSessionId));

		$lastId = 0;
		$totalClips = 0.0;
		$labeledConfused = 0.0;

		foreach($fileNames as $fileName) {
			$hasMore = true;
			while($hasMore)
			{
				$entries = CompileSessionEntry::model()->findAll('compileSessionId=:id AND id>:lastId ORDER BY deltaSequenceNumber LIMIT 8', array('id'=>$this->compileSessionId, 'lastId'=>$lastId));
				$count = count($entries);
				if($count < 8) {
				// bad clip
					$hasMore = false;
				}
				else {
					$totalClips++;
					$compTime = array();
					$compTimeError = array();
					$compileTime = 0; // first compile time
					$compileError = 0; // first error Compile time
					$errorCount = 0;

					foreach($entries as $compilation)
					{
						$currentCompTime = $compilation->timestamp;

						if( $compileTime != 0 )
						{
							$compTime[] = $currentCompTime - $compileTime;
						}

						if($compilation->messageType == "ERROR")
						{
							if($compileError != 0)
							{
								$compTimeError[] = $currentCompTime - $compileError;
							}
							$compError = $currentCompTime;
							$errorCount++;
						}
						else
						{
							$compError = 0;
						}

						$compileTime = $currentCompTime;
					}

					$features['maxComp'] = $this->getMaxValue($compTime);
					$features['maxErr'] = $this->getMaxValue($compTimeError);
					$features['aveComp'] = $this->getAverage($compTime);
					$features['aveErr'] = $this->getAverage($compTimeError);
					$features['errorCount'] = $errorCount;

					if( $this->studentconfusion( $features ) ){
						$labeledConfused++;
					}
					$lastId = $entries[$count-1]->id;
				}
			}
		}
		if($totalClips == 0) {
			$conf = 0;
		}
		else {
			$conf = $labeledConfused/$totalClips;
		}
		$this->confusion = $conf; // put confusion % heeeere
		$this->save();
	}

	public function studentconfusion( $features ){
		$maxComp = $features['maxComp']; // max time bet. compilations
		$aveComp = $features['aveComp']; // average time bet. compilations
		$maxCompErr = $features['maxErr']; // max time bet. compilations with errors
		$aveCompErr = $features['aveErr']; // average time bet. compilations with errors
		$numCompErr = $features['errorCount']; // number of compilations with errors

		$confused = true;

		if( $numCompErr <= 3.500 )
		{
			if( $maxCompErr <= 23.500 )
			{
				$confused = false;
			}
			else
			{
				if( $aveCompErr > 16.667 )
				{
					if( $maxCompErr > 88.500 )
					{
						$confused = false;
					}
					else
					{
						if( $aveCompErr <= 21.917 )
						{
							$confused = false;
						}
						else
						{
							if( $aveComp > 203.714 )
							{
								$confused = false;
							}
							else
							{
								if( $maxComp <= 329.500 )
								{
									$confused = false;
								}
							}
						}
					}
				}
			}
		}

		return $confused;
	}


	public function getAverage( $array )
	{
		$sum = 0;
		foreach( $array as $val )
		{
			$sum = $sum + $val;
		}
		$total = count($array);
		if($total == 0)
		{
			$total = 1;
		}
		$average = $sum/$total;
		return $average;
	}

	public function getMaxValue( $array )
	{
		if(count($array) == 0) {
			return 0;
		}
		return max($array);
	}

}