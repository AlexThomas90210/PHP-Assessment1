<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/
require_once("./Model/constants.php");
class DeleteUserView {
	//where the form will be submited
	private $actionEndpoint;

	function __construct($actionEndpoint ) {
		$this->actionEndpoint = $actionEndpoint;
	}

	public function HTML(){
		//im using the constant define in constanst.php for the name attribute of the input.
		//giving the submit input a name allows me to access it in the $_POST without any need for extra or hidden inputs 
		$string = 	'<h1> Delete Account </h1>
					<form action="'.$this->actionEndpoint.'" method="POST" name="Delete Account Form">
						<input type="submit" value="Delete Acount" name="'.DELETEPOST.'" />
					</form>
					<br>
					<br>';
		echo $string;
	}
}
?>