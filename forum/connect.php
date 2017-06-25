
<?php
$link = mysqli_connect("127.0.0.1", "root", "", "first_db");//to establish a connection to the database

if (!$link) {//debugging if the connection fails
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The first_db database is great." . PHP_EOL;
   
 session_start();
 session_destroy();//to destroy the previous session data



 if(isset($_POST["Submit"])){
   if(isset($_POST['username'])){
	 
	$username = mysqli_real_escape_string($link,$_POST['username']);
  $username = stripslashes($username);
  


   }
   if(isset($_POST['password'])){
	$password = mysqli_real_escape_string($link,$_POST['password']);
  $password = stripslashes($password);
}



 session_start();

  $password_hash = hash( 'md5', $password );//to hash the entered password to check against the database

   $sql = "SELECT * FROM `user_info` WHERE username = '" .$username. "' ";
   $res = mysqli_query($link,$sql);//to query for a row that has the same username
   $rows = mysqli_num_rows($res);//to count the number of rows that have this username

   $sql1 = "SELECT username, password, ascess_level, moderate_status FROM `user_info` WHERE username = '" .$username. "'";
   $res1 = mysqli_query($link, $sql1); 
   $ascess = mysqli_fetch_array($res1);//to fetch a associative array
   $level = $ascess['ascess_level'];
   $hash = $ascess['password'];
   $moderated = $ascess['moderate_status'];


 if($rows == 1 && $level==1 &&$password_hash==$hash){//for a admin level authentication the ascess level must be 1 and the passwords must mathc
  
    //to store the sessions data of the username and ascesslevel
    $_SESSION["username"] = $username; 
    $_SESSION["ascess_level"] = $level;
    $_SESSION["moderated"] = $moderated;
	  header('Location: forum.php');//to direct to an editable forum page
   
    echo mysqli_error($link);
	
}
  
 else if($rows==1 && $level==0 && $password_hash==$hash) {//for a normal user to check against password
    $_SESSION["username"] = $username; 
    $_SESSION["ascess_level"] = $level;

	  header('Location: viewing.php');
   }

 else if($rows==1 && $level==2 && $password_hash==$hash){
      $_SESSION["username"] = $username; 
      $_SESSION["ascess_level"] = $level;
      $_SESSION["moderated"] = $moderated;

      header("Location: class_rep.php");
 } 
 else {
  	echo "no";
    echo mysqli_error($link);

   }

  }

    
     // calculate the hash from a password
    function getPasswordHash($password)
    {
    return ( hash( 'md5', $password ) );
    }
     mysqli_close($link);
?>
<html>
<head>
	<title>Note making forum</title>
  <style type="text/css">
   .login{
     border: 2px solid black;
     border-radius: 1px 1px 1px 1px;
     padding: 15px 15px 15px 15px;
     margin right: 400px;
     margin-top: 210px;
     margin-left: 210px;
     width: 450px;
     font-size: 20px;
   }

   body {
    font: 13px/20px "Lucida Grande", Tahoma, Verdana, sans-serif;
    color: #404040;
    background: #0ca3d2;

   }
   



  </style>

</head>
  <body>
    <div class = "login">
  	<form method= "POST">

    <p>Username: <input type = "text" name = "username"></p> 
    <p>Password: <input type = "text" name = "password"></p>
     
     <input type = "submit" name = "Submit" value = "Log in">
     <a href = "captcha.php">Click here to register</a>
     
  	</form>
    </div>
  </body
</html>