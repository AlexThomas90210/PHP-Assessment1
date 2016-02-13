<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/
require_once("./Model/constants.php");
class DisplayUserView {
	
	public function headHTML($count){
		//if the count is 1 than we need to say "User" else we say "Users"
		$userString = " Users";
		if ($count == 1 ){
			$userString = " User";
		}
		$string = "We have ".$count.$userString." on our subscriber list!...";
		echo $string;
	}

	public function HTML($name , $email){
		$string = '<p>'.$name.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Email:</b> '.$email.'</p>';
		echo $string;
	}
	//I could have the view receive an array of users which would let me bunch up these 2 functions into one but I want 
	//to keep the view totaly seperate from the model and let the controller be smart and take care of this
}
?>