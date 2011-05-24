<?php

/**
 * This is the model class for table "Tag".
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * The followings are the available columns in table 'Tag':
 * @property integer $id
 * @property integer $parentId
 * @property string $name
 *
 * A tag. Zero or more tags can be assigned to an LogSession.
 * These can then be searched by one or more tags.
 */
class Tag extends CActiveRecord {
	const TERM_YEAR = 2;
	const TERM_COURSE = 3;
	const TERM_SECTION = 4;
	const TERM_LAB = 5;
	const TERM_OTHER = 6;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Tag the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'Tag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parentId, name', 'required'),
			array('parentId', 'numerical', 'integerOnly'=>true),
			array('name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parentId, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'logSessions' => array(self::HAS_MANY, 'LogSession', 'LogSessionTag(tagId, logSessionId)'),
			'parent' => array(self::BELONGS_TO, 'Tag', 'parentId'),
			'tags' => array(self::HAS_MANY, 'Tag', 'parentId'),
			'users' => array(self::HAS_MANY, 'User', 'UserTag(tagId, userId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'parentId' => 'Category',
			'name' => 'Name',
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

		$criteria->compare('parentId',$this->parentId);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('parentId','>1');

		$dataProvider = new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
		$sort = new CSort;
		$sort->attributes = array(
			'parent.name'=>array(
				'asc'=>'parent.name',
				'desc'=>'parent.name DESC',
				'label'=>'Category',
			),
			'*',
		);
		$dataProvider->sort = $sort;
		return $dataProvider;
	}

	/**
	 * Run before deleting a tag, cascades the deletions.
	 */
	protected function beforeDelete() {
		LogSessionTag::model()->deleteAllByAttributes(array('tagId'=>$this->id));
		UserTag::model()->deleteAllByAttributes(array('tagId'=>$this->id));
		return parent::beforeDelete();
	}

	/**
	 * Gets a tag with a certain id and certain type. If the tag's type
	 * does not match with the one specified, null is returned
	 * @param integer id of the tag
	 * @param integer id of the tag's type
	 * @return Tag the tag requested
	 */
	public function getTagById($tagId, $tagTypeId = Tag::TERM_OTHER) {
		$tag = Tag::model()->findByPk($tagId);
		if($tag != null && $tag->parentId == $tagTypeId) {
			return $tag;
		}
		return null;
	}

	/**
	 * Gets a tag with a certain name and certain type. If the tag does
	 * not exist, a new tag is created with the specified type. If the tag
	 * exists but is not of the specified type, null is returned
	 * @param integer id of the tag
	 * @param integer id of the tag's type
	 * @return Tag the tag requested
	 */
	public function getTagByName($tagName, $tagTypeId = Tag::TERM_OTHER) {
		$tag = Tag::model()->find('name LIKE :name', array(':name'=>$tagName));
		if($tag == null) {
			$tag = new Tag;
			$tag->name = $tagName;
			$tag->parentId = $tagTypeId;
			$tag->save();
		} else if($tag->parentId != $tagTypeId) {
			return null;
		}
		return $tag;
	}

	/**
	 * Gets a list of tags of a certain type.
	 * @param integer id of the tag's type
	 * @param integer the user requesting the tags
	 * @return array Tag
	 */
	public function getTags($parentId, $userId = 1) {
		if($userId == 1) {
			return $this->findAllByAttributes(array(
				'parentId' => $parentId,
			));
		}
		else {
			return $this->with(array(
				'users' => array(
					'condition' => 'id = :id',
					'params' => array(
						':id' => $userId,
					),
				),
			))->findAllByAttributes(array(
				'parentId' => $parentId,
			));
		}
	}

	/**
	 * Gets new tags generated by the tag input form.
	 * @return array Tag
	 */
	public function getNewTags() {
		$newTags = array();
		foreach($_POST['tag'] as $id => $datum) {
			if($id == Tag::TERM_YEAR || $id == Tag::TERM_COURSE || $id == Tag::TERM_SECTION) {
				$tag = $this->getTagById($datum, $id);
				if($tag != null) {
					$newTags[] = $tag;
				}
			}
			else if($id == Tag::TERM_LAB) {
				if(!empty($datum)) {
					$tag = $this->getTagByName($datum, Tag::TERM_LAB);
					if($tag != null) {
						$newTags[] = $tag;
					}
				}
			}
			else if($id == Tag::TERM_OTHER) {
				$otherTags = preg_split('/\s*,\s*/', $datum);
				$otherTags = array_unique($otherTags);
				foreach($otherTags as $otherTag) {
					$otherTag = trim($otherTag);
					if(!empty($otherTag)) {
						$tag = $this->getTagByName($otherTag);
						if($tag != null) {
							$newTags[] = $tag;
						}
					}
				}
			}
		}
		return $newTags;
	}

	/**
	 * Generates the view data needed by the tag input form to display
	 * previously selected items.
	 * @param array the tags previously chosen
	 * @return array view data
	 */
	public function getViewData($tags = array()) {
		$tagIds = array(
			Tag::TERM_YEAR,
			Tag::TERM_COURSE,
			Tag::TERM_SECTION,
		);
		$viewData = array();
		$viewData[Tag::TERM_LAB] = '';
		$viewData[Tag::TERM_OTHER] = '';
		foreach($tags as $tag) {
			if($tag->parentId == Tag::TERM_YEAR) {
				$viewData[Tag::TERM_YEAR] = $tag->id;
			}
			else if($tag->parentId == Tag::TERM_COURSE) {
				$viewData[Tag::TERM_COURSE] = $tag->id;
			}
			else if($tag->parentId == Tag::TERM_SECTION) {
				$viewData[Tag::TERM_SECTION] = $tag->id;
			}
			else if($tag->parentId == Tag::TERM_LAB) {
				$viewData[Tag::TERM_LAB] = $tag->name;
			}
			else if($tag->parentId == Tag::TERM_OTHER) {
				$viewData[Tag::TERM_OTHER] .= $tag->name . ', ';
			}
		}
		$viewData[Tag::TERM_OTHER] = substr($viewData[Tag::TERM_OTHER], 0, strlen($viewData[Tag::TERM_OTHER])-2);
		return $viewData;
	}

	public function getParentList() {
		return array(
			Tag::TERM_YEAR => 'Year',
			Tag::TERM_COURSE => 'Course',
			Tag::TERM_SECTION => 'Section',
			Tag::TERM_LAB => 'Lab',
			Tag::TERM_OTHER => 'Other',
		);
	}

	public function mergeWith($tag) {
		if($tag == null) return $this;
		if($tag->id == $this->id) return $this;
		Yii::app()->db->createCommand('DELETE a FROM UserTag a, UserTag b WHERE a.tagId='.$tag->id.' AND b.tagId='.$this->id.' AND a.userId=b.userId')->execute();
		Yii::app()->db->createCommand('DELETE a FROM LogSessionTag a, LogSessionTag b WHERE a.tagId='.$tag->id.' AND b.tagId='.$this->id.' AND a.logSessionId=b.logSessionId')->execute();
		Yii::app()->db->createCommand('UPDATE UserTag SET tagId='.$this->id.' WHERE tagId='.$tag->id)->execute();
		Yii::app()->db->createCommand('UPDATE LogSessionTag SET tagId='.$this->id.' WHERE tagId='.$tag->id)->execute();
		$tag->delete();
		return $this;
	}

	/**
	 * Creates a subselect when selecting via multiple tags.
	 * @param string the table where the original select will be done
	 * @param array list of the names of the tags used for selection
	 * @return string sql returning the ids from the table specified filtered by the tags
	 */
	public static function createSubSelect($table, $tagNames) {
		//$tableIdName = lcfirst($table).'Id';
		$tableIdName = strtolower(substr($table, 0, 1)) . substr($table, 1) . 'Id';
		$numTags = count($tagNames);
		$commandBuilder = Yii::app()->db->commandBuilder;
		$sql = 'SELECT '.$tableIdName.' FROM '.$table.'Tag';
		$sql = $commandBuilder->applyJoin($sql, 'JOIN Tag ON tagId = Tag.id');
		$inCondition = $commandBuilder->createInCondition('Tag', 'name', $tagNames);
		$sql = $commandBuilder->applyCondition($sql, $inCondition);
		$sql = $commandBuilder->applyGroup($sql, $tableIdName);
		$sql = $commandBuilder->applyHaving($sql, 'COUNT('.$tableIdName.') = '.$numTags);
		return $sql;
	}

	/**
	 * Generates HTML for displaying tags with links
	 * @param array list of Tags
	 * @return string the html
	 */
	public static function displayTags($tags) {
		$html = '';
		foreach($tags as $tag) {
			$html .= CHtml::link($tag->name, array('logSession/index', 'tags'=>$tag->name)) . ' ';
		}
		return $html;
	}

	public static function compare($tagA, $tagB) {
		return $tagA->id - $tagB->id;
	}
}
