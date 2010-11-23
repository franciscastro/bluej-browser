<?php

class EqCalculationTest extends CDbTestCase {
  
	public $fixtures = array(
		'compiles' => ':CompileSession',
		'entries' => ':CompileSessionEntry',
	);
  
  public function testCalculate() {
    $connection = new CDbConnection('sqlite:assets/test-compile.db');
    $connection->active = true;
		$command = $connection->createCommand('SELECT * FROM `F227_1_CompileData`');
		$row = $command->queryRow();
		$reader = $command->query();
		CompileSession::model()->doImport(2, $row, $reader);
    
    $compileSessionId = 2;
    $eqModel = new EqCalculation;
    $eqModel->compileSessionId = $compileSessionId;
    $eqModel->calculate();
    $this->assertTrue(abs($eqModel->eq - 0.57971014492754) < 0.00000000000001);
  }
  
}
