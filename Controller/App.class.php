<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/

class App { 
	//constant error message if the user trys to create an account and provided unmatching password
	const ERRORUNMATCHINGPASSWORD = 'Passwords did not match'; 
	//constant error message if the user trys to make an account with an email already in use
	const ERROREMAILINUSE = 'That email is already in use';  
	//constant error message if the user did not fill in all the fields
	const ERRORMISSINGFIELDS = 'Please fill in all the required fields'; 
	//constant error message if the user used whitespaces in the register fields
	const ERRORSPACESINFIELD = 'Please do not use Spaces at the beginning or end of the required fields';
	//constant error message if the password is too short
	const ERRORPASSWORDLENGTH = 'Your password must be at least 6 characters long';
	/*
	My views that submit a form have a parameter for where the form is to be submited,I could have seperate endpoints for 
	my form submitions however at the moment I am submiting all my forms to index.php and letting this class check for the
	submission so I am keeping that in a constant and passing it in whenever I instantiate a new view class
	*/
	const FORMSUBMITENDPOINT = 'index.php';

	//stores the error message for the user if he fails to login
	private $loginErrorMessage = "";  
	//stores the error message for the user if he fails to register
	private $registerErrorMessage = "";


	//database variable,
	private $db;
	//the user (if he is logged in) the only way of setting this is by using the logUserIn() function
	private $user;

	function __construct(){
		/*
		 since I made an interface for my Database I can easily switch the class in the
    	future if I wanted to change from mysqli to another database by just switching this line to the other class
    	and having that class implement the interface
    	*/
		$this->db = new MysqliDB();

		/*
		first check if the user is already logged in (I do this before any form checking because if the user has
		submited the DeleteAccount form then we are using the data stored in the $_SESSION to delete the account
		which would not be present as $this->user would not be set) 
		*/
		$this->checkSessionForUser();
		//check if the user has submited any form and process them
		$this->checkIfUserSubmitedAForm();
	}

	//MARK: PUBLIC

	public function isUserLoggedIn(){
		//checks if we have a user (indicates if user is logged in or not)
		if (isset($this->user)){
			return true;
		} else {
			return false;
		}
	}

	public function sayHello(){
		if ($this->isUserLoggedIn()){
			echo "Hi ".$this->user->getName()."!";
		} else {
			echo "Hi, Please Login or Register to view the User list.";
		}
	}

	/*
	all of my functions that spit out HTML create the view class inside the function as there is no need to instantiate
	a view object unless I will be using it ,for example if the user is logged in then there is no need to instantiate the LoginView class
	this is obviously just a tiny detail but I thought it made sense.
	Also all of these views are only called once so there is no need to keep a reference to the class 
	*/

	public function registerViewHTML(){
		//create the view class with the form submision endpoint
		$registerView = new RegisterView(self::FORMSUBMITENDPOINT);
		/*
		I can see if the user just tried to register his account and failed by checking $this->registerErrorMessage 
		if it is not equal to "" then the user just tried to submit the register form and failed,therefore I can
		take all the details he entered and reuse them,saving him having to retype all the details.I will however make him
		retype the password fields so im just taking the name and email from $_POST
		*/

		//I will pass in an empty string if there is no posted register fields
		$presetName = "";
		$presetEmail = "";
		//do the check and set the apropriate values
		if ($this->registerErrorMessage != ""){
			//Im going to double check that we actually have the $_POST variable just incase
			if (isset($_POST[REGISTERNAME])){
				$presetName = $_POST[REGISTERNAME];
			}
			if (isset($_POST[REGISTEREMAIL])){
				$presetEmail = $_POST[REGISTEREMAIL];
			}
		}
		$registerView->HTML($presetName , $presetEmail , $this->registerErrorMessage);
	}

	public function loginViewHTML(){
		//create the view class with the form submision endpoint
		$loginView = new LoginView(self::FORMSUBMITENDPOINT);
		/*
			just like in the register function , I can see if the user just tried to login and failed if loginErrorMessage is not equal to ""
			,if he did I can get his email address from the $_POST
		*/
		$presetEmail = "";
		if ($this->loginErrorMessage != ""){
			//Im going to double check that we actually have the $_POST variable just incase
			if (isset($_POST[LOGINEMAIL])){
				$presetEmail = $_POST[LOGINEMAIL];
			}
		}

		$loginView->HTML($presetEmail , $this->loginErrorMessage);
	}

	public function logoutViewHTML(){
		//create the view class with the form submision endpoint
		$logoutView = new LogoutView(self::FORMSUBMITENDPOINT);
		$logoutView->HTML();
	}

	public function deleteUserViewHTML(){
		//create the view class with the form submision endpoint
		$deleteUserView = new DeleteUserView(self::FORMSUBMITENDPOINT);
		$deleteUserView->HTML();
	}

	public function allUsersViewHTML(){
		//create the view class
		$displayUserView = new DisplayUserView();
		//get all the users in an array from the DB
		$usersArray = $this->db->getAllUsers();

		$displayUserView->headHTML(count($usersArray));
		//iterate over all the users in the array and spit out the html for that specific user
		foreach ($usersArray as $user){
			$name = $user->getName();
			$email = $user->getEmail();
			$displayUserView->HTML($name , $email);
		}
	}

	//MARK: PRIVATE

