<!-- The main page of the system will show relevant functionality according to user role -->
<?php
	require_once("classes.php");
	session_start();

	if(!isset($_SESSION['logged_in'])) {
		header("location: /tcabs/login.php");
	}

	header("location: /tcabs/dashboard.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "styles/stylesheet.php"; ?>
  </head>

  <body class="loggedin">
		<?php include "views/header.php"; ?>

		<div class="content">
		</div>
  </body>
  <?php include "views/footer.php"; ?>
</html>
