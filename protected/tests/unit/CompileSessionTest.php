<?php

class CompileSessionTest extends CDbTestCase {
	
	public $fixtures = array(
		'compiles' => 'CompileSession',
		'entries' => 'CompileSessionEntry',
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
		'FILE_PATH' => '10',
		'FILE_NAME' => '11',
		'FILE_CONTENTS' => '12',
		'FILE_ENCODING' => '13',
		'COMPILE_SUCCESSFUL' => 1,
		'MSG_TYPE' => '15',
		'MSG_MESSAGE' => '16',
		'MSG_LINE_NUMBER' => 17,
		'COMPILES_PER_FILE' => 18,
		'TOTAL_COMPILES' => 19,
	);
	
	public function testDoImport() {
		$connection = new CDbConnection('sqlite:assets/test-compile.db');
		$connection->active = true;
		$command = $connection->createCommand('SELECT * FROM `F227_1_CompileData`');
		$row = $command->queryRow();
		$reader = $command->query();
		CompileSession::model()->doImport(2, $row, $reader);
		
		$session = CompileSession::model()->findByPk(2);
		$this->assertTrue($session instanceof CompileSession);
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
			$this->assertEquals($entry->compileSessionId, $session->id);
			$this->assertEquals($entry->timestamp, $row['TIMESTAMP']);
			$this->assertEquals($entry->deltaSequenceNumber, $row['DELTA_SEQ_NUMBER']);
			$this->assertEquals($entry->deltaStartTime, $row['DELTA_START_TIME']);
			$this->assertEquals($entry->deltaEndTime, $row['DELTA_END_TIME']);
			$this->assertEquals($entry->filePath, $row['FILE_PATH']);
			$this->assertEquals($entry->fileName, $row['FILE_NAME']);
			$this->assertEquals($entry->fileContents, $row['FILE_CONTENTS']);
			$this->assertEquals($entry->fileEncoding, $row['FILE_ENCODING']);
			$this->assertEquals($entry->compileSuccessful, $row['COMPILE_SUCCESSFUL']);
			$this->assertEquals($entry->messageType, $row['MSG_TYPE']);
			$this->assertEquals($entry->messageText, $row['MSG_MESSAGE']);
			$this->assertEquals($entry->messageLineNumber, $row['MSG_LINE_NUMBER']);
			$this->assertEquals($entry->compilesPerFile, $row['COMPILES_PER_FILE']);
			$this->assertEquals($entry->totalCompiles, $row['TOTAL_COMPILES']);
		}
	}
	
	public function testLiveImport1() {
		$session = new CompileSession;
		$session->id = 1;
		$session->save();
		
		$row = $this->testRow;
		CompileSession::model()->liveImport(1, $row);
		
		$session = CompileSession::model()->findByPk(1);
		$this->assertEquals(count($session->entries), 1);
		$entry = $session->entries[count($session->entries)-1];
		$this->assertEquals($entry->compileSessionId, $session->id);
		$this->assertEquals($entry->timestamp, $row['TIMESTAMP']);
		$this->assertEquals($entry->deltaSequenceNumber, $row['DELTA_SEQ_NUMBER']);
		$this->assertEquals($entry->deltaStartTime, $row['DELTA_START_TIME']);
		$this->assertEquals($entry->deltaEndTime, $row['DELTA_END_TIME']);
		$this->assertEquals($entry->filePath, $row['FILE_PATH']);
		$this->assertEquals($entry->fileName, $row['FILE_NAME']);
		$this->assertEquals($entry->fileContents, $row['FILE_CONTENTS']);
		$this->assertEquals($entry->fileEncoding, $row['FILE_ENCODING']);
		$this->assertEquals($entry->compileSuccessful, $row['COMPILE_SUCCESSFUL']);
		$this->assertEquals($entry->messageType, $row['MSG_TYPE']);
		$this->assertEquals($entry->messageText, $row['MSG_MESSAGE']);
		$this->assertEquals($entry->messageLineNumber, $row['MSG_LINE_NUMBER']);
		$this->assertEquals($entry->compilesPerFile, $row['COMPILES_PER_FILE']);
		$this->assertEquals($entry->totalCompiles, $row['TOTAL_COMPILES']);
	}
	
	public function testLiveImport2() {
		$row = $this->testRow;
		CompileSession::model()->liveImport(3, $row);
		
		$session = CompileSession::model()->findByPk(3);
		$this->assertTrue($session instanceof CompileSession);
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
		
		$session = CompileSession::model()->findByPk(3);
		$this->assertTrue(count($session->entries) > 0);
		$entry = $session->entries[count($session->entries)-1];
		$this->assertEquals($entry->compileSessionId, $session->id);
		$this->assertEquals($entry->timestamp, $row['TIMESTAMP']);
		$this->assertEquals($entry->deltaSequenceNumber, $row['DELTA_SEQ_NUMBER']);
		$this->assertEquals($entry->deltaStartTime, $row['DELTA_START_TIME']);
		$this->assertEquals($entry->deltaEndTime, $row['DELTA_END_TIME']);
		$this->assertEquals($entry->filePath, $row['FILE_PATH']);
		$this->assertEquals($entry->fileName, $row['FILE_NAME']);
		$this->assertEquals($entry->fileContents, $row['FILE_CONTENTS']);
		$this->assertEquals($entry->fileEncoding, $row['FILE_ENCODING']);
		$this->assertEquals($entry->compileSuccessful, $row['COMPILE_SUCCESSFUL']);
		$this->assertEquals($entry->messageType, $row['MSG_TYPE']);
		$this->assertEquals($entry->messageText, $row['MSG_MESSAGE']);
		$this->assertEquals($entry->messageLineNumber, $row['MSG_LINE_NUMBER']);
		$this->assertEquals($entry->compilesPerFile, $row['COMPILES_PER_FILE']);
		$this->assertEquals($entry->totalCompiles, $row['TOTAL_COMPILES']);
	}
	
}
