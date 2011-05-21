<?php

class EqCalculationTest extends CDbTestCase {
	
	public $fixtures = array(
		'compiles' => ':CompileLog',
		'entries' => ':CompileLogEntry',
	);
	
	public function testCalculate() {
		$connection = new CDbConnection('sqlite:assets/test-compile.db');
		$connection->active = true;
		$command = $connection->createCommand('SELECT * FROM `F227_1_CompileData`');
		$row = $command->queryRow();
		$reader = $command->query();
		CompileLog::model()->doLog(2, $row, $reader);
		
		$logId = 2;
		$eqModel = new EqCalculation;
		$eqModel->logId = $logId;
		$eqModel->calculate();
		$this->assertTrue(abs($eqModel->eq - 0.57971014492754) < 0.00000000000001);
	}
	
}
