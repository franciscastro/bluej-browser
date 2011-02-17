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
    if(isset($_GET['tags'])) {
      $termNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
      $_GET['tags'] = implode(',', $termNames);
            
      $criteria = new CDbCriteria;
      $criteria->select = 'messageText, COUNT(messageText) AS count';
      $criteria->group = 'messageText';
      $criteria->condition = 'compileSessionId IN (SELECT sessionId FROM Import WHERE importSessionId IN ('.Term::createSubSelect('ImportSession', $termNames).'))';
      $criteria->limit = 10;
      $criteria->order = 'count DESC';
      $command = Yii::app()->db->getCommandBuilder()->createFindCommand('CompileSessionEntry', $criteria);
      $topErrorsData = $command->queryAll();
      
      // highest EQ
      $criteria = new CDbCriteria;
      $criteria->select = 'name, eq';
      $criteria->join = 'JOIN User on userId = User.id JOIN EqCalculation ON t.id = compileSessionId'; // JOIN Import ON sessionId = compileSessionId';
      $criteria->condition = 'compileSessionId IN (SELECT sessionId FROM Import WHERE importSessionId IN ('.Term::createSubSelect('ImportSession', $termNames).'))';
      $criteria->limit = 10;
      $criteria->order = 'eq DESC';
      //$command = Yii::app()->db->createCommand("SELECT name, eq FROM Session JOIN User ON userId = User.id JOIN SessionTerm ON Session.id=sessionId JOIN EqCalculation ON Session.id = compileSessionId WHERE $inCondition GROUP BY Session.id HAVING COUNT(Session.id) = $numTerms ORDER BY eq DESC LIMIT 10");
      $command = Yii::app()->db->getCommandBuilder()->createFindCommand('Session', $criteria);
      $topEqData = $command->queryAll();
      
      // confused people
      $criteria = new CDbCriteria;
      $criteria->select = 'name';
      $criteria->join = 'JOIN User on userId = User.id JOIN Confusion ON t.id = compileSessionId'; // JOIN Import ON sessionId = compileSessionId';
      $criteria->condition = 'isConfused=1 AND compileSessionId IN (SELECT sessionId FROM Import WHERE importSessionId IN ('.Term::createSubSelect('ImportSession', $termNames).'))';
      $criteria->limit = 10;
      //$command = Yii::app()->db->createCommand("SELECT name, eq FROM Session JOIN User ON userId = User.id JOIN SessionTerm ON Session.id=sessionId JOIN EqCalculation ON Session.id = compileSessionId WHERE $inCondition GROUP BY Session.id HAVING COUNT(Session.id) = $numTerms ORDER BY eq DESC LIMIT 10");
      $command = Yii::app()->db->getCommandBuilder()->createFindCommand('Session', $criteria);
      $topConfusedData = $command->queryAll();
      
      // time delta
      $criteria = new CDbCriteria;
      $criteria->select = 'COUNT(a.id) AS count, (a.timestamp - b.timestamp)/20 AS delta';
      $criteria->join = 'JOIN CompileSessionEntry b ON a.id = b.id+1 AND a.compileSessionId = b.compileSessionId'; // JOIN Import ON a.compileSessionId = sessionId';
      $criteria->condition = 'a.compileSessionId IN (SELECT sessionId FROM Import WHERE importSessionId IN ('.Term::createSubSelect('ImportSession', $termNames).'))';
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
      $displayParameters = array(
        'topEqData'=>$topEqData,
        'topConfusedData'=>$topConfusedData,
        'topErrorsData'=>$topErrorsData,
        'timeDeltaData'=>$timeDeltaData,
      );
      if(Yii::app()->request->isAjaxRequest) {
        $this->renderPartial('_summary', $displayParameters);
      }
      else {
        $this->render('summary', $displayParameters);
      }
    }
  }
  
  public function actionEq() {
    if(isset($_GET['tags'])) {
      $termNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
      $_GET['tags'] = implode(',', $termNames);
            
      $criteria = new CDbCriteria;
      $criteria->select = 'name, eq';
      $criteria->join = 'JOIN User ON userId = User.id JOIN EqCalculation ON t.id = compileSessionId'; // JOIN Import ON sessionId = compileSessionId';
      $criteria->condition = 'compileSessionId IN (SELECT sessionId FROM Import WHERE importSessionId IN ('.Term::createSubSelect('ImportSession', $termNames).'))';
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
      $viewData['average'] = $viewData['average'] / $count;
      
      $this->render('eq', array(
        'viewData'=>$viewData,
        'dataProvider'=>$dataProvider,
      ));
    }
  }

  public function actionError() {
    if(isset($_GET['tags'])) {
      $termNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
      $_GET['tags'] = implode(',', $termNames);
            
      $criteria = new CDbCriteria;
      $criteria->select = 'messageText, COUNT(messageText) AS count';
      $criteria->group = 'messageText';
      //$criteria->join = 'JOIN Import ON sessionId = compileSessionId';
      $criteria->condition = 'compileSessionId IN (SELECT sessionId FROM Import WHERE importSessionId IN ('.Term::createSubSelect('ImportSession', $termNames).'))';
      
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
  }  
}
