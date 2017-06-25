<html>
<head>
	<title>Logout</title>
	<?php
   
    session_start();
    session_destroy();

    header("Location: connect.php");






	?>
</head>
<body>

</body>
</html>