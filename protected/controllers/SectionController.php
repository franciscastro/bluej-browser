<?php

/**
 * Handles actions involving sections.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class SectionController extends Controller {
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update', 'admin','delete'),
				'roles'=>array('Administrator'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model=new Section;
		$terms = array();

		if(isset($_POST['term'])) {
			$yearModel = Term::model()->getTermById($_POST['term'][Term::TERM_YEAR], Term::TERM_YEAR);
			$courseModel = Term::model()->getTermById($_POST['term'][Term::TERM_COURSE], Term::TERM_COURSE);
			$sectionModel = Term::model()->getTermById($_POST['term'][Term::TERM_SECTION], Term::TERM_SECTION);
			if($yearModel != null &&
					 $courseModel != null &&
					 $sectionModel != null) {
				$model->yearId = $yearModel->id;
				$model->courseId = $courseModel->id;
				$model->sectionId = $sectionModel->id;
				$model->name = $yearModel->name . '/' . $courseModel->name . '/' . $sectionModel->name;
			}
			$terms = $_POST['term'];

			$newTeachers = array();
			if(isset($_POST['teacher']))
			foreach($_POST['teacher'] as $teacherId) {
				$teacher = User::model()->findByPk($teacherId);
				if($teacher != null && $teacher->roleId == User::ROLE_TEACHER) {
					$newTeachers[] = $teacher;
				}
			}
			$model->newTeachers = $newTeachers;
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'terms'=>$terms,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id) {
		$model=$this->loadModel($id);
		$terms = array();
		$terms[Term::TERM_YEAR] = $model->yearId;
		$terms[Term::TERM_COURSE] = $model->courseId;
		$terms[Term::TERM_SECTION] = $model->sectionId;

		if(isset($_POST['term'])) {
			$yearModel = Term::model()->getTermById($_POST['term'][Term::TERM_YEAR], Term::TERM_YEAR);
			$courseModel = Term::model()->getTermById($_POST['term'][Term::TERM_COURSE], Term::TERM_COURSE);
			$sectionModel = Term::model()->getTermById($_POST['term'][Term::TERM_SECTION], Term::TERM_SECTION);
			if($yearModel != null &&
					 $courseModel != null &&
					 $sectionModel != null) {
				$model->yearId = $yearModel->id;
				$model->courseId = $courseModel->id;
				$model->sectionId = $sectionModel->id;
				$model->name = $yearModel->name . '/' . $courseModel->name . '/' . $sectionModel->name;
			}
			$terms = $_POST['term'];

			$newTeachers = array();
			if(isset($_POST['teacher']))
			foreach($_POST['teacher'] as $teacherId) {
				$teacher = User::model()->findByPk($teacherId);
				if($teacher != null && $teacher->roleId == User::ROLE_TEACHER) {
					$newTeachers[] = $teacher;
				}
			}
			$model->newTeachers = $newTeachers;
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'terms'=>$terms,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) {
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	/**
	 * Manages all models.
	 */
	public function actionIndex() {
		$model=new Section('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Section']))
			$model->attributes=$_GET['Section'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model=Section::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='section-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
