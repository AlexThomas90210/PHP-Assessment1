<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/

require_once("./Model/constants.php");

class RegisterView {
  //where the form submits to
  private $actionEndpoint;

  function __construct($actionEndpoint){
    $this->actionEndpoint = $actionEndpoint;
  }

  public function HTML($presetName , $presetEmail , $error){
    //I am passing in variables so that the $Name and $Email inputs can have values inserted into them to save the user having to retype them
    //I am leaving the controller dictate if they should be blank or not
    //I am also leaving the controller dictate if the error message is blank or not
    //I want my views to be as 'Dumb' as possible and let my controller be smart 
    $string = '<h1> Create Account </h1>
               <form action="'.$this->actionEndpoint.'" method="POST" name="myForm">
                      Name <input type="text" name="'.REGISTERNAME.'" value="'.$presetName.'" />
                      <br/>
                      Email <input type="text" name="'.REGISTEREMAIL.'" value="'.$presetEmail.'" />
                      <br/>
                      Password <input type="password" name="'.REGISTERPASSWORD1.'" />
                      <br/>
                      Password <input type="password" name="'.REGISTERPASSWORD2.'" />
                      <br/>
                      <input type="submit" value="Register" name="'.REGISTERPOST.'" />
                      <p style="color:red;">'.$error.'</p>  
                </form>
                <br/>
                <br/>';
    echo $string;
  }
}
?>