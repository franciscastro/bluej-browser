<?php

class InvocationLogTest extends CDbTestCase {
	
	public $fixtures = array(
		'compiles' => 'InvocationLog',
		'entries' => 'InvocationLogEntry',
	);
	
	public $testRow = array(
		'TIMESTAMP' => 123213,
		'DELTA_VERSION' => '1',
		'BJ_EXT_VERSION' => '2.6',
		'SYSUSER' => 'user',
		'HOME' => 'userhome',
		'OSNAME' => 'Windows',
		'OSVER' => '7',
		'OSARCH' => 'x86',
		'IPADDR' => '127.0.0.1',
		'HOSTNAME' => 'localhost',
		'LOCATION_ID' => '1',
		'PROJECT_ID' => '2',
		'SESSION_ID' => '3',
		'PROJECT_PATH' => '4',
		'PACKAGE_PATH' => '5',
		'DELTA_NAME' => '6',
		'DELTA_SEQ_NUMBER' => 7,
		'DELTA_START_TIME' => 8,
		'DELTA_END_TIME' => 9,
		'PACKAGE' => '10',
		'CLASS_NAME' => '11',
		'OBJECT_NAME' => '12',
		'METHOD_NAME' => '13',
		'PARAMETER_TYPES' => '14',
		'PARAMETERS' => '15',
		'RESULT' => '16',
		'INVOCATION_STATUS' => '17',
	);
	
	public function testDoLog() {
		$connection = new CDbConnection('sqlite:assets/test-invocation.db');
		$connection->active = true;
		$command = $connection->createCommand('SELECT * FROM `F227_11_InvocationData`');
		$row = $command->queryRow();
		$reader = $command->query();
		InvocationLog::model()->doLog(2, $row, $reader);
		
		$log = InvocationLog::model()->findByPk(2);
		$this->assertTrue($log instanceof InvocationLog);
		$this->assertEquals($log->deltaVersion, $row['DELTA_VERSION']);
		$this->assertEquals($log->extensionVersion, $row['BJ_EXT_VERSION']);
		$this->assertEquals($log->systemUser, $row['SYSUSER']);
		$this->assertEquals($log->home, $row['HOME']);
		$this->assertEquals($log->osName, $row['OSNAME']);
		$this->assertEquals($log->osVersion, $row['OSVER']);
		$this->assertEquals($log->osArch, $row['OSARCH']);
		$this->assertEquals($log->ipAddress, $row['IPADDR']);
		$this->assertEquals($log->hostName, $row['HOSTNAME']);
		$this->assertEquals($log->locationId, $row['LOCATION_ID']);
		$this->assertEquals($log->projectId, $row['PROJECT_ID']);
		$this->assertEquals($log->logId, $row['SESSION_ID']);
		$this->assertEquals($log->projectPath, $row['PROJECT_PATH']);
		$this->assertEquals($log->packagePath, $row['PACKAGE_PATH']);
		$this->assertEquals($log->deltaName, $row['DELTA_NAME']);
		
		$reader = $command->query();
		for($i = 0; $i < count($log->entries); $i++) {
			$entry = $log->entries[$i];
			$row = $reader->read();
			$this->assertEquals($entry->logId, $log->id);
			$this->assertEquals($entry->timestamp, $row['TIMESTAMP']);
			$this->assertEquals($entry->deltaSequenceNumber, $row['DELTA_SEQ_NUMBER']);
			$this->assertEquals($entry->deltaStartTime, $row['DELTA_START_TIME']);
			$this->assertEquals($entry->deltaEndTime, $row['DELTA_END_TIME']);
			$this->assertEquals($entry->package, $row['PACKAGE']);
			$this->assertEquals($entry->className, $row['CLASS_NAME']);
			$this->assertEquals($entry->objectName, $row['OBJECT_NAME']);
			$this->assertEquals($entry->methodName, $row['METHOD_NAME']);
			$this->assertEquals($entry->parameterTypes, $row['PARAMETER_TYPES']);
			$this->assertEquals($entry->parameters, $row['PARAMETERS']);
			$this->assertEquals($entry->result, $row['RESULT']);
			$this->assertEquals($entry->invocationStatus, $row['INVOCATION_STATUS']);
		}
	}
	
