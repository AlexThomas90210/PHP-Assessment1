<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/

	/*
	the following constants are used in the $_SESSION[] superglobal and represent the names of the keys that I am storing so
	that I am not hard coding them in ,doing this will make me less prone to typing errors and in my opinion makes it easier to read.
	*/
	define("SESSIONUSERID"		,"userId");
	define("SESSIONUSERLOGGEDIN","loggedIn");
	define("SESSIONUSEREMAIL"   ,"userEmail");
	define("SESSIONUSERHASH"    ,"userHash");
	define("SESSIONUSERNAME"    ,"userName");

	//the following constants are for the names of the keys of the $_POST[] superglobal for the Login form
	define("LOGINPASSWORD"      ,"logInPassword");
	define("LOGINEMAIL"         ,"logInEmail");
	define("LOGINPOST"          ,"logInPOST");

	//the following constants are the names of the keys for the register form
	define("REGISTERPASSWORD1"  ,"registerPassword1");
	define("REGISTERPASSWORD2"  ,"registerPassword2");
	define("REGISTEREMAIL"      ,"registerEmail");
	define("REGISTERNAME"       ,"registerName");
	define("REGISTERPOST"       ,"registerPOST");

	//the following constants are the names of the keys for the delete account form
	define("DELETEPOST"         ,"deletePOST");

	//the following constants are the names of the keys for the logOut form
	define("LOGOUTPOST"         ,"logOutPOST");
?>