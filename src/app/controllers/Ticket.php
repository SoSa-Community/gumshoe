<?php
namespace controllers;

use models\Device;
use models\Session;
use models\User;
use PHPMailer\PHPMailer\Exception;
use Ubiquity\controllers\Startup;
use Ubiquity\exceptions\DAOException;
use Ubiquity\orm\DAO;

/**
 * Ticket Controller
 **/
class Ticket extends ControllerBase{
	
	public function index(){return [];}
	
	/**
	 * @post("ticket")
	 */
	public function createTicket(){
		
		$responseData = null;
		$status = 'failure';
		$error = new \Error('Not logged in', 1);
		
		$request = $_POST;
		
		$title = $request['title'] ?? null;
		$description = $request['description'] ?? null;
		
		if($_REQUEST['_user']){
			if(empty($title)){
				$error = new \Error('Please provide a title', 2);
			}elseif(empty($description)){
				$error = new \Error('Please provide a description', 3);
			}
			else{
				$ticket = new \models\Ticket();
				$ticket->setTitle($title);
				$ticket->setDescription($description);
				$ticket->generateSlug();
				$ticket->setCreatorId($_REQUEST['_user']['id']);
				$ticket->setOwnerId($_REQUEST['_user']['id']);
				
				try{
					if(DAO::save($ticket)) {
						$error = null;
						$status = 'success';
						$responseData = ['ticket' => $ticket->getPublicOutput()];
					}
				}catch (\Exception $e){
					
					$error = $e;
				}
			}
		}
		
		echo $this::generateResponse($status, $responseData, $error);
	}
}
