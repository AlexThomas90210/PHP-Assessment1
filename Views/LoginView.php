<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/
require_once("./Model/constants.php");

class LoginView {
  	private $actionEndpoint;

  	function __construct($actionEndpoint){
   		 $this->actionEndpoint = $actionEndpoint;
 	}

  	public function HTML($presetEmail , $error){
  		//Usings constans define in constants.php for the names of my inputs 
		$string = 	'<h1> Login </h1>
				<form action="'.$this->actionEndpoint.'" method="POST" name="LoginForm">
     			   	Email <input type="text" name="'.LOGINEMAIL.'" value="'.$presetEmail.'"/>
     			   	<br/>
     			   	Password <input type="password" name="'.LOGINPASSWORD.'" />
     			   	<br/>
          			<input type="submit" value="Login!" name="'.LOGINPOST.'"  />
				</form>
				<p style="color:red;">'.$error.'</p>
				<br/>
				<br/>';
    	echo $string;
	}
}
?>