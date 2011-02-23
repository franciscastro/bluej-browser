<?php

/**
 * Handles creation of reports for EQ, Errors and Time Deltas.
 */
class ReportController extends Controller {
  
  public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
  
  public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','summary', 'eq', 'error'),
				'roles'=>array('Teacher', 'Researcher'),
			),
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'roles'=>array('Administrator'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
  
  public function actionIndex()
  {
    if(isset($_GET['tags'])) {
      $this->redirect(array('summary', 'tags'=>$_GET['tags']), true);
    }
    $models = Term::model()->findAll('parentId > 1');
    $this->render('index', array(
      'models' => $models,
    ));
  }
  
  public function actionSummary()
  {
    $compileSessionIds = $this->getCompileSessionIds();
    $criteria = new CDbCriteria;
    $criteria->select = 'messageText, COUNT(messageText) AS count';
    $criteria->group = 'messageText';
    $criteria->condition = 'compileSessionId IN ('.implode(',', $compileSessionIds).')';
    $criteria->limit = 10;
    $criteria->order = 'count DESC';
    $command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria);
    $topErrorsData = $command->queryAll();
    
    // highest EQ
    $criteria = new CDbCriteria;
    $criteria->select = 'name, eq';
    $criteria->join = 'JOIN User on userId = User.id JOIN EqCalculation ON t.id = compileSessionId'; // JOIN Import ON sessionId = compileSessionId';
    $criteria->condition = 'compileSessionId IN ('.implode(',', $compileSessionIds).')';
    $criteria->limit = 10;
    $criteria->order = 'eq DESC';
    //$command = Yii::app()->db->createCommand("SELECT name, eq FROM Session JOIN User ON userId = User.id JOIN SessionTerm ON Session.id=sessionId JOIN EqCalculation ON Session.id = compileSessionId WHERE $inCondition GROUP BY Session.id HAVING COUNT(Session.id) = $numTerms ORDER BY eq DESC LIMIT 10");
    $command = Yii::app()->db->getCommandBuilder()->createFindCommand('Session', $criteria);
    $topEqData = $command->queryAll();
    
    
    // time delta
    $criteria = new CDbCriteria;
    if(Yii::app()->db->driverName == 'mysql') {
      $criteria->select = 'COUNT(a.id) AS count, (a.timestamp - b.timestamp) DIV 20 AS delta';
    }
    else {
      $criteria->select = 'COUNT(a.id) AS count, (a.timestamp - b.timestamp)/20 AS delta';
    }
    $criteria->join = 'JOIN CompileSessionEntry b ON a.id = b.id+1 AND a.compileSessionId = b.compileSessionId'; // JOIN Import ON a.compileSessionId = sessionId';
    $criteria->condition = 'a.compileSessionId IN ('.implode(',', $compileSessionIds).')';
    $criteria->group = 'delta';
    //$command = Yii::app()->db->createCommand("SELECT COUNT(a.id) AS count, (a.timestamp - b.timestamp)/20 AS delta FROM CompileSessionEntry a, (SELECT id, compileSessionId, timestamp FROM CompileSessionEntry JOIN SessionTerm ON compileSessionId=sessionId WHERE termId = 7 GROUP BY id HAVING COUNT(id) = 1) b WHERE a.id = b.id+1 AND a.compileSessionId = b.compileSessionId GROUP BY delta");
    $command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria, 'a');
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
      ));
    }
    else {
      $this->render('summary', array(
        'topEqData'=>$topEqData,
        'topErrorsData'=>$topErrorsData,
        'timeDeltaData'=>$timeDeltaData,
      ));
    }
  }
  
  public function actionEq() {
    $compileSessionIds = $this->getCompileSessionIds();
  
    $criteria = new CDbCriteria;
    $criteria->select = 'name, eq';
    $criteria->join = 'JOIN User ON userId = User.id JOIN EqCalculation ON t.id = compileSessionId'; // JOIN Import ON sessionId = compileSessionId';
    $criteria->condition = 'compileSessionId IN ('.implode(',', $compileSessionIds).')';
    /*
    $criteria->group = 't.id';
    $criteria->having = 'COUNT(t.id) = ' . $numTerms;
    $criteria->addInCondition('termId', $termIds);
    
    $sort = new CSort('Session');
    $sort->attributes = array(
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
    );
    $sort->defaultOrder = 'eq DESC';
    $sort->applyOrder($criteria);
    */
    
    $command = Yii::app()->db->getCommandBuilder()->createFindCommand('Session', $criteria);
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

  public function actionError() {
    
    $compileSessionIds = $this->getCompileSessionIds();
          
    $criteria = new CDbCriteria;
    $criteria->select = 'messageText, COUNT(messageText) AS count';
    $criteria->group = 'messageText';
    //$criteria->join = 'JOIN Import ON sessionId = compileSessionId';
    $criteria->condition = 'compileSessionId IN (' . implode(',', $compileSessionIds) . ')';
    
    /*
    $criteria->select = 'messageText, COUNT(messageText) AS count';
    $criteria->group = 'messageText';
    $criteria->condition = "compileSessionId IN (SELECT sessionId FROM SessionTerm WHERE termId IN ($termList) GROUP BY sessionId HAVING COUNT(sessionId) = $numTerms)";
    
    
    $sort = new CSort('CompileSessionEntry');
    $sort->ajaxEnabled = true;
    $sort->attributes = array(
      'count' => array(
        'asc' => 'COUNT(messageText)',
        'desc' => 'COUNT(messageText) DESC',
        'Label' => 'Count',
      ),
      '*'
    );
    $sort->defaultOrder = 'count DESC';
    $sort->applyOrder($criteria);
    */
    $command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria);
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
  
  function getCompileSessionIds() {
    if(isset($_GET['tags'])) {
      $termNames = array_unique(preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY));
      $_GET['tags'] = implode(',', $termNames);
      
      if(Yii::app()->user->getState('report_tags') == $termNames) {
        $compileSessionIds = Yii::app()->user->getState('report_cids');
      }
      else {
        $criteria = new CDbCriteria;
        $criteria->select = 'sessionId';
        $criteria->group = 'sessionId';
        $criteria->join = 'JOIN ImportSessionTerm ON ImportSessionTerm.importSessionId=t.importSessionId JOIN Term ON termId = Term.id';
        $criteria->addInCondition('name', $termNames);
        $criteria->having = 'COUNT(t.importSessionId) = '.count($termNames);
        $command = Yii::app()->db->getCommandBuilder()->createFindCommand('Import', $criteria);
        $compileSessionIds = $command->queryColumn();
      
        Yii::app()->user->setState('report_tags', $termNames);
        Yii::app()->user->setState('report_cids', $compileSessionIds);
      }
      return $compileSessionIds;
    }
    else {
      $this->redirect('index');
    }
  }
}
