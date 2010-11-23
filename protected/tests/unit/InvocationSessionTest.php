<?php

class InvocationSessionTest extends CDbTestCase {
	
	public $fixtures = array(
		'compiles' => 'InvocationSession',
		'entries' => 'InvocationSessionEntry',
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
	
	public function testDoImport() {
		$connection = new CDbConnection('sqlite:assets/test-invocation.db');
    $connection->active = true;
		$command = $connection->createCommand('SELECT * FROM `F227_11_InvocationData`');
		$row = $command->queryRow();
		$reader = $command->query();
		InvocationSession::model()->doImport(2, $row, $reader);
		
		$session = InvocationSession::model()->findByPk(2);
		$this->assertTrue($session instanceof InvocationSession);
		$this->assertEquals($session->deltaVersion, $row['DELTA_VERSION']);
		$this->assertEquals($session->extensionVersion, $row['BJ_EXT_VERSION']);
		$this->assertEquals($session->systemUser, $row['SYSUSER']);
		$this->assertEquals($session->home, $row['HOME']);
		$this->assertEquals($session->osName, $row['OSNAME']);
		$this->assertEquals($session->osVersion, $row['OSVER']);
		$this->assertEquals($session->osArch, $row['OSARCH']);
		$this->assertEquals($session->ipAddress, $row['IPADDR']);
		$this->assertEquals($session->hostName, $row['HOSTNAME']);
		$this->assertEquals($session->locationId, $row['LOCATION_ID']);
		$this->assertEquals($session->projectId, $row['PROJECT_ID']);
		$this->assertEquals($session->sessionId, $row['SESSION_ID']);
		$this->assertEquals($session->projectPath, $row['PROJECT_PATH']);
		$this->assertEquals($session->packagePath, $row['PACKAGE_PATH']);
		$this->assertEquals($session->deltaName, $row['DELTA_NAME']);
		
		$reader = $command->query();
		for($i = 0; $i < count($session->entries); $i++) {
			$entry = $session->entries[$i];
			$row = $reader->read();
			$this->assertEquals($entry->invocationSessionId, $session->id);
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
	
	public function testLiveImport1() {
		$session = new InvocationSession;
		$session->id = 1;
		$session->save();
		
		$row = $this->testRow;
		InvocationSession::model()->liveImport(1, $row);
		
		$session = InvocationSession::model()->findByPk(1);
		$this->assertEquals(count($session->entries), 1);
		$entry = $session->entries[count($session->entries)-1];
		$this->assertEquals($entry->invocationSessionId, $session->id);
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
	
	public function testLiveImport2() {
		$row = $this->testRow;
		InvocationSession::model()->liveImport(3, $row);
		
		$session = InvocationSession::model()->findByPk(3);
		$this->assertTrue($session instanceof InvocationSession);
		$this->assertEquals($session->deltaVersion, $row['DELTA_VERSION']);
		$this->assertEquals($session->extensionVersion, $row['BJ_EXT_VERSION']);
		$this->assertEquals($session->systemUser, $row['SYSUSER']);
		$this->assertEquals($session->home, $row['HOME']);
		$this->assertEquals($session->osName, $row['OSNAME']);
		$this->assertEquals($session->osVersion, $row['OSVER']);
		$this->assertEquals($session->osArch, $row['OSARCH']);
		$this->assertEquals($session->ipAddress, $row['IPADDR']);
		$this->assertEquals($session->hostName, $row['HOSTNAME']);
		$this->assertEquals($session->locationId, $row['LOCATION_ID']);
		$this->assertEquals($session->projectId, $row['PROJECT_ID']);
		$this->assertEquals($session->sessionId, $row['SESSION_ID']);
		$this->assertEquals($session->projectPath, $row['PROJECT_PATH']);
		$this->assertEquals($session->packagePath, $row['PACKAGE_PATH']);
		$this->assertEquals($session->deltaName, $row['DELTA_NAME']);
		
		$session = InvocationSession::model()->findByPk(3);
		$this->assertTrue(count($session->entries) > 0);
		$entry = $session->entries[count($session->entries)-1];
		$this->assertEquals($entry->invocationSessionId, $session->id);
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
