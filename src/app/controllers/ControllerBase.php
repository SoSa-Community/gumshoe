<?php
namespace controllers;

use Ubiquity\controllers\Controller;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;

/**
 * ControllerBase.
 **/
abstract class ControllerBase extends Controller{
	protected $headerView = "@activeTheme/main/vHeader.html";
	protected $footerView = "@activeTheme/main/vFooter.html";

	public function initialize() {
		UResponse::asJSON();
		
		if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
			$request = json_decode(trim(file_get_contents("php://input")), true);
			$_POST = array_merge($_POST, $request);
		}
		
		$_REQUEST = [];
		$headers = getallheaders();
		$sessionId = $headers['session-id'] ?? null;
		$refreshToken = $headers['refresh-token'] ?? null;
		$deviceToken = $headers['token'] ?? null;
		
		if(!empty($sessionId)){
			try{
				//TODO: Implement Auth
				if($sessionId === '1'){
					$_REQUEST['_user'] = ['id' => 1, 'admin' => false];
				}
				elseif($sessionId === '2'){
					$_REQUEST['_user'] = ['id' => 1, 'admin' => true];
				}
				elseif($sessionId === '3'){
					$_REQUEST['_user'] = ['id' => 3, 'admin' => false];
				}
				
			}catch(\Exception $e){
				error_log($e);
			}
		}
		
		$_REQUEST['_headers'] = $headers;
		
		/*
		if (! URequest::isAjax ()) {
			$this->loadView ( $this->headerView );
		}*/
	}

	public function finalize() {
		/*
		if (! URequest::isAjax ()) {
			$this->loadView ( $this->footerView );
		}
		*/
	}
	
	public static function generateResponse(string $status='failure', $data=null, \Throwable $error=null){
		$response = ['status' => $status];
		
		if((!empty($data) && !empty($error)) || !empty($data))  $response['response'] = $data;
		if(!empty($error)){
			
			if(is_a($error, '\Throwable')){
				$error = ['message' => $error->getMessage(), 'code' => $error->getCode()];
			}
			
			$response['error'] = $error;
		}
		
		$sessionRefreshed = $_REQUEST['_sessionRefreshed'] ?? false;
		
		if($sessionRefreshed)   $response['session'] = $_REQUEST['_session']->getPublicOutput();
		
		return json_encode($response);
	}
}

