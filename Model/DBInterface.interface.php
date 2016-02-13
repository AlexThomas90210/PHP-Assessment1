<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/
require_once('User.class.php');

//small interface for the database class
interface DBInterface {

	public function createUser($email , $hash , $name);  //returns a new User
	public function deleteUser(User $user);    //no return
	// logInUser throws an exception if the user has an invalid email or password
	public function logInUser($email , $password); //returns a User
	public function getAllUsers();	//returns an array of User
	public function checkIfEmailExists($email);   //returns true/false
}
?>