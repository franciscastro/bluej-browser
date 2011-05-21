<?php

/**
 * A mixin that causes logs to automatically have their confusion rate calculated.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CalculateConfusion extends CActiveRecordBehavior {

	public function attach($owner) {
		parent::attach($owner);
	}

	public function events() {
		return array_merge(parent::events(), array(
			'onAfterLog'=>'afterLog',
		));
	}

	public function afterLog($event) {
		$model = Confusion::model()->findByAttributes(array('logId'=>$this->owner->id));
		if($model == null) {
			$model = new Confusion;
			$model->logId = $this->owner->id;
			$model->save();
		}
		$model->calculate();
	}
}
