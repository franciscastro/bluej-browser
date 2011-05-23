<?php

/**
 * A mixin that causes logs to automatically have their error assigned.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class AssignErrorClass extends CActiveRecordBehavior {
	public function attach($owner) {
		parent::attach($owner);
		$this->owner->metaData->addRelation('errorClass', array(CActiveRecord::HAS_ONE, 'ErrorClass', 'compileLogEntryId'));
	}

	public function events() {
		return array_merge(parent::events(), array(
		'onAfterSave'=>'afterSave',
		));
	}

	public function afterSave($event) {
		if($this->owner->messageText == '') return;
		if(ErrorClass::model()->exists('compileLogEntryId = :id', array(':id' => $this->owner->id))) return;
		$errorClass = new ErrorClass;
		$errorClass->compileLogEntryId = $this->owner->id;
		$errorClass->assignClass($this->owner->messageText);
		$errorClass->save();
	}

	public function afterDelete($event) {
		if($this->owner->errorClass != null) $this->owner->errorClass->delete();
	}
}
