<?php

/**
 * Handles viewing of compile logs and their entries.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CompileLogController extends Controller {
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
				'actions'=>array('source','compare'),
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

	public function actionSource($id = 0, $logId = 0) {
		if($id == 0) {
			$dataProvider = new CActiveDataProvider('CompileLogEntry', array(
				'criteria'=>array(
					'condition'=>'logId='.$logId,
				),
				'pagination'=>array(
					'pageSize'=>1,
					'pageVar'=>'page',
				),
				'sort'=>array(
					'sortVar'=>'sort',
				),
			));
			$model = $dataProvider->data[0];

			$this->render('source',array(
				'model'=>$model,
				'pages'=>$dataProvider->pagination,
			));
		}
		else {
			$model = $this->loadEntryModel($id);
			$this->render('source', array(
				'model'=>$model,
			));
		}
	}

	public function actionCompare($logId) {
		$dataProvider = new CActiveDataProvider('CompileLogEntry', array(
			'criteria'=>array(
				'condition'=>'logId='.$logId,
			),
			'pagination'=>array(
				'pageSize'=>1,
				'pageVar'=>'page',
			),
			'sort'=>array(
				'sortVar'=>'sort',
			),
		));
		$model = $dataProvider->data[0];
		$pages = $dataProvider->pagination;
		$pages->currentPage++;
		$data = $dataProvider->getData(true);
		$model2 = $data[0];
		$pages->currentPage--;
		$pages->itemCount--;

		$diff = CompileLog::diff($model->fileContents, $model2->fileContents);
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
		$model=CompileLog::model()->findByPk((int)$id);
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

		if(isset($_POST['CompileLogEntry']['logId']))
		{
			if($model->logId < 0) {
				if($this->loadModel(-$model->logId)->undeleteEntry($model)) {
					$this->redirect(array('view','id'=>$model->logId));
				}
			}
			if($model->log->moveEntry($model, $_POST['CompileLogEntry']['logId']))
				$this->redirect(array('view','id'=>$model->logId));
		}

		$this->render('updateEntry',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a compile log entry.
	 */
	public function actionDeleteEntry($id) {
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadEntryModel($id);
			$model->log->deleteEntry($model);

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
		$model=CompileLogEntry::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}
