<?php

/**
 * This is the model class for table "EqCalculation".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'EqCalculation':
 * @property integer $id
 * @property integer $logId
 * @property double $eq
 *
 * Stores the EQ of a compilation log.
 */
class EqCalculation extends CActiveRecord {
	/**
	 * Returns the static model of the specified AR class.
	 * @return EqCalculation the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'EqCalculation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('logId', 'numerical', 'integerOnly'=>true),
			array('eq', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, logId, eq', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'compileLog' => array(self::BELONGS_TO, 'CompileLog', 'logId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'logId' => 'Compile Session',
			'eq' => 'Eq',
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

		$criteria->compare('eq',$this->eq);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Calculates EQ of a compilation log.
	 */
	public function calculate() {
		$entries = CompileLogEntry::model()->findAll('logId=:id ORDER BY fileName, timestamp', array('id'=>$this->logId));
		$numRows = count($entries);
		if($numRows < 2) {
			$this->eq = -1;
			$this->save();
			return;
		}
		$prevEntry = $entries[0];
		$count = 0;
		$eq = 0;
		$eqBreakDown[0] = 0;
		$eqBreakDown[1] = 0;
		$eqBreakDown[2] = 0;
		$eqBreakDown[3] = 0;
		for($i = 1; $i < $numRows; $i++) {
			$nextEntry = $entries[$i];
			$nextEntrydiff = CompileLog::diff($nextEntry->fileContents, $prevEntry->fileContents);
			if($nextEntry->fileName == $prevEntry->fileName) {
				$count++;
				if($nextEntry->messageLineNumber != -1 && $prevEntry->messageLineNumber != -1) {
					$eq += 2;
					$eqBreakDown[0]++;;
					if($nextEntry->messageText == $prevEntry->messageText) {
						$eq += 3;
						$eqBreakDown[1]++;
					}
					if($nextEntry->messageLineNumber == $prevEntry->messageLineNumber) {
						$eq += 3;
						$eqBreakDown[2]++;
					}
					if(in_array($prevEntry->messageLineNumber - 1, array_keys($nextEntrydiff))) {
						$eq += 1;
						$eqBreakDown[3]++;
					}
				}
			}
			$prevEntry = $nextEntry;
		}
		if($count > 0) {
			$eq /= 9.0;
			$eq /= (float)$count;
			$this->eq = $eq;
		}
		else {
			$this->eq = -1;
		}

		$this->save();
	}
}
