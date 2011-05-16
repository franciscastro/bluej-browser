<?php

/**
 * A mixin that causes imports to automatically have their eq calculated.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CalculateEq extends CActiveRecordBehavior {

	public function attach($owner) {
		parent::attach($owner);
	}

	public function events() {
		return array_merge(parent::events(), array(
			'onAfterImport'=>'afterImport',
		));
	}

	public function afterImport($event) {
		$eqCalculation = EqCalculation::model()->findByAttributes(array('compileSessionId'=>$this->owner->id));
		if($eqCalculation == null) {
			$eqCalculation = new EqCalculation;
			$eqCalculation->compileSessionId = $this->owner->id;
			$eqCalculation->save();
		}
		$eqCalculation->calculate();
	}
}
