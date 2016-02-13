<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/
require_once('User.class.php');
require_once('DBInterface.interface.php');

/*
I am subclassing the mysqli class as I see no reason not too, this will allow me to call $this->function instead of $this->mysqli->function
I am also making the class implement the DBInterface so in the future it is easy to switch the database class for a new class in the App class by just creating a new class that also implements the DBInterface
*/
class MysqliDB extends mysqli implements DBInterface {

	//credentials
	const DB_HOST = "localhost";
	const DB_USERNAME = "root"; //would obviously change this but Im not sure how it works for you when I give you the sql file so im leaving it standard
	const DB_PASSWORD = "";     //same as the username ,would normally change this
	const DB_NAME = "AssesmentDB";

	//Error Messages , kept in constants here so its easier to manage in the future.
	const ERRORINVALIDCREDENTIALS = "Invalid password/email";
	const ERRORCORRUPTDATA = "FATAL ERROR DATABASE CORRUPT";

	public function __construct(){
		//as this is a subclass of mysqli,call the parent __contruct
		parent::__construct(self::DB_HOST, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
		//check here if there is an error
		if ($this->connect_error){
			die("Connection Error ( ERROR Number: " . $this->connect_errno . " ) " . $this->connect_error);
		}
	}

	public function __destruct() {
		//checking that there is no connection error in the first place else trying to close prompts an error if the connection wasnt open/failed in the first place
		if (!$this->connect_error){
			$this->close();
			/*
			im not sure how necessary this is , my thinking is that maybe if this object is destroyed while a connection is open and
			the script is still running ,then a connection to the database would still be in memory while the script is still running
			if I dont manually close the connection,
			if im right,this would mean that setting the variable to NULL would kill the reference and therefore close the connection
			but maybe it already does this
			and maybe there is just no need at all to do this however I dont see any reason NOT to do it so
			Better to be safe than sorry I guess 
			Also,in this app I will never be setting the reference to NULL anyway because its so small ,it would be only if the app is big and has many concurrent connections

			*/
		}
    }

    /*
    create a new user,I am not passing in the user class as I want the user class to hold the ID
    ,and I can only know the ID after the DB has given me back the inserted ID
    */
	public function createUser($email , $password , $name){
		//sanitize the variables
		$name = $this->real_escape_string($name);
		$email = $this->real_escape_string($email);
		$password = $this->real_escape_string($password);
		$hash = md5($password);   //doing a quick hash of the password here,Im aware that md5 is not the safest and having a salt would be alot more secure

		$query = "INSERT INTO Users(name,email,password) 
				  VALUES ('$name','$email','$hash')" ;
				 
		$result = $this->query($query) or die("ERROR: " . $this->error );;
		$insertedId = $this->insert_id;
		//making the user with the hash instead of the password, I will never hold onto the password ,only the encrypted version
		return new User($insertedId , $name , $email , $hash);
	}

	public function logInUser($email, $password){

		$email = $this->real_escape_string($email);
		$password = $this->real_escape_string($password);
		$hash = md5($password);  //hashing the password as that is how the password is kept in our database

		$query = "SELECT * 
				  FROM Users
				  WHERE email='$email' 
				  	AND password='$hash'";

		$result = $this->query($query) or die("ERROR : " . $this->error);
		switch (mysqli_num_rows($result)) {
			case 0 :
				//user not found
				throw new Exception(self::ERRORINVALIDCREDENTIALS);
				break;
			case 1 :
				//user found in database ,get his details
				while ($row = mysqli_fetch_assoc($result)) {
					$id = $row['id'];
					$email = $row['email'];
					$name = $row['name'];
					$password = $row['password'];
					return new User($id , $name , $email , $password);
				}				
				break;
			default:
				//should never happen unless the database is corrupted with duplicate entries for email address
				die(self::ERRORCORRUPTDATA);
				break;
		}
	}

	
	//get all the users out of the database and return a user array
	public function getAllusers(){
		$query = "SELECT *
				  FROM Users";

		$result = $this->query($query) or die("ERROR : " . $this->error);

		//create the array where the users will be stored
		$usersArray = array();

		while ($row = mysqli_fetch_assoc($result)){
			//create a user from the row and add it to the array
			$usersArray[] = new User($row['id'] , $row['name'] , $row['email'] , $row['password'] );
		}
		return $usersArray;
	}

	public function deleteUser(User $user){
		//sanitize variables(id will never come from the user but no harm in doing it)
		$id = $this->real_escape_string($user->getId());

		$query = "DELETE FROM Users
				  WHERE id='$id'
				  LIMIT 1";
		$result = $this->query($query) or die("ERROR : " . $this->error);
	}

	//when user trys to create a new account we need to check if that email is already in use,returns true if it is
	public function checkIfEmailExists($email){
		//sanitize variables
		$email = $this->real_escape_string($email);

		$query = "SELECT id
				  FROM Users
				  WHERE email='$email' ";

		$result = $this->query($query) or die("ERROR : " . $this->error);
		//if there are more than 0 rows the the email already exists
		if (mysqli_num_rows($result) > 0){
			return true;
		} else {
			return false;
		}
	}
}

?>