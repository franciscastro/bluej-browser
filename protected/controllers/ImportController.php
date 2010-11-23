<?php

/**
 * Receives live import requests from the BlueJ plugin.
 */

include("xmlrpc.inc");
include("xmlrpcs.inc");
include("xmlrpc_wrappers.inc");

class ImportController extends Controller
{	
	public function actionXmlrpc()
	{
		global $xmlrpcString, $xmlrpcArray, $xmlrpcStruct;
	
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$server=new xmlrpc_server(array(
				"insert" => array(
					"function" => "ImportController::insert",
					"signature" => array(array($xmlrpcString, $xmlrpcString, $xmlrpcArray, $xmlrpcArray, $xmlrpcStruct)),
				)
			), false);
			
			$server->functions_parameters_type='phpvals';
			$server->setdebug(3);
			$server->compress_response = true;

			$server->service();
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	public static function insert($tableName, $columnNames, $columnTypes, $data)
	{
		global $xmlrpcerruser, $err;
		
		$pc = strripos($tableName, '_');
		$userName = substr($tableName, 0, $pc);
		$sessionType = substr($tableName, $pc+1); 
		
		ImportSession::model()->liveInsert($userName, $sessionType, $data);		
		
		//method response
		return true;
	}
}
