<?php

namespace models;

use Ubiquity\controllers\Startup;
use Ubiquity\orm\DAO;
use \Firebase\JWT\JWT;
/**
 * @table("name"=>"tickets")
 **/
class Ticket{
	/**
	 * @id
	 */
	private int $id = 0;
	private string $title = '';
	private string $description = '';
	
	/**
	 * @column("name"=>"status_id")
	 */
	private int $statusId = 1;
	
	/**
	 * @column("name"=>"type_id")
	 */
	private int $typeId = 0;
	
	private string $privacy = 'private';
	
	/**
	 * @column("name"=>"lock_mode")
	 */
	private string $lockMode = 'partial';
	private string $slug = '';
	
	/**
	 * @column("name"=>"creator_id")
	 */
	private int $creatorId = 0;
	
	/**
	 * @column("name"=>"owner_id")
	 */
	private int $ownerId = 0;
	
	private string $created = '';
	private string $updated = '';
	
	public function getId(){return $this->id;}
	public function setId($id){$this->id = $id;}
	
	public function getTitle(){return $this->title;}
	public function setTitle($title){$this->title = $title;}
	
	public function getDescription(){return $this->description;}
	public function setDescription($description){$this->description = $description;}
	
	public function getStatusId(){return $this->statusId;}
	public function setStatusId($statusId){$this->statusId = $statusId;}
	
	public function getTypeId(){return $this->typeId;}
	public function setTypeId($typeId){$this->typeId = $typeId;}
	
	public function getPrivacy(){return $this->privacy;}
	public function setPrivacy($privacy){
		if(in_array($privacy,['private','public'])){
			$this->privacy = $privacy;
			return true;
		}
		return false;
	}
	
	public function getLockMode(){return $this->lockMode;}
	public function setLockMode($lockMode){$this->lockMode = $lockMode;}
	
	public function getSlug(){return $this->slug;}
	public function setSlug($slug){$this->slug = $slug;}
	
	public function generateSlug(){
		$slug = substr(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title))),0, 90);
		$slug .= '-' . substr(bin2hex(random_bytes(10)), 0, 5);
		$this->setSlug($slug);
	}
	
	public function getCreatorId(){return $this->creatorId;}
	public function setCreatorId($creatorId){$this->creatorId = $creatorId;}
	
	public function getOwnerId(){return $this->ownerId;}
	public function setOwnerId($ownerId){$this->ownerId = $ownerId;}
	
	public function getCreated(){return $this->created;}
	public function setCreated($created){$this->created = $created;}
	
	public function getUpdated(){return $this->updated;}
	public function setUpdated($updated){$this->updated = $updated;}
	
	public function getPublicOutput(){
		return $this->_rest;
	}
}