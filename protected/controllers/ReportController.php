<?php

/**
 * Handles creation of reports.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class ReportController extends Controller {

	const EQ = 1;
	const ERROR = 2;
	const ERROR_CLASS = 3;
	const TIME_DELTA = 4;
	const CONFUSION = 5;
	const HISTOGRAM = 6;

	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules() {
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'roles'=>array('Administrator', 'Teacher', 'Researcher'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex() {
		if(isset($_GET['tags'])) {
			$this->redirect(array('summary', 'tags'=>$_GET['tags']), true);
		}
		$models = Tag::model()->findAll('parentId > 1');
		$this->render('index', array(
			'models' => $models,
		));
	}

	public function actionSummary() {
		$command = $this->getCommand(self::ERROR_CLASS, array('limit' => 10));
		$topErrorsData = array();
		foreach($command->queryAll() as $datum) {
			$data = array();
			$data[] = (int)$datum['count'];
			$data[] = (empty($datum['messageText'])) ? 'no error' : $datum['messageText'];
			$topErrorsData[] = $data;
		}

		$command = $this->getCommand(self::EQ, array('limit' => 10));
		$topEqData = array();
		foreach($command->queryAll() as $datum) {
			$data = array();
			$data[] = (float)$datum['eq'];
			$data[] = CHtml::link($datum['name'], isset($_GET['id']) ? array('compileLog/view', 'id'=>$datum['logId']) : array('user/view', 'id'=>$datum['userId']));
			$topEqData[] = $data;
		}

		$command = $this->getCommand(self::CONFUSION, array('limit' => 10));
		$topConfusedData = array();
		foreach($command->queryAll() as $datum) {
			$data = array();
			$data[] = (float)$datum['confusion'] ;
			$data[] = CHtml::link($datum['name'], isset($_GET['id']) ? array('compileLog/view', 'id'=>$datum['logId']) : array('user/view', 'id'=>$datum['userId'])) . sprintf(' %d clip(s)', $datum['clips']);
			$topConfusedData[] = $data;
		}

		$command = $this->getCommand(self::TIME_DELTA, array('limit' => 10));
		$timeDeltaData = array();
		foreach($command->queryAll() as $n => $datum) {
			if($n == 6) {
				$timeDeltaData[] = array((int)$datum['count'], 'Beyond');
			}
			else if($n > 6) {
				$timeDeltaData[6][0] += (int)$datum['count'];
			}
			else {
				$data = array();
				$data[] = (int)$datum['count'];
				$data[] = sprintf("%d - %d", $datum['delta'] * 20, ($datum['delta']+1) * 20);
				$timeDeltaData[] = $data;
			}
		}

		$data = array();
		$data['eq'] = array_reverse($topEqData);
		$data['errors'] = array_reverse($topErrorsData);
		$data['confusion'] = array_reverse($topConfusedData);
		$data['timeDeltas'] = array_reverse($timeDeltaData);

		if(isset($_GET['id'])) {
			$command = $this->getCommand(self::HISTOGRAM);
			$histogramData = array();
			foreach($command->queryAll() as $datum) {
				$histogramData[] = array(date('Y-m-d H:i:s', $datum['bucket']), (int)$datum['count']);
			}
			$data['histogram'] = $histogramData;
		}

		if(Yii::app()->request->isAjaxRequest) {
			echo CJavaScript::jsonEncode($data);
		}
		else {
			$this->render('summary', array(
				'data' => $data,
				'isSingle' => isset($_GET['id']),
			));
		}
	}

	public function actionEq() {
		$command = $this->getCommand(self::EQ);

		$dataProvider = new CSqlDataProvider($command->text, array(
			'keyField'=>'name',
			'sort'=>array(
				'attributes'=>array(
					'name' => array(
						'asc' => 'name',
						'desc' => 'name DESC',
						'Label' => 'Student',
					),
					'eq' => array(
						'asc' => 'eq',
						'desc' => 'eq DESC',
						'Label' => 'EQ',
					),
				),
				'defaultOrder' => 'eq DESC',
			),
			'pagination'=>false,
		));

		$eqData = $dataProvider->getData();

		$viewData = array();
		$viewData['average'] = 0;
		$count = 0;

		foreach($eqData as $datum) {
			if($datum['eq'] >= 0) {
				$viewData['average'] += $datum['eq'];
				$count++;
			}
		}

		if($count > 0) {
			$viewData['average'] = $viewData['average'] / $count;
		}
		else {
			$viewData['average'] = 0;
		}

		$this->render('eq', array(
			'viewData'=>$viewData,
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionConfusion() {
		$command = $this->getCommand(self::CONFUSION);

		$dataProvider = new CSqlDataProvider($command->text, array(
			'keyField'=>'name',
			'sort'=>array(
				'attributes'=>array(
					'name' => array(
						'asc' => 'name',
						'desc' => 'name DESC',
						'Label' => 'Student',
					),
					'confusion' => array(
						'asc' => 'confusion',
						'desc' => 'confusion DESC',
						'Label' => 'Confusion Rate',
					),
					'clips' => array(
						'asc' => 'clips',
						'desc' => 'clips DESC',
						'Label' => 'Clips',
					),
				),
				'defaultOrder' => 'confusion DESC',
			),
			'pagination'=>false,
		));

		$confusionData = $dataProvider->getData();

		$viewData = array();
		$viewData['average'] = 0;
		$count = 0;

		foreach($confusionData as $datum) {
			if($datum['confusion'] >= 0) {
				$viewData['average'] += $datum['confusion'];
				$count++;
			}
		}

		if($count > 0) {
			$viewData['average'] = $viewData['average'] / $count;
		}

		$this->render('confusion', array(
			'viewData'=>$viewData,
			'dataProvider'=>$dataProvider,
		));

	}

	public function actionError() {
		$command = $this->getCommand(self::ERROR);

		$dataProvider = new CSqlDataProvider($command->text, array(
			'keyField'=>'messageText',
			'sort'=>array(
				'attributes'=>array(
					'messageText' => array(
						'asc' => 'messageText',
						'desc' => 'messageText DESC',
						'Label' => 'Error',
					),
					'count' => array(
						'asc' => 'COUNT(messageText)',
						'desc' => 'COUNT(messageText) DESC',
						'Label' => 'Count',
					),
					'*'
				),
				'defaultOrder' => 'count DESC',
			),
			'pagination'=>false,
		));
		$errorData = $dataProvider->getData();

		$this->render('error', array(
			'dataProvider'=>$dataProvider,
		));

	}

	public function actionErrorClass() {
		$command = $this->getCommand(self::ERROR_CLASS);

		$dataProvider = new CSqlDataProvider($command->text, array(
			'keyField'=>'messageText',
			'sort'=>array(
				'attributes'=>array(
					'messageText' => array(
						'asc' => 'messageText',
						'desc' => 'messageText DESC',
						'Label' => 'Error',
					),
					'count' => array(
						'asc' => 'COUNT(messageText)',
						'desc' => 'COUNT(messageText) DESC',
						'Label' => 'Count',
					),
					'*'
				),
				'defaultOrder' => 'count DESC',
			),
			'pagination'=>false,
		));
		$errorData = $dataProvider->getData();

		$this->render('error', array(
			'dataProvider'=>$dataProvider,
		));

	}

	public function actionTimeDelta() {
		$interval = 20;
		if(isset($_GET['interval']) && is_numeric($_GET['interval'])) {
			$interval = $_GET['interval'];
		}

		$command = $this->getCommand(self::TIME_DELTA, array('interval' => $interval));
		$dataProvider = new CSqlDataProvider($command->text, array(
			'keyField'=>'delta',
			'sort'=>array(
				'attributes'=>array(
					'delta' => array(
						'asc' => 'delta',
						'desc' => 'delta DESC',
						'Label' => 'Range',
					),
					'count' => array(
						'asc' => 'count',
						'desc' => 'count DESC',
						'Label' => 'Count',
					),
					'*'
				),
				'defaultOrder' => 'delta',
			),
			'pagination'=>false,
		));
		$errorData = $dataProvider->getData();

		$this->render('timeDelta', array(
			'dataProvider'=>$dataProvider,
			'interval'=>$interval,
		));

	}

	function getCommand($reportType, $extraOptions = array()) {
		$logSessionIds = $this->getLogSessionIds();
		$criteria = new CDbCriteria;
		if($logSessionIds !== false) $criteria->condition = 'logSessionId IN ('.implode(',', $logSessionIds).')';
		$table = '';
		if($reportType == self::EQ) {
			$table = 'EqCalculation';
			$criteria->select = 'userId, logId, name, eq';
			$criteria->join = 'JOIN Log ON Log.id = logId JOIN User ON userId = User.id';
			$criteria->order = 'eq DESC';
		}
		else if($reportType == self::ERROR) {
			$table = 'CompileLogEntry';
			$criteria->select = 'messageText, COUNT(messageText) AS count';
			$criteria->group = 'messageText';
			$criteria->join = 'JOIN Log ON Log.id = logId';
			$criteria->order = 'count DESC';
		}
		else if($reportType == self::ERROR_CLASS) {
			$table = 'CompileLogEntry';
			$criteria->select = 'error AS messageText, COUNT(*) AS count';
			$criteria->group = 'error';
			$criteria->join = 'JOIN Log ON Log.id = logId LEFT JOIN ErrorClass ON compileLogEntryId = t.id';
			$criteria->order = 'count DESC';
		}
		else if($reportType == self::TIME_DELTA) {
			$interval = array_key_exists('interval', $extraOptions) ? $extraOptions['interval'] : 20;
			if(Yii::app()->db->driverName == 'mysql') {
				$criteria->select = "(b.timestamp - a.timestamp) DIV $interval AS delta";
			}
			else {
				$criteria->select = "(b.timestamp - a.timestamp)/$interval AS delta";
			}
			$criteria->join = 'JOIN Log ON a.logId = Log.id JOIN CompileLogEntry b ON a.timestamp < b.timestamp AND a.logId = b.logId';
			$criteria->group = 'a.id';
			$command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileLogEntry', $criteria, 'a');
			$subselect = $command->getText();
			$command = Yii::app()->db->createCommand("SELECT COUNT(*) AS count, delta FROM ($subselect) c GROUP BY delta");
			return $command;
		}
		else if($reportType == self::CONFUSION) {
			$table = 'Confusion';
			$criteria->select = 'userId, logId, name, confusion, clips';
			$criteria->join = 'JOIN Log ON Log.id = logId JOIN User ON userId = User.id';
			$criteria->order = 'confusion DESC, clips DESC';
		}
		else if($reportType == self::HISTOGRAM) {
			$table = 'CompileLogEntry';
			if(Yii::app()->db->driverName == 'mysql') {
				$criteria->select = 'timestamp DIV 60 * 60 AS bucket, COUNT(*) AS count';
			}
			else {
				$criteria->select = 'timestamp / 60 * 60 AS bucket, COUNT(*) AS count';
			}
			$criteria->join = 'JOIN Log ON Log.id = logId';
			$criteria->group = 'bucket';
		}
		if(array_key_exists('limit', $extraOptions)) {
			$criteria->limit = $extraOptions['limit'];
		}
		if($table == '') {
			return false;
		}
		else {
			return Yii::app()->db->getCommandBuilder()->createFindCommand($table, $criteria);
		}
	}

	function getLogSessionIds() {
		if(isset($_GET['tags']) && !empty($_GET['tags'])) {
			if(isset($_GET['id'])) {
				return array($_GET['id']);
			}
			$tagNames = array_unique(preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY));
			$_GET['tags'] = implode(',', $tagNames);

			if(Yii::app()->user->getState('report_tags') == $tagNames) {
				$logSessionIds = Yii::app()->user->getState('report_iids');
			}
			else {
				$criteria = new CDbCriteria;
				$criteria->select = 'logSessionId';
				$criteria->group = 'logSessionId';
				$criteria->join = 'JOIN Tag on tagId = Tag.id';
				$criteria->addInCondition('name', $tagNames);
				$criteria->having = 'COUNT(logSessionId) = '.count($tagNames);
				$command = Yii::app()->db->getCommandBuilder()->createFindCommand('LogSessionTag', $criteria);

				$logSessionIds = $command->queryColumn();

				Yii::app()->user->setState('report_tags', $tagNames);
				Yii::app()->user->setState('report_iids', $logSessionIds);
			}
			if(count($logSessionIds) == 0) {
				$this->redirect(array('report/empty'));
			}
			return $logSessionIds;
		}
		else {
			$_GET['tags'] = '';
			return false;
		}
	}

	function makeDetailBreadcrumbs($name) {
		if(isset($_GET['id'])) {
			$this->breadcrumbs=array(
				'Logs' => array('logSession/index'),
				'Log Session #' . $_GET['id'] => array('logSession/view', 'id'=>$_GET['id']),
				'Summary' => array('summary', 'id'=>$_GET['id']),
				$name,
			);
		}
		else if(isset($_GET['tags'])){
			$this->breadcrumbs=array(
				'Logs' => array('logSession/index', 'tags'=>$_GET['tags']),
				'General Summary' => array('summary', 'tags'=>$_GET['tags']),
				$name,
			);
		}
		else {
			$this->breadcrumbs=array(
				'Logs' => array('logSession/index'),
				'General Summary' => array('summary'),
				$name,
			);
		}
	}
}