	public function testLiveLog1() {
		$log = new InvocationLog;
		$log->id = 1;
		$log->save();
		
		$row = $this->testRow;
		InvocationLog::model()->liveLog(1, $row);
		
		$log = InvocationLog::model()->findByPk(1);
		$this->assertEquals(count($log->entries), 1);
		$entry = $log->entries[count($log->entries)-1];
		$this->assertEquals($entry->logId, $log->id);
		$this->assertEquals($entry->timestamp, $row['TIMESTAMP']);
		$this->assertEquals($entry->deltaSequenceNumber, $row['DELTA_SEQ_NUMBER']);
		$this->assertEquals($entry->deltaStartTime, $row['DELTA_START_TIME']);
		$this->assertEquals($entry->deltaEndTime, $row['DELTA_END_TIME']);
		$this->assertEquals($entry->package, $row['PACKAGE']);
		$this->assertEquals($entry->className, $row['CLASS_NAME']);
		$this->assertEquals($entry->objectName, $row['OBJECT_NAME']);
		$this->assertEquals($entry->methodName, $row['METHOD_NAME']);
		$this->assertEquals($entry->parameterTypes, $row['PARAMETER_TYPES']);
		$this->assertEquals($entry->parameters, $row['PARAMETERS']);
		$this->assertEquals($entry->result, $row['RESULT']);
		$this->assertEquals($entry->invocationStatus, $row['INVOCATION_STATUS']);
	}
	
	public function testLiveLog2() {
		$row = $this->testRow;
		InvocationLog::model()->liveLog(3, $row);
		
		$log = InvocationLog::model()->findByPk(3);
		$this->assertTrue($log instanceof InvocationLog);
		$this->assertEquals($log->deltaVersion, $row['DELTA_VERSION']);
		$this->assertEquals($log->extensionVersion, $row['BJ_EXT_VERSION']);
		$this->assertEquals($log->systemUser, $row['SYSUSER']);
		$this->assertEquals($log->home, $row['HOME']);
		$this->assertEquals($log->osName, $row['OSNAME']);
		$this->assertEquals($log->osVersion, $row['OSVER']);
		$this->assertEquals($log->osArch, $row['OSARCH']);
		$this->assertEquals($log->ipAddress, $row['IPADDR']);
		$this->assertEquals($log->hostName, $row['HOSTNAME']);
		$this->assertEquals($log->locationId, $row['LOCATION_ID']);
		$this->assertEquals($log->projectId, $row['PROJECT_ID']);
		$this->assertEquals($log->logId, $row['SESSION_ID']);
		$this->assertEquals($log->projectPath, $row['PROJECT_PATH']);
		$this->assertEquals($log->packagePath, $row['PACKAGE_PATH']);
		$this->assertEquals($log->deltaName, $row['DELTA_NAME']);
		
		$log = InvocationLog::model()->findByPk(3);
		$this->assertTrue(count($log->entries) > 0);
		$entry = $log->entries[count($log->entries)-1];
		$this->assertEquals($entry->logId, $log->id);
		$this->assertEquals($entry->timestamp, $row['TIMESTAMP']);
		$this->assertEquals($entry->deltaSequenceNumber, $row['DELTA_SEQ_NUMBER']);
		$this->assertEquals($entry->deltaStartTime, $row['DELTA_START_TIME']);
		$this->assertEquals($entry->deltaEndTime, $row['DELTA_END_TIME']);
		$this->assertEquals($entry->package, $row['PACKAGE']);
		$this->assertEquals($entry->className, $row['CLASS_NAME']);
		$this->assertEquals($entry->objectName, $row['OBJECT_NAME']);
		$this->assertEquals($entry->methodName, $row['METHOD_NAME']);
		$this->assertEquals($entry->parameterTypes, $row['PARAMETER_TYPES']);
		$this->assertEquals($entry->parameters, $row['PARAMETERS']);
		$this->assertEquals($entry->result, $row['RESULT']);
		$this->assertEquals($entry->invocationStatus, $row['INVOCATION_STATUS']);
	}
	
}