	private function checkSessionForUser(){
		//check the session variables to see if we have the user details there
		if (isset($_SESSION[SESSIONUSERLOGGEDIN]) && isset($_SESSION[SESSIONUSERID]) && isset($_SESSION[SESSIONUSEREMAIL]) && ($_SESSION[SESSIONUSERHASH]) ){
			//double check user logged in is marked as true
			if ($_SESSION[SESSIONUSERLOGGEDIN] != true) {
				exit();
			}
			//we have the user in the session,store the variables into easier to read variables
			$id = $_SESSION[SESSIONUSERID];
			$name = $_SESSION[SESSIONUSERNAME];
			$email = $_SESSION[SESSIONUSEREMAIL];
			$hash = $_SESSION[SESSIONUSERHASH];

			//create the user
			$user = new User($id , $name , $email , $hash);
			//set the user
			$this->user = $user;
		}
	}

	private function logUserOut(){
		//set the user and NULL and clear all the session variables and set loggedin to false
		$this->user = NULL;
		$_SESSION[SESSIONUSERLOGGEDIN] = false;
		$_SESSION[SESSIONUSERID] = NULL;
		$_SESSION[SESSIONUSEREMAIL] = NULL;
		$_SESSION[SESSIONUSERHASH] = NULL;
		$_SESSION[SESSIONUSERNAME] = NULL;
	}

	private function logUserIn($user){
		//set the user and set all the required session variables
		$this->user = $user;
	 	$_SESSION[SESSIONUSERLOGGEDIN] = true;
		$_SESSION[SESSIONUSERID] = $user->getId();
		$_SESSION[SESSIONUSERNAME] = $user->getName();
		$_SESSION[SESSIONUSEREMAIL] = $user->getEmail();
		$_SESSION[SESSIONUSERHASH] = $user->getHash();
		//I am storing the hash in the session
	}

	//helper function which takes all the functions that check for submited forms and calls them 
	private function checkIfUserSubmitedAForm(){
		$this->checkForLoginPOST();
		$this->checkForLogoutPOST();
		$this->checkForRegisterUserPOST();
		$this->checkForDeleteUserPOST();
	}

	//REGISTER function
	private function checkForRegisterUserPOST(){
		//check if the register $_POST variables are set
		if (isset($_POST[REGISTERPOST]) && isset($_POST[REGISTERPASSWORD1]) && isset($_POST[REGISTERPASSWORD2]) && isset($_POST[REGISTERNAME]) && isset($_POST[REGISTEREMAIL])){
			//user is trying to register prepare the variables
			$password1 = $_POST[REGISTERPASSWORD1];
			$password2 = $_POST[REGISTERPASSWORD2];
			$name = ucfirst($_POST[REGISTERNAME]); //make the first letter capital
			$email = $_POST[REGISTEREMAIL];
			
			//check if the user inputed any space bars at the start or end of any input field,only checking 1 password as we do a matching password check after
			//count all the characters
			$startingStringLength = strlen($password1) + strlen($name) + strlen($email);
			//count all the characters with trim() I am not adding the strings together as that would not give me the correct answer
			$trimmedStringLength = strlen(trim($password1)) + strlen(trim($name)) + strlen(trim($email));
			//begin the if block of doom to check if the inputs are ok
			if ($startingStringLength != $trimmedStringLength){
				//user has spaces at the start/end of a field
				$this->registerErrorMessage = self::ERRORSPACESINFIELD;

			} else if ($password1 != $password2){
				//password are not equal 
				$this->registerErrorMessage = self::ERRORUNMATCHINGPASSWORD;
			
			} else if (strlen($password1) < 6) {
				//password length is less than 6 characters
				$this->registerErrorMessage = self::ERRORPASSWORDLENGTH;

			} else if ($password1 == "" || $name == "" || $email == "" ) {
				//Error
				$this->registerErrorMessage = self::ERRORMISSINGFIELDS;

			} else if ($this->db->checkIfEmailExists($email) == true){
				//email address is already in use in the database
				$this->registerErrorMessage = self::ERROREMAILINUSE;

			} else {
				//*****   ALL IS GOOD   *****
				//create the user    ,the database is in charge of sanitizing the input
				$user = $this->db->createUser($email , $password1 , $name);
				//log the user in (this will set the $this->user)
				$this->logUserIn($user);
			}
		}
	}

	//LOGOUT function
	private function checkForLogoutPOST(){
		//check if the logout form was posted
		if (isset($_POST[LOGOUTPOST])) {
			//logout form was posted,log the user out
			$this->logUserOut();
		}
	}

	//DELETE ACCOUNT function
	private function checkForDeleteUserPOST(){
		//check if the delete form was posted and we have a user logged in
		// technically because of the order of the functions being called in construct the user will always be set if the delete for is posted but its better to double check
		if (isset($_POST[DELETEPOST]) && isset($this->user)){
			//tell database to delete the user
			$this->db->deleteUser($this->user);
			//log the user out as he is still in the SESSION variable
			$this->logUserOut();
		}
	}

	//LOGIN function
	private function checkForLoginPOST(){
		//check if the login form has been submited 
		if (isset($_POST[LOGINPOST]) && isset($_POST[LOGINPASSWORD]) && isset($_POST[LOGINEMAIL])){
			//login form has been submited
			$password = $_POST[LOGINPASSWORD];
			$email = $_POST[LOGINEMAIL];

			//Database throws an error if no user is found so use try catch block
			try {
				$user = $this->db->logInUser($email , $password);
				//user succesfully logged in,log him in
				$this->logUserIn($user);
			} catch (Exception $e) {
				//error with credentials ,set the error message
     			$this->loginErrorMessage = "Error:".$e->getMessage();
			}
		}
	}
}
?>