<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/

class User {

	private $id; 
	private $name;
	private $email;
	//Im keeping the hash of the user here , probably should not but itl be grand sure!
	private $hash;

	public function __construct($id , $name , $email , $hash){
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->hash = $hash;
	}

	public function getName(){
		return $this->name;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getId(){
		return $this->id;
	}

	public function getHash(){
		return $this->hash;
	}
}

?>