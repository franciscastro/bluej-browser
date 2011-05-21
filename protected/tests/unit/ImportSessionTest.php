<?php

class LogSessionTest extends CDbTestCase {
	
	public $fixtures = array(
		'logSessions' => 'LogSession',
		'user' => 'User',
	);
	
	/**
	 * @dataProvider getAssociatedLogProvider
	 */
	public function testGetAssociatedLog($userName, $logType, $logExists=false) {
		if($logExists) {
			$userModel = User::model()->findByAttributes(array('name'=>$userName));
			$this->assertTrue($userModel instanceof User);
			
			$logModel = new Session;
			$logModel->userId = $userModel->id;
			$logModel->date = time();
			$logModel->type = $logType;
			$this->assertTrue($logModel->save());
			
			$logModel = new Log;
			$logModel->logId = $logModel->id;
			$logModel->logSessionId = 1;
			$logModel->path = '';
			$this->assertTrue($logModel->save());
		}
		
		$logModel = LogSession::model()->getAssociatedLog($userName, $logType, '');
		$this->assertTrue($logModel instanceof Log);
		
		$this->assertEquals($logModel->log->user->name, $userName);
		
		if($logType == 'invocationdata') {
			$this->assertEquals($logModel->log->type, 'InvocationLog');		
		}
		else if($logType == 'compiledata') {
			$this->assertEquals($logModel->log->type, 'CompileLog');		
		}
	}
	
	public function getAssociatedLogProvider() {
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
	 * @depends testGetAssociatedLog
	 * @dataProvider liveInsertProvider
	 */
	public function testLiveInsert($userName, $logType, $data, $logSessionOk) {
		if($logSessionOk) {
			$logSessionModel = new LogSession;
			$logSessionModel->source = 'live';
			$logSessionModel->start = 123123;
			$this->assertTrue($logSessionModel->save());
		}
		
		$insertOk = LogSession::model()->liveInsert($userName, $logType, $data);
		$this->assertEquals($insertOk, $logSessionOk);		
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
