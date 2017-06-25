<html>
<head>
	<title>Posts Pending Approvals</title>

	<?php
    
	  $link = mysqli_connect("127.0.0.1", "root", "", "first_db");
      session_start();
      //to check if the user is a non moderated user who has logged in and is a professor
       if(!empty($_SESSION["username"]) && !empty($_SESSION["ascess_level"]) && $_SESSION["moderated"]==1 && $_SESSION["ascess_level"]==1){
        $query = "SELECT id, title, info, image,priority FROM approval";
        $result = mysqli_query($link,$query);
        $rows = mysqli_num_rows($result);

        if($rows==0){
          echo "You have no requests pending";
        }
        // to display the posts pending approval from the table 'approval'
        while($array = mysqli_fetch_array($result)){
          $priority_rows = $array['priority'];
          if($priority_rows==0){
            $status = "Low";
          }
          else if($priority_rows==1){
            $status = "Medium";
          }
          else if($priority_rows==2){
            $status = "High";
          }


          $image_encoded = base64_encode($array["image"]);
           echo  "<div>Note  ".$array["id"]. "<p> Title:     " . $array["title"]."</p> <p>Info:   " .$array['info']."</p><p>".$status.
           "</p><p><img src='data:image/jpeg;base64,$image_encoded'/></p><br></div>";
        }
        
        if(isset($_POST["id"]) ){
          $id = ($_POST["id"]);
          
          
          $query_approve = "SELECT title, info, image, priority FROM approval WHERE id = '" .$id. "'";
          $result_approve = mysqli_query($link,$query_approve);
          $array_approve = mysqli_fetch_array($result_approve);
          $title = $array_approve["title"];
          $info = $array_approve["info"];
          $image_content = $array_approve["image"];
          $priority =  $array_approve["priority"];
         // $img = base64_decode($image_content);
         //if the post is approved to insert that into the content table and remove it from the approval table
          if(isset($_POST["approve"])){
            $query_append = "INSERT INTO content (title, info, image, priority) VALUES ( ? , ? , ?, ? )";
            $append_result = mysqli_prepare($link,$query_append);
            mysqli_stmt_bind_param($append_result,"sssi",$title,$info,$image_content,$priority);
            mysqli_stmt_execute($append_result);

           
            if($append_result){
              echo "Sucessfully approved";
              $query_reject = "DELETE FROM approval WHERE id = '" .$id. "' ";
              $reject_result = mysqli_query($link,$query_reject);
            }
          }
          //if rejected the post is deleted from the approval table
          else if(isset($_POST['reject'])){
            $query_reject = "DELETE FROM approval WHERE id = '".$id."' ";
            $reject_result = mysqli_query($link,$query_reject);
            if($reject_result){
              echo "Sucessfully rejected";
            }

          }
          


        }

       }
        //if a moderated user tries to ascess this
       else if($_SESSION["moderated"]==0 || empty($_SESSION["username"]) || ($_SESSION["ascess_level"])!=1 ) {
        header("Location: forum.php");
       }

	?>
  <style type="text/css">
    .link{
    padding-left: 20px;
    padding-right: 20px;
    border: 2.5px solid red;
   }
   img{
    width: 50px;
    height: 50px;
   }
   body{
     background: #0ca3d2;
   }
  </style>
</head>
<body>
  <form method = "POST">
  <input type = "text" name = "id" placeholder = "enter the note number">
  <input type = "submit" name = "approve" value = "Approve">
  <input type = "submit" name = "reject" value = "Reject">
  <a href = "logout.php" class = "link">Logout</a>
  <a href = "forum.php">Forum</a>
  </form>
</body>
</html>