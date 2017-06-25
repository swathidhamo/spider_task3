<html>
<head>
  <style type="text/css">
   div{

    border: 3px solid green;
    padding-top: 5px;
    padding-bottom:  5px;
    margin-bottom: 5px;
    margin-top: 5px;
    background: #dce1ea;
   }

    .link{
    padding-left: 20px;
    padding-right: 20px;
    border: 2.5px solid red;
   }

    img{
      width: 100px;
      height: 100px;
      padding: 10px 10px 10px 10px;
    }
    body{

     background: #0ca3d2;
     padding-left: 15px;
     padding-right: 15px;
   }

   .ascess{
    border: 2px solid black;
    margin-right: 200px;
    width: 300px;
    padding-left: 30px;
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
   #intro{
    border: 1px solid black;
    padding-top: 20px;
    padding-bottom: 20px;
    width: 500px;
    padding-left: 20px;
   }
   textarea{
    width: 360px; 
    height: 200px;
   }
   #priority{
    color: #340000;
   }


   table {
    border-collapse: collapse;
   }

  table, tr, td {
    border: 1px solid black;
    padding-left: 15px;
    padding-right: 10px;
  }

  </style>
	<title>The forum</title>
	<?php

	  $link = mysqli_connect("127.0.0.1", "root", "", "first_db");
     session_start();
     if($_SESSION["moderated"]==0){
      $user_ascess = "moderated user";
     }
     else{
      $user_ascess = "normal user";
     }
     echo "<p id = 'intro'> Welcome to the forum " .$_SESSION["username"] . " and you are a ".$user_ascess."</p>" ;
   
      if( !empty($_SESSION["username"]) && !empty($_SESSION["ascess_level"]) && $_SESSION["ascess_level"]==1){
      
      if(isset($_POST["new"])){
       
        if(isset($_POST["content0"])){
      	    $content = $_POST["content0"];
        }
        if(isset($_POST["title"])){
        	$title = $_POST["title"];
        }

          $image = $_FILES['image']['tmp_name'];
          $img = file_get_contents($image);

        if(isset($_POST["priority"])){
          $priority = $_POST["priority"];
        }  
        


     
        /* Here the database 'content' consists of the all the information that will be 
        displayed in the forum.
        But the database 'approval' will consists of the information that will have to 
        be approved by a non-moderated admin level member
        */
                 
       if($_SESSION["moderated"]==1){//if the user is not moderated the content will be directly added to the forum
    
      $sql = "INSERT INTO content (title, info, image, priority) VALUES (?,?,?,?)";
      $query = mysqli_prepare($link,$sql);
      mysqli_stmt_bind_param($query, "sssi",$title,$content,$img,$priority);
      mysqli_stmt_execute($query);

     }
      
    
    if($_SESSION["moderated"]==0){//if the user is moderated then the content is added into a database that will have to be approved by the admin
      if(isset($_POST["content0"]) && isset($_POST["title"])){
      $sql_approval = "INSERT INTO approval (title, info, image,priority) VALUES (?,? ,?,?)";
      $query_approval = mysqli_prepare($link,$sql_approval);
      mysqli_stmt_bind_param($query_approval,"sssi",$title, $content,$img,$priority);
      mysqli_stmt_execute($query_approval);
      if($query_approval){
        echo "Please wait while you post is pending for approval";
      }
     }
   }
  }


    //this is to display the contents of the forum for all the members 
   $result=mysqli_query($link,"SELECT id, title, info, image, priority FROM content");
    if(isset($_POST["sort"])){
        $result=mysqli_query($link,"SELECT id, title, info, image, priority FROM content ORDER BY priority ASC");
        echo "Sorted according to priority";
    }
     
   echo "<table><tr><td>No.</td><td>Title</td><td>Info</td><td>Priority</td><td>Image</td><td>Edit/Delete</td>";
   while($query2=mysqli_fetch_array($result)){

       $priority_rows = $query2['priority'];
          if($priority_rows==0){
            $status = "Low";
          }
          else if($priority_rows==1){
            $status = "Medium";
          }
          else if($priority_rows==2){
            $status = "High";
          }


        $image_data = $query2['image'];
        $encoded = base64_encode($image_data);
        echo "<tr><td>".$query2['id']."</td>";
        echo "<td>".$query2['title']."</td>";
        echo "<td>".$query2['info']."</td>";
        echo "<td id = 'priority'>".$status."</td>";
        echo "<td><img src='data:image/jpeg;base64,$encoded'/></td>";
        echo "<td><a href= 'edite.php?id=".$query2['id']."'>Edit/Delete</a></td><tr>";      
        
      }
    //to elevate the ascess level of a user
    if(isset($_POST["elevate"])){
       
         if(isset($_POST['ascess']) && isset($_POST["level"])){
           $ascess = $_POST['ascess'];
           $user_level = $_POST["level"];
            if($user_level==1){
             $value = 1;//for a professor
             if(isset($_POST["moderated"])){
              $moderated_user = 0;//moderated user has value of 1
             }
             else{
              $moderated_user = 1;
             }
            }
            else if($user_level==2){
              $value = 2;//corresponds to a CR
              if(isset($_POST["moderated"])){
               $moderated_user = 0;//moderated user has value of 1
              }
              else{
               $moderated_user = 1;
              }

            }
            else if($user_level==0){
              $value = 0;//corresponds to a student
              if(isset($_POST["moderated"])){
              $moderated_user = 0;//moderated user has value of 1
             }
      
            }

           $ascess_query  = "UPDATE user_info SET ascess_level = ?, moderate_status = ? WHERE username = '".$ascess."' ";
           $result_q = mysqli_prepare($link,$ascess_query);
           mysqli_stmt_bind_param($result_q,"ii",$value,$moderated_user);
           $ascess_change = mysqli_stmt_execute($result_q);
           $_SESSION["moderated"] = $moderated_user;
          // $result_q = mysqli_query($link, $ascess_query);

           if($ascess_change){
            echo "ascess changed";
           }
         }

       }


         

  

}

     

     else {
      header("Location: connect.php");
     }


   /*  if(isset($_POST["approval"])){

          echo mysqli_error($link);
          header("Location: approve.php");
         }
*/
     //to direct a non moderated admin ascess level member to the approval database

       
  ?>

</table>



</head>
<body>
   
   <form method = "POST" enctype="multipart/form-data" >
    <div id = "new_note">
      <p>Title: <input type = "text" name = "title" placeholder = "Enter the title"></p>
   	  <p>Content: <textarea name = "content0" id = "content0" width = "650" height = "650" placeholder = "Enter the content of the note"></textarea></p>
      <p><input type="file" name="image" /></p>      
      <p><select name = "priority">
        <option value = "0">Low</option>
        <option value = "1">Medium</option>
        <option value = "2">High</option>
      </select></p>
      <p><input type = "submit" name = "new" value = "New"></p>
      <p><a href="approve.php">Approvals pending</a></p>
    </div>
     
     <div class = "ascess"> 
      <select name = "level">
       <option value = "2">CR</option> 
        <option value = "1">Professor</option> 
        <option value = "0">Student</option> 
      </select>
        Moderated <input type = "radio" name = "moderated">
       <input type = "submit" name = "elevate" value = "change">     
       <input type = "text" name = "ascess" placeholder = "Enter the username">

    </div>
    <input name = "sort" type = "submit" value = "Sort by priority">
  

   </form>

   <a href = "logout.php" class = "link">Logout</a>

 

   </script>


</body>
</html>