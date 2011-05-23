<?php

/**
 * A mixin that causes logs to automatically have their eq calculated.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CalculateEq extends CActiveRecordBehavior {

	public function attach($owner) {
		parent::attach($owner);
		$this->owner->metaData->addRelation('eq', array(CActiveRecord::HAS_ONE, 'EqCalculation', 'logId'));
	}

	public function events() {
		return array_merge(parent::events(), array(
			'onAfterLog'=>'afterLog',
		));
	}

	public function afterLog($event) {
		$eqCalculation = EqCalculation::model()->findByAttributes(array('logId'=>$this->owner->id));
		if($eqCalculation == null) {
			$eqCalculation = new EqCalculation;
			$eqCalculation->logId = $this->owner->id;
			$eqCalculation->save();
		}
		$eqCalculation->calculate();
	}

	public function afterDelete($event) {
		if($this->owner->eq != null) $this->owner->eq->delete();
	}
}
