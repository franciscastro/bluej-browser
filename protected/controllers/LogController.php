<?php

/**
 * Handles viewing of logs. This also receives live log requests
 * from the BlueJ plugin.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */

include("xmlrpc.inc");
include("xmlrpcs.inc");
include("xmlrpc_wrappers.inc");

class LogController extends Controller {
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
			array('allow',
				'actions'=>array('xmlrpc'),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view', 'delete'),
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

	/**
	 * Displays a particular model.
	 * @param integer the ID of the model to be displayed
	 */
	public function actionView($id) {
		$model = $this->loadModel($id);

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the LogSession page.
	 * @param integer the ID of the model to be deleted
	 */
	public function actionDelete($id) {
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			$parent = $model->logSessionId;
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('logSession/admin', 'id'=>$parent));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Log::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Receives a log sent via the BlueJ extension or optionally any other
	 * source that uses XML-RPC
	 */
	public function actionXmlrpc() {
		global $xmlrpcString, $xmlrpcArray, $xmlrpcStruct;

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$server=new xmlrpc_server(array(
				"insert" => array(
					"function" => "LogController::insert",
					"signature" => array(array($xmlrpcString, $xmlrpcString, $xmlrpcArray, $xmlrpcArray, $xmlrpcStruct)),
				)
			), false);

			$server->functions_parameters_type='phpvals';
			$server->setdebug(3);
			$server->compress_response = true;

			$server->service();
		}
	}

	/**
	 * This handles the insert operation called via XML-RPC that the BlueJ
	 * extension does
	 */
	public static function insert($tableName, $columnNames, $columnTypes, $data) {
		global $xmlrpcerruser, $err;

		$pc = strripos($tableName, '_');
		$userName = substr($tableName, 0, $pc);
		$logType = substr($tableName, $pc+1);

		LogSession::model()->liveInsert($userName, $logType, $data);
		return true;
	}
}
