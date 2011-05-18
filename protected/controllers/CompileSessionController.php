<?php

/**
 * Handles viewing of compile sessions and their entries.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CompileSessionController extends Controller {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','source','compare'),
				'roles'=>array('Teacher', 'Researcher'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('deleteEntry'),
				'roles'=>array('Researcher'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'roles'=>array('Administrator'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView() {
		$model = $this->loadModel();
		$dataProvider = new CActiveDataProvider('CompileSessionEntry', array(
			'criteria'=> array(
				'condition'=>'compileSessionId='.$_GET['id'],
			),
			'pagination'=>false,
		));

		$criteria = new CDBCriteria($dataProvider->criteria);

		$sort = new CSort('CompileSessionEntry');
		$sort->applyOrder($criteria);
		$dataProvider->sort = $sort;

		$importSessionId = $model->import->importSessionId;
		$breadcrumbs=array(
			'Logs'=>array('importSession/index'),
			'Log Session #'.$importSessionId=>array('importSession/view', 'id'=>$importSessionId),
			'Compile Log #'.$_GET['id']=>array('compileSession/view', 'id'=>$_GET['id']),
		);

		Yii::app()->user->setState('compileSession_breadcrumbs', $breadcrumbs);
		Yii::app()->user->setState('compileSession_criteria', $criteria);

		$this->render('view',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionSource() {
		$criteria = Yii::app()->user->getState('compileSession_criteria');
		$count = CompileSessionEntry::model()->count($criteria);
		$pages = new CPagination($count);

		$pages->pageSize = 1;
		$pages->applyLimit($criteria);
		$model = CompileSessionEntry::model()->find($criteria);

		$this->render('source',array(
			'model'=>$model,
			'pages'=>$pages,
		));
	}

	public function actionCompare() {
		$criteria = Yii::app()->user->getState('compileSession_criteria');
		$count = CompileSessionEntry::model()->count($criteria);
		$pages = new CPagination($count-1);

		$pages->pageSize = 1;
		$pages->applyLimit($criteria);
		$criteria->limit = 2;
		$models = CompileSessionEntry::model()->findAll($criteria);

		$model = $models[0];
		$model2 = $models[1];
		$diff = CompileSession::diff($model->fileContents, $model2->fileContents);
		$this->render('compare',array(
			'model'=>$model,
			'model2'=>$model2,
			'diff'=>$diff,
			'pages'=>$pages,
		));
	}

	/**
	 * Deletes a compile session entry.
	 */
	public function actionDeleteEntry() {
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = CompileSessionEntry::model()->findByPk($_GET['id']);
			$model->compileSessionId = -$model->compileSessionId;
			$model->save();


			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel() {
		if($this->_model===null) {
			if(isset($_GET['id']))
				$this->_model=CompileSession::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
