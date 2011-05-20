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
				'actions'=>array('updateEntry', 'deleteEntry'),
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
	public function actionView($id) {
		$model = $this->loadModel($id);
		$dataProvider = new CActiveDataProvider('CompileSessionEntry', array(
			'criteria'=> array(
				'condition'=>'compileSessionId='.$id,
				'order'=>'timestamp',
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
			'deleted'=>CompileSessionEntry::model()->findAllByAttributes(array('compileSessionId'=>(-$id))),
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionSource($id = 0) {
		if($id == 0) {
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
		else {
			$model = $this->loadEntryModel($id);
			$this->render('source', array(
				'model'=>$model,
			));
		}
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CompileSession::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateEntry($id)
	{
		$model=$this->loadEntryModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CompileSessionEntry']['compileSessionId']))
		{
			if($model->compileSessionId < 0) {
				if($this->loadModel(-$model->compileSessionId)->undeleteEntry($model)) {
					$this->redirect(array('view','id'=>$model->compileSessionId));
				}
			}
			if($model->compileSession->moveEntry($model, $_POST['CompileSessionEntry']['compileSessionId']))
				$this->redirect(array('view','id'=>$model->compileSessionId));
		}

		$this->render('updateEntry',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a compile session entry.
	 */
	public function actionDeleteEntry($id) {
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadEntryModel($id);
			$model->compileSession->deleteEntry($model);

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
	 * @param integer the ID of the model to be loaded
	 */
	public function loadEntryModel($id)
	{
		$model=CompileSessionEntry::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}
