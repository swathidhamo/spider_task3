<html>
<head>

  <style type="text/css">
   div{

    border: 3px solid green;
    padding-top: 5px;
    margin-top: 5px;
    padding-bottom:  5px;
    margin-bottom: 5px;
    padding-right: 100px;
    width: 350px;
    margin-left: 100px;
    padding-left: 30px;
    background: #dce1ea;
   }
   .link{
    padding-left: 20px;
    padding-right: 20px;
    border: 2.5px solid red;
   }
   .priority{
    color: red;
   }

   p{
    font-style: bold;
   }

   body{
     background: #0ca3d2;
   }
   img{
    width: 50px;
    height: 50px;
   }

    #new_note{
    border: 2px solid black;
    width: 650px;
    height: 450px;
    padding-left: 30px;
   }
   #new_note: p{
    padding-top: 20px;
    padding-bottom: 20px;
    padding-left: 30px;
   }



  </style>
	<title>The forum</title>
  <?php
   $link = mysqli_connect("127.0.0.1", "root", "", "first_db");
   session_start();
   if($_SESSION['moderated'] ==0){
  
     echo "<p>Welcome to the forum " .$_SESSION["username"]." and you are a moderated editor". "</p>";
   }
     else{
      echo "<p>Welcome to the forum " .$_SESSION["username"]. "</p>";
    }

       //to check if the user has already logged in
     if( empty($_SESSION["username"]) ) {

      header("Location: connect.php");
      echo "You do not have the ascess level";
     }
 
     else {

      if($_SESSION["moderated"]==0){
         if(isset($_POST["new"])){
          if(isset($_POST["title"])){
            $title = mysqli_real_escape_string($link,$_POST["title"]);
            $title = stripslashes($title);
          }
          if(isset($_POST["content0"])){
                $content = mysqli_real_escape_string($link,$_POST["content0"]);
                $content = stripslashes($content);
          }

          
            $image = $_FILES['image']['tmp_name'];
            $img = file_get_contents($image);

            if(isset($_POST["priority"])){
              $priority = $_POST["priority"];
            }
     

          $query = "INSERT INTO approval (title, info, image,priority) VALUES (?,?,?,?)";
         
          $result = mysqli_prepare($link,$query);
          mysqli_stmt_bind_param($result,"sssi",$title,$content,$img,$priority);
          $result_q = mysqli_stmt_execute($result);
          if($result_q){
            echo "Please wait for the approval from admin";
          }


         } 
      }
      else{
        if(isset($_POST["new"])){
          echo "Warning you are not a moderated editor !!! So you cannot add content.";
        }
      }





      $display = "SELECT id,title, info,image, priority FROM content";
      if(isset($_POST["sort"])){
        $display = "SELECT id,title, info,image, priority FROM content ORDER BY priority ASC";
        echo "Sorted!";
      }
      $result = mysqli_query($link,$display);
      while($row = mysqli_fetch_assoc($result)) {
        $image_data = $row["image"];
        $image_encoded = base64_encode($image_data);
        $priority_rows = $row["priority"];
        if($priority_rows==0){
            $status = "Low";
          }
          else if($priority_rows==1){
            $status = "Medium";
          }
          else if($priority_rows==2){
            $status = "High";
          }



        echo  "<div>Note  ".$row["id"]. "<p> Title:     " . $row["title"]."</p> <p>Info:   " . $row["info"]. "<p class = 'priority'> Priority:  " .$status. "</p>" ."<p><img src='data:image/jpeg;base64,$image_encoded'/></p>" ."</p><br></div>";
    }

  
   }
     

  ?>
</head>
<body>
   
  <form method = "POST" enctype="multipart/form-data"  >
   <input type = "submit" value = "Sort by priority" name = "sort">
     <div id = "new_note">
      <p><input type = "text" name = "title" placeholder = "Enter the title"></p>
      <p><textarea name = "content0" id = "content0" width = "200" height = "200" placeholder = "Enter the contents"></textarea></p>
      <p><input type = "submit" name = "new" value = "new"></p>
      <p><input type="file" name="image" /></p>
       <p><select name = "priority">
        <option value = "0">Low</option>
        <option value = "1">Medium</option>
        <option value = "2">High</option>
      </select></p>
    </div>
  </form>
   <a href = "logout.php" class = "link">Logout</a>

</body>
</html>