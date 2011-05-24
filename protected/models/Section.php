<?php

/**
 * This is the model class for table "Section".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'Section':
 * @property integer $id
 * @property string  $name
 * @property integer $yearId
 * @property integer $courseId
 * @property integer $sectionId
 *
 * The followings are the available model relations:
 * @property Tag $section
 * @property Tag $course
 * @property Tag $year
 *
 * A section represents a year, course, and section tag grouped
 * together. It is primarily used for restricting which tags a
 * teacher can access.
 */
class Section extends CActiveRecord {
	public $newTeachers = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @return Section the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'Section';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('yearId, courseId, sectionId', 'required'),
			array('name', 'unique'),
			array('yearId, courseId, sectionId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, yearId, courseId, sectionId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'section' => array(self::BELONGS_TO, 'Tag', 'sectionId'),
			'course' => array(self::BELONGS_TO, 'Tag', 'courseId'),
			'year' => array(self::BELONGS_TO, 'Tag', 'yearId'),
			'teachers' => array(self::MANY_MANY, 'User', 'UserSection(sectionId, userId)', 'condition'=>'roleId='.User::ROLE_TEACHER),
			'students' => array(self::MANY_MANY, 'User', 'UserSection(sectionId, userId)', 'condition'=>'roleId='.User::ROLE_STUDENT),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'yearId' => 'Year',
			'courseId' => 'Course',
			'sectionId' => 'Section',
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
		$criteria->compare('yearId',$this->yearId);
		$criteria->compare('courseId',$this->courseId);
		$criteria->compare('sectionId',$this->sectionId);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
	}

	/**
	 * Run after saving a record. Updates the teachers of the section.
	 */
	protected function afterSave() {
		parent::afterSave();
		$oldTeachers = $this->teachers;
		$this->addUsers(array_udiff($this->newTeachers, $oldTeachers, array('User', 'compare')));
		$this->removeUsers(array_udiff($oldTeachers, $this->newTeachers, array('User', 'compare')));
	}

	/**
	 * Adds users to the section.
	 * @param array the list of Users to be added.
	 */
	public function addUsers($users) {
		$users = is_array($users) ? $users : array($users);
		foreach($users as $user) {
			$relation = new UserSection;
			$relation->sectionId = $this->id;
			$relation->userId = $user->id;
			$relation->save();
		}
	}

	/**
	 * Removes users from the section.
	 * @param array the list of Users to be removed.
	 */
	public function removeUsers($users) {
		$users = is_array($users) ? $users : array($users);
		foreach($users as $user) {
			UserSection::model()->deleteAllByAttributes(array(
				'sectionId'=>$this->id,
				'userId'=>$user->id,
			));
		}
	}

	/**
	 * Replaces Tag's getViewData
	 * @param array the list of previously selected tags
	 * @return view data to be used for Section's input form
	 */
	public function getViewData($tags=array()) {
		$viewData = Tag::model()->getViewData($tags);
		if(!array_key_exists(Tag::TERM_YEAR, $viewData) ||
			 !array_key_exists(Tag::TERM_COURSE, $viewData) ||
			 !array_key_exists(Tag::TERM_SECTION, $viewData)) {
			$viewData['section'] = '';
		}
		else {
			$section = $this->findByAttributes(array(
				'yearId'=>$viewData[Tag::TERM_YEAR],
				'courseId'=>$viewData[Tag::TERM_COURSE],
				'sectionId'=>$viewData[Tag::TERM_SECTION],
			));
			if($section == null) {
				$viewData['section'] = '';
			}
			else {
				$viewData['section'] = $section->id;
			}
		}
		return $viewData;
	}

	/**
	 * Replaces Tag's getNewTags
	 * @return the list of tags from the input form
	 */
	public function getNewTags() {
		$newTags = Tag::model()->getNewTags();
		$section = $this->findByPk($_POST['tag']['section']);
		if($section != null) {
			$newTags[] = $section->year;
			$newTags[] = $section->course;
			$newTags[] = $section->section;
		}
		return $newTags;
	}
}
