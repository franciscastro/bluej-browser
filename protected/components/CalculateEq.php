<?php

/**
 * The mixin for EqCalculation to attach to CompileLog
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * This attaches to CompileLogEntry so the error quotient is
 * calculated after a log has been imported or after a new live
 * entry is inserted.
 *
 * It also adds a relation so the EqCalculation model can be accessed
 * via $log->eq.
 */
class CalculateEq extends CActiveRecordBehavior {

	/**
	 * Overrides CBehavior::attach to add the eq
	 * relation.
	 */
	public function attach($owner) {
		parent::attach($owner);
		$this->owner->metaData->addRelation('eq', array(CActiveRecord::HAS_ONE, 'EqCalculation', 'logId'));
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
	 * This causes the EQ to be calculated after
	 * a log operation.
	 */
	public function afterLog($event) {
		$eqCalculation = EqCalculation::model()->findByAttributes(array('logId'=>$this->owner->id));
		if($eqCalculation == null) {
			$eqCalculation = new EqCalculation;
			$eqCalculation->logId = $this->owner->id;
			$eqCalculation->save();
		}
		$eqCalculation->calculate();
	}

	/**
	 * Cascades the deletion of the EqCalculation model after
	 * deleting the entry.
	 */
	public function afterDelete($event) {
		if($this->owner->eq != null) $this->owner->eq->delete();
	}
}
