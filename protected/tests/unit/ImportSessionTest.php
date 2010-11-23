<?php

class ImportSessionTest extends CDbTestCase {
	
	public $fixtures = array(
		'importSessions' => 'ImportSession',
		'user' => 'User',
	);
	
	/**
	 * @dataProvider getAssociatedImportProvider
	 */
	public function testGetAssociatedImport($userName, $sessionType, $importExists=false) {
		if($importExists) {
			$userModel = User::model()->findByAttributes(array('name'=>$userName));
			$this->assertTrue($userModel instanceof User);
			
			$sessionModel = new Session;
			$sessionModel->userId = $userModel->id;
			$sessionModel->date = time();
			$sessionModel->type = $sessionType;
			$this->assertTrue($sessionModel->save());
			
			$importModel = new Import;
			$importModel->sessionId = $sessionModel->id;
			$importModel->importSessionId = 1;
			$importModel->path = '';
			$this->assertTrue($importModel->save());
		}
		
		$importModel = ImportSession::model()->getAssociatedImport($userName, $sessionType, '');
		$this->assertTrue($importModel instanceof Import);
		
		$this->assertEquals($importModel->session->user->name, $userName);
		
		if($sessionType == 'invocationdata') {
			$this->assertEquals($importModel->session->type, 'InvocationSession');		
		}
		else if($sessionType == 'compiledata') {
			$this->assertEquals($importModel->session->type, 'CompileSession');		
		}
	}
	
	public function getAssociatedImportProvider() {
		return array(
			array(
				'F227_10',
				'invocationdata',
			),
			array(
				'F227_11',
				'invocationdata',
			),
			array(
				'F227_11',
				'invocationdata',
				true,
			),
			array(
				'F227_10',
				'compiledata',
			),
			array(
				'F227_11',
				'compiledata',
			),
			array(
				'F227_11',
				'compiledata',
				true,
			),
		);
	}
	
	/**
	 * @depends testGetAssociatedImport
	 * @dataProvider liveInsertProvider
	 */
	public function testLiveInsert($userName, $sessionType, $data, $importSessionOk) {
		if($importSessionOk) {
			$importSessionModel = new ImportSession;
			$importSessionModel->source = 'live';
			$importSessionModel->start = 123123;
			$this->assertTrue($importSessionModel->save());
		}
		
		$insertOk = ImportSession::model()->liveInsert($userName, $sessionType, $data);
		$this->assertEquals($insertOk, $importSessionOk);		
	}
	
	public function liveInsertProvider() {
		$testRowInvocation = array(
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
		
		$testRowCompilation = array(
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
		return array(
			array(
				'F227_12',
				'invocationdata',
				$testRowInvocation,
				true,
			),
			array(
				'F227_12',
				'invocationdata',
				$testRowInvocation,
				false,
			),
			array(
				'F227_12',
				'compiledata',
				$testRowCompilation,
				true,
			),
			array(
				'F227_12',
				'compiledata',
				$testRowCompilation,
				false,
			),
		);
	}
}
