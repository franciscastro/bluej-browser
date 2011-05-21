<?php

/**
 * Receives live log requests from the BlueJ plugin.
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
				'actions'=>array('delete'),
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

	public static function insert($tableName, $columnNames, $columnTypes, $data) {
		global $xmlrpcerruser, $err;

		$pc = strripos($tableName, '_');
		$userName = substr($tableName, 0, $pc);
		$logType = substr($tableName, $pc+1);

		LogSession::model()->liveInsert($userName, $logType, $data);
		return true;
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel() {
		if($this->_model===null) {
			if(isset($_GET['id']))
				$this->_model=Log::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
