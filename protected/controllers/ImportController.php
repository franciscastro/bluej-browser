<?php

/**
 * Receives live import requests from the BlueJ plugin.
 *
 * @author Thomas Dy <thatsmydoing@gmail.com>
 * @copyright Copyright &copy; 2010-2011 Ateneo de Manila University
 * @license http://www.opensource.org/licenses/mit-license.php
 */

include("xmlrpc.inc");
include("xmlrpcs.inc");
include("xmlrpc_wrappers.inc");

class ImportController extends Controller {
	public function actionXmlrpc() {
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

	public static function insert($tableName, $columnNames, $columnTypes, $data) {
		global $xmlrpcerruser, $err;

		$pc = strripos($tableName, '_');
		$userName = substr($tableName, 0, $pc);
		$sessionType = substr($tableName, $pc+1);

		ImportSession::model()->liveInsert($userName, $sessionType, $data);
		return true;
	}
}
