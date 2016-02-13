<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/
require_once("./Model/constants.php");
class LogoutView {
	//where the form submits too
  	private $actionEndpoint;

  	function __construct($actionEndpoint){
   		 $this->actionEndpoint = $actionEndpoint;
 	}

  	public function HTML(){
  		//im using the constant define in constanst.php for the name attribute of the submit input.
		//giving the submit input a name allows me to access it in the $_POST without any need for extra or hidden inputs 
		$string = '<h1> Logout </h1>
					<form action="'.$this->actionEndpoint.'" method="POST" name="Logout Form">
						<input type="submit" value="Logout" name="'.LOGOUTPOST.'"/>
					</form>';
		echo $string;
  	}
}
?>