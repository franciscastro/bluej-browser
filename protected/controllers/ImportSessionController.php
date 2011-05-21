<?php

/**
 * Handles creation of import sessions, whether live or not.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class ImportSessionController extends Controller {
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
			array('deny',
				'actions'=>array('view', 'export', 'exportAll', 'update', 'stopLive'),
				'roles'=>array('Teacher'),
				'expression'=>'!ImportSession::checkTeacherAccess($user->getModel());',
			),
			array('allow',
				'actions'=>array('index', 'view', 'export', 'exportAll', 'createLive', 'stopLive', 'update'),
				'roles'=>array('Teacher'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'roles'=>array('Administrator', 'Researcher'),
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
		$importModel=new Import('search');
		$importModel->unsetAttributes();  // clear any default values
		$importModel->importSessionId = $model->id;

		$this->render('view',array(
			'model'=>$model,
			'import'=>$importModel,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model=new ImportSession;

		if(isset($_POST['ImportSession'])) {
			$model->attributes=$_POST['ImportSession'];
			$file = CUploadedFile::getInstance($model, 'source');
			if(!$file->getTempName() == '') {
				$sourceFile = Yii::app()->params['importRoot'] . $file->getName();
			}
			else {
				$file = null;
			}
			if($file !== null && $file->saveAs($sourceFile)) {
				$sourcePath = Yii::app()->params['importRoot'] . $this->id;
				$tempDir = Yii::app()->file->set($sourcePath);
				if($tempDir->exists) {
					$tempDir->delete(true);
				}
				$tempDir->createDir();
				set_time_limit(0);
				if(Yii::app()->zip->extractZip($sourceFile, $sourcePath)) {
					$files = CFileHelper::findFiles($sourcePath, array('fileTypes'=>array('sqlite'), 'exclude'=>array('.htaccess')));
					$transaction = Import::model()->dbConnection->beginTransaction();
					$prevDir = '';
					foreach($files as $file) {
						$directory = dirname($file);
						if($prevDir != $directory) {
							$prevDir = $directory;
							$model = new ImportSession;
							$model->attributes=$_POST['ImportSession'];
							$model->source = $sourceFile;
							$directory = str_ireplace($sourcePath, '', $directory);
							$model->path = $directory;
							$termNames = str_ireplace(',', ';', $directory);
							$termNames = str_ireplace(DIRECTORY_SEPARATOR, ',', $termNames);

							$_POST['term'][Term::TERM_OTHER] = $termNames;
							$model->newTerms = $this->getTermModel()->getNewTerms();
							$model->save();
						}
						$model->fileImport($file);
					}
					$transaction->commit();
					$this->redirect(array('view','id'=>$model->id));
				}
				else {
					$model->addError('source', 'There was a problem with the file you uploaded.');
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'terms'=>$this->getTermModel()->getViewData(),
		));
	}

	public function actionCreateLive() {
		$model=new ImportSession;
		$terms = array();

		if(isset($_POST['ImportSession'])) {
			$terms = $this->getTermModel()->getNewTerms();
			$model->attributes=$_POST['ImportSession'];
			$model->source = 'live';
			$model->start = time();
			$model->newTerms = $terms;
			if(isset($_POST['term']['section'])) {
				$model->sectionId = $_POST['term']['section'];
			}
			else {
				$viewData = Section::model()->getViewData($terms);
				if($viewData['section'] != '') {
					$model->sectionId = $viewData['section'];
				}
			}
			if($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('createLive',array(
			'model'=>$model,
			'terms'=>$this->getTermModel()->getViewData($terms),
		));
	}

	public function actionStopLive() {
		$model=$this->loadModel();

		if(Yii::app()->request->isPostRequest) {
			$model->newTerms = $model->terms;
			$model->end = time();
			if($model->save()) {
				if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view','id'=>$model->id));
			}

		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate() {
		$model=$this->loadModel();

		if(isset($_POST['ImportSession'])) {
			$model->attributes=$_POST['ImportSession'];
			$model->newTerms = $this->getTermModel()->getNewTerms();
			if(isset($_POST['term']['section'])) {
				$model->sectionId = $_POST['term']['section'];
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'terms'=>$this->getTermModel()->getViewData($model->terms),
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete() {
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->loadModel()->delete();

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
		$model=new ImportSession('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ImportSession']))
			$model->attributes=$_GET['ImportSession'];
		if(Yii::app()->user->hasRole(array('Teacher'))) {
			$model->sectionId = $this->modelArrayToAttributeArray(Yii::app()->user->getModel()->sections, 'id');
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionExport() {
		set_time_limit(0);
		$model = $this->loadModel();
		$exportName = $this->getExportName();
		$exportZip = Yii::app()->file->set(Yii::app()->params['exportRoot'] . '/' . $exportName . '.zip');
		if($exportZip->getIsFile()) {
			if($model->start != null && ($model->end == null || $model->end > $exportZip->timeModified)) {
				$exportZip->delete(true);
				$exportZip->create();
				$exportDir = $this->makeExportDir($exportName, $model);
				Yii::app()->zip->makeZip($exportDir->getRealPath(), $exportZip->getRealPath());
			}
		}
		else {
			$exportZip->create();
			$exportDir = $this->makeExportDir($exportName, $model);
			Yii::app()->zip->makeZip($exportDir->getRealPath(), $exportZip->getRealPath());
		}
		Yii::app()->getRequest()->sendFile(basename($exportZip->getBaseName()), file_get_contents($exportZip->getRealPath()));
	}

	private $_processed;

	public function actionExportAll() {
		set_time_limit(0);
		$search=new ImportSession('search');
		$search->unsetAttributes();  // clear any default values
		if(isset($_GET['ImportSession']))
			$search->attributes=$_GET['ImportSession'];
		if(Yii::app()->user->hasRole(array('Teacher'))) {
			$search->sectionId = $this->modelArrayToAttributeArray(Yii::app()->user->getModel()->sections, 'id');
		}
		$search = $search->search();
		$search->setPagination(false);
		$models = $search->getData();

		$exportDirs = array();
		$this->_processed = false;
		$count = 0;
		foreach($models as $model) {
			$count++;
			$exportName = $this->getExportName($model);
			$exportDir = $this->makeExportDir($exportName, $model);
			$exportDirs[] = $exportDir->getRealPath();
			if($this->_processed) {
				header('refresh:1;url='.Yii::app()->request->getRequestURI());
				$count = (count($models)-$count);
				if($count == 0) {
					echo 'Almost done! Just zipping up the file...';
				}
				else {
					echo 'Processing... ' . $count . ' to go!';
				}
				exit;
			}
		}

		$exportName = 'all';
		if(isset($_GET['tags'])) {
			$termNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
			$exportName = implode('-', $termNames);
		}

		$exportZip = Yii::app()->file->set(Yii::app()->params['exportRoot'] . '/' . $exportName . '.zip');
		if($exportZip->getIsFile()) {
			$exportZip->delete(true);
		}
		$exportZip->create();
		Yii::app()->zip->makeZip($exportDirs, $exportZip->getRealPath());

		Yii::app()->getRequest()->sendFile(basename($exportZip->getBaseName()), file_get_contents($exportZip->getRealPath()));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel() {
		if($this->_model===null) {
			if(isset($_GET['id']))
				$this->_model=ImportSession::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='import-session-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function getTermModel() {
		//if(Yii::app()->user->hasRole(array('Teacher'))) {
			return CActiveRecord::model('Section');
		//}
		//return CActiveRecord::model('Term');
	}

	private function getExportName($model = null) {
		if($model == null) {
			$model = $this->loadModel();
		}

		$exportName = $model->id;
		if($model->source != 'live') {
			$exportName = substr($model->path, 1);
			$exportName = str_replace('\\', '-', $exportName);
			$exportName = str_replace('/', '-', $exportName);
		}
		else {
			$majorTerms = array();
			foreach($model->terms as $term) {
				if($term->parentId == Term::TERM_OTHER) continue;
				$majorTerms[$term->parentId] = $term->name;
			}
			$majorTerms[] = $model->id;
			$exportName = implode($majorTerms, '-');
		}
		return $exportName;
	}

	private function makeExportDir($exportName, $model) {
		$exportDir = Yii::app()->file->set(Yii::app()->params['exportRoot'] . '/' . $exportName . '/');
		if($exportDir->getIsDir()) {
			if($model->start != null && ($model->end == null || $model->end > $exportDir->timeModified)) {
				$exportDir->delete(true);
			}
			else {
				return $exportDir;
			}
		}
		$exportDir->createDir();
		chdir($exportDir->getRealPath());
		$importModels = $model->imports;
		foreach($importModels as $importModel) {
			$sessionModel = $importModel->invocationSession;
			if($sessionModel != null) {
				$fp = fopen($importModel->user->name . '-InvocationData-' . $importModel->id . '.csv', 'w');
				$sessionModel->doExport($fp);
				fclose($fp);
			}
			$sessionModel = $importModel->compileSession;
			if($sessionModel != null) {
				$fp = fopen($importModel->user->name . '-CompileData-' . $importModel->id . '.csv', 'w');
				$sessionModel->doExport($fp);
				fclose($fp);
			}
		}
		$this->_processed = true;
		return $exportDir;
	}
}
