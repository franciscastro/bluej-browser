<?php

/**
 * Handles creation and viewing of logs, whether live or not.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class LogSessionController extends Controller {
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
				'expression'=>'!LogSession::checkTeacherAccess($user->getModel());',
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
	public function actionView($id) {
		$model = $this->loadModel($id);
		$dataProvider=new CActiveDataProvider('Log', array(
			'criteria'=>array(
				'condition'=>'logSessionId='.$model->id,
				'with'=>array('user'),
			),
			'sort'=>array(
				'attributes'=>array(
					'id'=>array(
						'asc'=>'id',
						'desc'=>'id DESC',
					),
					'username'=>array(
						'asc'=>'user.name',
						'desc'=>'user.name DESC',
						'label'=>'Student'
					),
					'user.computer'=>array(
						'asc'=>'user.computer',
						'desc'=>'user.computer DESC',
						'label'=>'Computer'
					)
				),
				'defaultOrder'=>'user.name',
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));

		$this->render('view',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate() {
		$model=new LogSession;

		if(isset($_POST['LogSession'])) {
			$model->attributes=$_POST['LogSession'];
			$file = CUploadedFile::getInstance($model, 'source');
			if(!$file->getTempName() == '') {
				$importDir = Yii::app()->file->set(Yii::app()->params['importRoot']);
				if(!$importDir->getIsDir()) {
					$importDir->createDir();
				}
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
					$files = CFileHelper::findFiles($sourcePath, array('fileTypes'=>array('sqlite','csv'), 'exclude'=>array('.htaccess')));
					$transaction = Log::model()->dbConnection->beginTransaction();
					$prevDir = '';
					foreach($files as $file) {
						$directory = dirname($file);
						if($prevDir != $directory) {
							$prevDir = $directory;
							$model = new LogSession;
							$model->attributes=$_POST['LogSession'];
							$model->source = $sourceFile;
							$directory = str_ireplace($sourcePath, '', $directory);
							$model->path = $directory;
							$tagNames = str_ireplace(',', ';', $directory);
							$tagNames = str_ireplace(DIRECTORY_SEPARATOR, ',', $tagNames);

							$_POST['tag'][Tag::TERM_OTHER] = $tagNames;
							$model->newTags = Section::model()->getNewTags();
							if(isset($_POST['tag']['section'])) {
								$model->sectionId = $_POST['tag']['section'];
							}
							else {
								$viewData = Section::model()->getViewData($model->newTags);
								if($viewData['section'] != '') {
									$model->sectionId = $viewData['section'];
								}
							}
							$model->save();
						}
						$model->fileLog($file);
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
			'tags'=>Section::model()->getViewData(),
		));
	}

	public function actionCreateLive() {
		$model=new LogSession;
		$tags = array();

		if(isset($_POST['LogSession'])) {
			$tags = Section::model()->getNewTags();
			$model->attributes=$_POST['LogSession'];
			$model->source = 'live';
			$model->start = time();
			$model->newTags = $tags;
			if(isset($_POST['tag']['section'])) {
				$model->sectionId = $_POST['tag']['section'];
			}
			else {
				$viewData = Section::model()->getViewData($tags);
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
			'tags'=>Section::model()->getViewData($tags),
		));
	}

	public function actionStopLive($id) {
		$model=$this->loadModel($id);

		if(Yii::app()->request->isPostRequest) {
			$model->newTags = $model->tags;
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
	public function actionUpdate($id) {
		$model=$this->loadModel($id);

		if(isset($_POST['LogSession'])) {
			$model->attributes=$_POST['LogSession'];
			$model->newTags = Section::model()->getNewTags();
			if(isset($_POST['tag']['section'])) {
				$model->sectionId = $_POST['tag']['section'];
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'tags'=>Section::model()->getViewData($model->tags),
		));
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
		$model=new LogSession('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LogSession']))
			$model->attributes=$_GET['LogSession'];
		if(Yii::app()->user->hasRole(array('Teacher'))) {
			$model->sectionId = $this->modelArrayToAttributeArray(Yii::app()->user->getModel()->sections, 'id');
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionExport($id) {
		set_time_limit(0);
		$model = $this->loadModel($id);
		$exportName = $this->getExportName();
		$exportZip = Yii::app()->file->set(Yii::app()->params['exportRoot'] . '/' . $exportName . '.zip');
		if($exportZip->getIsFile()) {
			if($model->start != null && ($model->end == null || $model->end > $exportZip->timeModified)) {
				$exportZip->delete(true);
				$exportDir = $this->makeExportDir($exportName, $model);
				Yii::app()->zip->makeZip($exportDir->getRealPath(), $exportZip->getRealPath());
			}
		}
		else {
			//$exportZip->create();
			$exportDir = $this->makeExportDir($exportName, $model);
			Yii::app()->zip->makeZip($exportDir->getRealPath(), $exportZip->getRealPath());
		}
		Yii::app()->getRequest()->sendFile(basename($exportZip->getBaseName()), file_get_contents($exportZip->getRealPath()));
	}

	private $_processed;

	public function actionExportAll() {
		set_time_limit(0);
		$search=new LogSession('search');
		$search->unsetAttributes();  // clear any default values
		if(isset($_GET['LogSession']))
			$search->attributes=$_GET['LogSession'];
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
			$tagNames = preg_split('/\s*,\s*/', $_GET['tags'], null, PREG_SPLIT_NO_EMPTY);
			$exportName = implode('-', $tagNames);
		}

		$exportZip = Yii::app()->file->set(Yii::app()->params['exportRoot'] . '/' . $exportName . '.zip');
		if($exportZip->getIsFile()) {
			$exportZip->delete(true);
		}
		Yii::app()->zip->makeZip($exportDirs, $exportZip->getRealPath());

		Yii::app()->getRequest()->sendFile(basename($exportZip->getBaseName()), file_get_contents($exportZip->getRealPath()));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=LogSession::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='log-log-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * This determines the name of the exported folder and zip file.
	 * If the model was originally imported from a file, it will use
	 * the name of the file that was uploaded. Otherwise, it gets
	 * the Year-Course-Section and uses that.
	 * @param CModel the model to be exported
	 */
	private function getExportName($model = null) {
		if($model == null) {
			$model = $this->loadModel($_GET['id']);
		}

		$exportName = $model->id;
		if($model->source != 'live') {
			$exportName = substr($model->path, 1);
			$exportName = str_replace('\\', '-', $exportName);
			$exportName = str_replace('/', '-', $exportName);
		}
		else {
			$majorTags = array();
			foreach($model->tags as $tag) {
				if($tag->parentId == Tag::TERM_OTHER) continue;
				$majorTags[$tag->parentId] = $tag->name;
			}
			$majorTags[] = date('Ymd', $model->start);
			$majorTags[] = $model->id;
			$exportName = implode($majorTags, '-');
		}
		return $exportName;
	}

	/**
	 * This makes a directory and exports the logs as CSVs into the
	 * directory. Exports are cached so exporting multiple times
	 * should not take a long while.
	 * @param string the name of the directory
	 * @param CModel the model to export
	 */
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
		$logModels = $model->logs;
		foreach($logModels as $logModel) {
			$clogModel = $logModel->invocationLog;
			if($clogModel != null) {
				$fp = fopen($logModel->user->name . '_InvocationData-' . $clogModel->id . '.csv', 'w');
				$clogModel->doExport($fp);
				fclose($fp);
			}
			$clogModel = $logModel->compileLog;
			if($clogModel != null) {
				$fp = fopen($logModel->user->name . '_CompileData-' . $clogModel->id . '.csv', 'w');
				$clogModel->doExport($fp);
				fclose($fp);
			}
		}
		$this->_processed = true;
		return $exportDir;
	}
}
