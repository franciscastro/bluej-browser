<?php

/**
 * Handles actions involving terms.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class TermController extends Controller {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',
				'actions'=>array('view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','create','update','search'),
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
		$termModel = $this->loadModel($id);
		$dataProvider = new CActiveDataProvider('ImportSession', array(
			'criteria'=>array(
				'join'=>'INNER JOIN ImportSessionTerm ON id = importSessionId',
				'condition'=>'termId = :id',
				'params'=>array(
					':id'=>$termModel->id,
				),
				'order'=>'id',
			),
		));

		$this->render('view',array(
			'model'=>$termModel,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model=new Term;

		if(isset($_POST['Term'])) {
			$model->attributes=$_POST['Term'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate($id) {
		$model=$this->loadModel($id);

		if(isset($_POST['Term'])) {
			$model->attributes=$_POST['Term'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionMerge() {
		if(isset($_POST['tags'])) {
			$terms = preg_split('/\s*,\s*/', $_POST['tags']);
			$terms = array_unique($terms);

			$model = Term::model()->findByAttributes(array('name'=>$terms[0]));
			foreach($terms as $term) {
				$termModel = Term::model()->findByAttributes(array('name'=>$term));
				$model->mergeWith($termModel);
			}
			$this->redirect(array('view','id'=>$model->id));
		}
		$this->render('merge');
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete($id) {
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex() {
		$model=new Term('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Term']))
			$model->attributes=$_GET['Term'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionSearch() {
		if(Yii::app()->getRequest()->getIsAjaxRequest()) {
			if(!isset($_GET['parent'])) {
				$terms = Term::model()->findAll('name LIKE :name AND parentId > 1', array(
					':name' => $_GET['term'].'%',
				));
			}
			else {
				$terms = Term::model()->findAll('name LIKE :name AND parentId = :parent', array(
					':name' => $_GET['term'].'%',
					':parent' => $_GET['parent'],
				));
			}
			$termNames = array();
			foreach($terms as $term) {
				$termNames[] = $term->name;
			}
			echo CJavaScript::jsonEncode($termNames);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Term::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='term-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
