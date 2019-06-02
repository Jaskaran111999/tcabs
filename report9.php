<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
  header('Location: login.php');
  exit();
} else {
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // After the form is submitted -> add code here
  }
}
?>

<!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8" />
          <meta http-equiv="x-ua-compatible" content="ie=edge" />
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Stylesheets -->
      		 <?php include "views/stylesheet.php"; ?>
        </head>

        <body class="loggedin">
      		<?php include "views/header.php"; ?>

      <div class="content">

      			<h2>Available reports to generate</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
      		<div>
      		<?php foreach($_SESSION['roles'] as $key => $value){
      							if($key=='admin') {
      		?>
    <div class="btn-group btn-group-justified">
      <a href="report.php" class="btn btn-primary">Overview</a>
      <a href="report1.php" class="btn btn-primary">1</a>
      <a href="report2.php" class="btn btn-primary">2</a>
      <a href="report3.php" class="btn btn-primary">3</a>
      <a href="report4.php" class="btn btn-primary">4</a>
      <a href="report5.php" class="btn btn-primary">5</a>
      <a href="report6.php" class="btn btn-primary">6</a>
      <a href="report7.php" class="btn btn-primary">7</a>
      <a href="report8.php" class="btn btn-primary">8</a>
      <a href="report9.php" class="btn btn-primary">9</a>
      <a href="report10.php" class="btn btn-primary">10</a>
    </div>
    <br>

    <p class="h4 mb-4 text-center">Unit Overview, showing current state of budget for each team in the unit</p>

    <body>
        <form action="report9.php" method="post">
    </body>
  <?php } else { ?>
  <h2>Admin Function</h2>
  <div>
  <p>Sorry, you do not have access to this function</p>
  </div>
<?php } } ?>
</div>

</div>

</body>
<?php include "views/footer.php"; ?>
</html>
