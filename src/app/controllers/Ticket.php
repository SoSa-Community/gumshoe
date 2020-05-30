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
	
	/**
	 * @post("ticket/update")
	 */
	public function updateTicket()
	{
		
		$responseData = null;
		$status = 'failure';
		$error = new \Error('Not logged in', 1);
		
		$request = $_POST;
		
		if($_REQUEST['_user']){
			$userId = $_REQUEST['_user']['id'];
			$isAdmin = $_REQUEST['_user']['admin'];
			
			$ticketId = $request['id'] ?? null;
			$description = $request['description'] ?? null;
			$ownerId = $request['owner_id'] ?? null;
			$privacy = $request['privacy'] ?? null;
			
			if(!empty($ownerId)) $ownerId = intval($ownerId);
			
			if(empty($ticketId)){
				$error = new \Error('Please provide a ticket id', 2);
			}elseif(empty($description) && empty($ownerId) && empty($privacy)){
				$error = new \Error('Please provide something to change!', 3);
			}else{
				$ticket = DAO::getOne(\models\Ticket::class, 'id = ?', false, [$ticketId]);
				if(empty($ticket)){
					$error = new \Error('Ticket does not exist', 4);
				}else{
					$lockMode = $ticket->getLockMode();
					
					$isOwner = ($userId === $ticket->getOwnerId());
					$isCreator = ($userId === $ticket->getCreatorId());
					
					if(!$isOwner && !$isCreator && !$isAdmin){
						$error = new \Error('You don\'t have permission to change this ticket', 5);
					}elseif($lockMode == 'full' && !$isAdmin){
						$error = new \Error('You don\'t have permission to change this ticket', 6);
					}
					else{
						if(!empty($description))    $ticket->setDescription($description);
						if(!empty($ownerId))        $ticket->setOwnerId($ownerId);
						if(!empty($privacy)){
							if(!$ticket->setPrivacy($privacy)){
								$error = new \Error('Please provide a valid value for privacy', 7);
							}
						}
						
						if(DAO::save($ticket)){
							$error = null;
							$status = 'success';
							$responseData = ['ticket' => $ticket->getPublicOutput()];
						}
					}
				}
			}
		}
		
		echo $this::generateResponse($status, $responseData, $error);
		
		
	}
	
}
