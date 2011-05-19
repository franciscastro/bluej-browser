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
		$models = Term::model()->findAll('parentId > 1');
		$this->render('index', array(
			'models' => $models,
		));
	}

	public function actionSummary() {
		$command = $this->getCommand(self::ERROR_CLASS, array('limit' => 10));
		$topErrorsData = $command->queryAll();

		$command = $this->getCommand(self::EQ, array('limit' => 10));
		$topEqData = $command->queryAll();

		$command = $this->getCommand(self::CONFUSION, array('limit' => 10));
		$topConfusedData = $command->queryAll();

		$command = $this->getCommand(self::TIME_DELTA, array('limit' => 10));
		$timeDeltaData = $command->queryAll();
		foreach($timeDeltaData as $n=>$datum) {
			if($n > 6) {
				$timeDeltaData[6]['count'] += $datum['count'];
				unset($timeDeltaData[$n]);
			}
			else {
				$timeDeltaData[$n]['from'] = ($datum['delta'] * 20);
				$timeDeltaData[$n]['to'] = ($datum['delta'] * 20 + 20);
			}
		}

		if(Yii::app()->request->isAjaxRequest) {
			$this->renderPartial('_summary', array(
				'topEqData'=>$topEqData,
				'topErrorsData'=>$topErrorsData,
				'timeDeltaData'=>$timeDeltaData,
				'topConfusedData'=>$topConfusedData,
			));
		}
		else {
			$this->render('summary', array(
				'topEqData'=>$topEqData,
				'topErrorsData'=>$topErrorsData,
				'timeDeltaData'=>$timeDeltaData,
				'topConfusedData'=>$topConfusedData,
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
		$importSessionIds = $this->getImportSessionIds();
		$criteria = new CDbCriteria;
		if($importSessionIds !== false) $criteria->condition = 'importSessionId IN ('.implode(',', $importSessionIds).')';
		$table = '';
		if($reportType == self::EQ) {
			$table = 'EqCalculation';
			$criteria->select = 'name, eq';
			$criteria->join = 'JOIN Import ON Import.id = compileSessionId JOIN User ON userId = User.id';
			$criteria->order = 'eq DESC';
		}
		else if($reportType == self::ERROR) {
			$table = 'CompileSessionEntry';
			$criteria->select = 'messageText, COUNT(messageText) AS count';
			$criteria->group = 'messageText';
			$criteria->join = 'JOIN Import ON Import.id = compileSessionId';
			$criteria->order = 'count DESC';
		}
		else if($reportType == self::ERROR_CLASS) {
			$table = 'CompileSessionEntry';
			$criteria->select = 'error AS messageText, COUNT(*) AS count';
			$criteria->group = 'error';
			$criteria->join = 'JOIN Import ON Import.id = compileSessionId LEFT JOIN ErrorClass ON compileSessionEntryId = t.id';
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
			$criteria->join = 'JOIN Import ON a.compileSessionId = Import.id JOIN CompileSessionEntry b ON a.id < b.id AND a.compileSessionId = b.compileSessionId';
			$criteria->group = 'a.id';
			$command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria, 'a');
			$subselect = $command->getText();
			$command = Yii::app()->db->createCommand("SELECT COUNT(*) AS count, delta FROM ($subselect) c GROUP BY delta");
			return $command;
		}
		else if($reportType == self::CONFUSION) {
			$table = 'Confusion';
			$criteria->select = 'name, confusion, clips';
			$criteria->join = 'JOIN Import ON Import.id = compileSessionId JOIN User ON userId = User.id';
			$criteria->order = 'confusion DESC, clips DESC';
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

	function getImportSessionIds() {
		if(isset($_GET['tags']) && !empty($_GET['tags'])) {
			if(isset($_GET['id'])) {
				return array($_GET['id']);
			}
			$termNames = array_unique(preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY));
			$_GET['tags'] = implode(',', $termNames);

			if(Yii::app()->user->getState('report_tags') == $termNames) {
				$importSessionIds = Yii::app()->user->getState('report_iids');
			}
			else {
				$criteria = new CDbCriteria;
				$criteria->select = 'importSessionId';
				$criteria->group = 'importSessionId';
				$criteria->join = 'JOIN Term on termId = Term.id';
				$criteria->addInCondition('name', $termNames);
				$criteria->having = 'COUNT(importSessionId) = '.count($termNames);
				$command = Yii::app()->db->getCommandBuilder()->createFindCommand('ImportSessionTerm', $criteria);

				$importSessionIds = $command->queryColumn();

				Yii::app()->user->setState('report_tags', $termNames);
				Yii::app()->user->setState('report_iids', $importSessionIds);
			}
			if(count($importSessionIds) == 0) {
				$this->redirect(array('report/empty'));
			}
			return $importSessionIds;
		}
		else {
			$_GET['tags'] = '';
			return false;
		}
	}

	function makeDetailBreadcrumbs($name) {
		if(isset($_GET['id'])) {
			$this->breadcrumbs=array(
				'Logs' => array('importSession/index'),
				'Log Session #' . $_GET['id'] => array('importSession/view', 'id'=>$_GET['id']),
				'Summary' => array('summary', 'id'=>$_GET['id']),
				$name,
			);
		}
		else if(isset($_GET['tags'])){
			$this->breadcrumbs=array(
				'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
				'General Summary' => array('summary', 'tags'=>$_GET['tags']),
				$name,
			);
		}
		else {
			$this->breadcrumbs=array(
				'Logs' => array('importSession/index'),
				'General Summary' => array('summary'),
				$name,
			);
		}
	}
}
