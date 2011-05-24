<?php

/**
 * The mixin for ErrorClass to attach to CompileLogEntry
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * This attaches to CompileLogEntry so the error class is
 * automatically assigned on each save. It also adds a relation
 * so the ErrorClass model can be accessed as $entry->errorClass.
 */
class AssignErrorClass extends CActiveRecordBehavior {

	/**
	 * Overrides CBehavior::attach to add the errorClass
	 * relation.
	 */
	public function attach($owner) {
		parent::attach($owner);
		$this->owner->metaData->addRelation('errorClass', array(CActiveRecord::HAS_ONE, 'ErrorClass', 'compileLogEntryId'));
	}

	/**
	 * This causes the errorClass to be assigned after
	 * saving the entry.
	 */
	public function afterSave($event) {
		if($this->owner->messageText == '') return;
		if(ErrorClass::model()->exists('compileLogEntryId = :id', array(':id' => $this->owner->id))) return;
		$errorClass = new ErrorClass;
		$errorClass->compileLogEntryId = $this->owner->id;
		$errorClass->assignClass($this->owner->messageText);
		$errorClass->save();
	}

	/**
	 * Cascades the deletion of the errorClass after
	 * deleting the entry.
	 */
	public function afterDelete($event) {
		if($this->owner->errorClass != null) $this->owner->errorClass->delete();
	}
}
