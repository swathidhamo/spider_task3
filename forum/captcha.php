<html>
<head>
	<title></title>
	<?php

session_start();
    $link = mysqli_connect("127.0.0.1", "root", "", "first_db");
// Create a blank image and add some text
   if(!isset($_POST["username"]) || !isset($_POST["captcha"])){//to generate a captcha image when the fields are'nt active
   $image = imagecreatetruecolor(120, 40);

   $text_color = imagecolorallocate($image, 233, 14, 91);
   $val= rand(9,true).rand(9,true).rand(9,true).rand(9,true).rand(9,true).rand(9,true);//to create a 6 digit random number 
   imagestring($image, 5, 5, 5,  $val , $text_color);

// Save the image as 'simpletext.jpg'
   imagejpeg($image, 'captcha.jpg',100);

// Free up memory
   imagedestroy($image);
   $_SESSION['captcha_value']=$val;
 }

  if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["submit"]) && isset($_POST['captcha']))
  {//when the fields are entered and the submit button is clicked

	 if($_POST['captcha']==$_SESSION['captcha_value'])//to check if the captcha entered matches against the value of the captcha
    	{
	 	    $username = $_POST["username"];
	    	$password = $_POST["password"];
		    $hash = getPasswordHash($password);
        $ascess = 0;

		        $sql = "INSERT INTO user_info (username, password, ascess_level) VALUES (?,?,?)";
            $query = mysqli_prepare($link,$sql);
            mysqli_stmt_bind_param($query,"ssi",$username,$hash,$ascess);
            $result = mysqli_stmt_execute($query);
            if($result){

        	  header("Location: connect.php");
            echo "Sucessfully created!";
            }
	
	}
	else{
	
		echo "Incorrect captcha";

     }
  }



   function getPasswordHash( $password )
   {
    return ( hash( 'md5', $password ) );
   }

?>
<style type="text/css">
  body{
     background: #0ca3d2;
    }

  .login{
     border: 2px solid black;
     border-radius: 6px 6px 6px 6px;
     padding: 15px 15px 15px 15px;
     margin right: 400px;
     margin-top: 150px;
     margin-left: 250px;
     width: 450px;
     font-size: 20px;
  }
  .warn{
    font-size: 7px;
    padding-left: 60px;
    color: red;
  }





   </style>
</head>
<body>
  <form method = "POST">
   <div class = "login">
    <p>Username: <input type = "text" name = "username"></p>
    <p>Password: <input type = "text" name = "password"> </p>
	  <p>Name: <input type='text' name='name'/></p>
	<p>	<br><img src=captcha.jpg></p>
	Enter the digits:<br><input type='text' name='captcha' placeholder='Type above Text'/>
		<br><input type='submit' value='Register' name = 'submit'/>     
   </div>
   <p id = "warn">Note:You will by default be a student. Your ascess level can only be raised by a administrator</p>
  </form>

</body>
</html>
