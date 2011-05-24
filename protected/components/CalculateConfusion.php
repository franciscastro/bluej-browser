<?php

/**
 * The mixin for Confusion to attach to CompileLog
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * This attaches to CompileLogEntry so the confusion rate is
 * calculated after a log has been imported or after a new live
 * entry is inserted.
 *
 * It also adds a relation so the Confusion model can be accessed
 * via $log->confusion.
 */
class CalculateConfusion extends CActiveRecordBehavior {

	/**
	 * Overrides CBehavior::attach to add the confusion
	 * relation.
	 */
	public function attach($owner) {
		parent::attach($owner);
		$this->owner->metaData->addRelation('confusion', array(CActiveRecord::HAS_ONE, 'Confusion', 'logId'));
	}

	/**
	 * Overrides the CActiveRecordBehavior::events() to
	 * add the afterLog event.
	 */
	public function events() {
		return array_merge(parent::events(), array(
			'onAfterLog'=>'afterLog',
		));
	}

	/**
	 * This causes the confusion to be calculated after
	 * a log operation.
	 */
	public function afterLog($event) {
		$model = Confusion::model()->findByAttributes(array('logId'=>$this->owner->id));
		if($model == null) {
			$model = new Confusion;
			$model->logId = $this->owner->id;
			$model->save();
		}
		$model->calculate();
	}

	/**
	 * Cascades the deletion of the confusion model after
	 * deleting the entry.
	 */
	public function afterDelete($event) {
		if($this->owner->confusion != null) $this->owner->confusion->delete();
	}
}
