<?php
/********************************************************** 
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy 
* Student ID: xxxxx
* Date : 2016/02/10
* Ref: none
***********************************************************/

session_start();
//Controller
require_once('Controller/App.class.php');
//Model
require_once('./Model/User.class.php');
require_once('./Model/DB.class.php');
require_once('./Model/constants.php');
require_once('./Model/DBInterface.interface.php');
//Views
require_once('./Views/DeleteUserView.php');
require_once('./Views/DisplayUserView.php');
require_once('./Views/LoginView.php');
require_once('./Views/LogoutView.php');
require_once('./Views/RegisterView.php');

//create the app,the constructor will do all the form checking and SESSION checking
$app = new App();
?>
<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Simple User List</title>

  </head>

  <body>

    <?php
    //say hello,will vary on if the user is logged in or not
    $app->sayHello();
    //check if the user is logged in
    if (!$app->isUserLoggedIn()){
      //User is not logged in
      //show User the Login view
      $app->loginViewHTML();
      //show User the Register view
      $app->registerViewHTML();
    } else {
      //User is logged in
      //show User the Logout view
      $app->logoutViewHTML();
      //show User the DeleteAccount view
      $app->deleteUserViewHTML();
      //show User all the users of the app (kinda what the point of the app is, like a subscriber list or members list with login functionality)
      $app->allUsersViewHTML();
    }
    //I am not closing the database here or earlier as we are using it in the 
    //last if statement above, and in the __destruct method of the DB class it will automatically close
    ?>

</body>
</html>
