<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
	} else {

		// check user permission to access the page(admin)
		if(!$_SESSION['loggedUser']->uRoles['convenor']) {
			header('Location: /tcabs/dashboard.php');
		} else {
			// print_r($_POST);

			// --------------------------  Data to populate forms --------------------------------
			$n_unitOffering = new Team;
			try {

				// returns a multidimensional array of results
				$unitOfferingAvailable = $n_unitOffering->getAllTeams();

			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
			}



			// if there was a post request
			if($_SERVER['REQUEST_METHOD'] == 'POST') {


				// if button presses with name attribute = submit
				if(isset($_POST['submit'])) {


					// Edit Existing Data
					if($_POST['submit'] === 'editData') {
						echo "hello";
					$n = new TeamMember;
					$teamMembers = $_POST['teamMembers'];

					// Get the possible team members for the unit and delete if they are part of the team
					$searchQuery = $_POST['uOffID'];
					$query = mysqli_query($conn, "SELECT enrolment.enrolmentID, unitoffering.unitOfferingID, unit.unitcode, unit.unitName, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email
					from enrolment
												left join unitoffering on unitoffering.unitOfferingID = enrolment.unitOfferingID
												left join unit on unitoffering.unitCode = unit.unitCode
												left join users on enrolment.sUserName = users.email
					left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year
					where unitoffering.unitofferingID = '$searchQuery'
					order by teachingperiod.year desc ");
					$searchResults = mysqli_fetch_all($query, MYSQLI_ASSOC);


					foreach($searchResults as $key => $value) {
						try {
							echo "delete" . $value['email'];
							$n->deleteTeamMember(
								$value['email'],
								$_POST['teamName'],
								$_POST['supEmail'],
								$_POST['unitCode'],
								$_POST['term'],
								$_POST['year']

							);
							echo "<script type='text/javascript'>alert('delete successfully!');</script>";
						} catch(mysqli_sql_exception $e) {
						echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					}





					foreach($teamMembers as $key => $value) {
						print_r($value);

						try {
							$n->addTeamMember(
								$value,
								$_POST['teamName'],
								$_POST['supEmail'],
								$_POST['unitCode'],
								$_POST['term'],
								$_POST['year']

							);
							echo "<script type='text/javascript'>alert('Added successfully!');</script>";
							} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}

					}
					}

					// Adding new data
					if($_POST['submit'] === "addData") {

							$selectedUnitOffering = $n_unitOffering->searchTeam($_POST['unitOfferingID']);
							print_r($selectedUnitOffering);
							$newData = new Project;


							try {
								foreach($selectedUnitOffering as $key => $value) {
								$newData->addProject(
									$_POST['teamName'],
									$_POST['teamDesc']
								//	$value['unitCode'],
								//	$value['term'],
								//	$value['year']
								);
							}
							echo "<script type='text/javascript'>alert('Added successfully!');</script>";
							} catch(mysqli_sql_exception $e) {
								echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}

					// if search is pressed
					 else if($_POST['submit'] === "search") {

						$n = new Team;
						try {

							// returns a multidimensional array for each user found
							$searchResults = $n->searchTeam("%{$_POST['searchQuery']}%");

						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}

					}

				}

				else 	if(isset($_POST['update'])) {
						//something here ---------------------
						// echo "update " . $_POST['update'];

					// Delete Data
					} else 	if(isset($_POST['delete'])) {
						$editingData = new Team;
						$editsearchResults = $editingData->searchTeam($_POST['delete']);
						print_r($editsearchResults);

						$deleteData = new Team;

						try {
						foreach($editsearchResults as $key => $value) {
						$deleteData->deleteTeam(
							$value['teamName'],
							$value['uName'],
							$value['unitCode'],
							$value['term'],
							$value['year']
						);
					}
						echo "<script type='text/javascript'>alert('Added successfully!');</script>";
						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}



						}
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Manage Projects - TCABS</title>
		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
  </head>

   <body class="loggedin">
		<?php include "../views/header.php"; ?>
		<div class="content">
			<h2>Manage Projects</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>

		<!-- Nav tabs -->
		<?php
		//show tabs if not in update mode
				if(!isset($_POST['update'])) {
			?>
		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addData') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
			</li>

			<li class="nav-item">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Update/Delete</a>
			</li>
		</ul>
		<?php  } ?>

		<!-- Tab panes -->
		<div class="tab-content">

			<!-- Edit Data only when Update button pressed -->
			<?php
				if(isset($_POST['update'])) {
				$editingData = new Team;
				$editsearchResults = $editingData->searchTeam($_POST['update']);





				if ($editsearchResults == NULL) {
				echo "<script type='text/javascript'>alert('Oops nothing found!');</script>";
				} else {
				foreach($editsearchResults as $key => $value) {
			?>
			<div>
			<form method="post" class="was-validated"><br>
  		  <p class="h4 mb-4 text-center">Edit Team (<?php echo ($_POST['update']); ?>)</p>
								<input type="text" id="uOffID" name="uOffID" class="form-control" value="<?php echo $value['uOffID'];?>" readonly /><br>
		  						<input type="text" id="teamName" name="teamName" class="form-control" value="<?php echo $value['teamName'];?>" readonly /><br>
									<input type="text" id="supEmail" name="supEmail" class="form-control" value="<?php echo $value['uName'];?>" readonly /><br>
									<input type="text" id="unitCode" name="unitCode" class="form-control" value="<?php echo $value['unitCode'];?>" readonly /><br>
									<input type="text" id="term" name="term" class="form-control" value="<?php echo $value['term'];?>" readonly /><br>
									<input type="text" id="year" name="year" class="form-control" value="<?php echo $value['year'];?>" readonly /><br><br>

									<p class="p mb-4 text-center">Select Team Members</p>

									<select class="browser-default custom-select" id="teamMembers" name="teamMembers[]" multiple="multiple" size="5" required>
										<option hidden value="" disabled="" selected="">Select Team Members</option>

									<!-- Populate based on units table here -->

									<?php

									// Get the possible team members for the unit
									$searchQuery = $value['uOffID'];
									$query = mysqli_query($conn, "SELECT enrolment.enrolmentID, unitoffering.unitOfferingID, unit.unitcode, unit.unitName, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email
									from enrolment
																left join unitoffering on unitoffering.unitOfferingID = enrolment.unitOfferingID
																left join unit on unitoffering.unitCode = unit.unitCode
																left join users on enrolment.sUserName = users.email
									left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year
									where unitoffering.unitofferingID = '$searchQuery'
									order by teachingperiod.year desc ");
									$searchResults = mysqli_fetch_all($query, MYSQLI_ASSOC);



									foreach($searchResults as $key => $value) {
									$unitName = $value['fName'] . " " . $value['lName'];
									?>
									<option value="<?php echo $value['email']; ?>"> <?php echo $unitName; ?> </option>
									<?php } ?>


									</select>



  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="editData">Edit</button>
			</form>
			</div>
		<?php  } } }?>


		<!-- Tab 1 -->
  	<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addData') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
			<form method ="post" class="was-validated"><br>
				<p class="h4 mb-4 text-center">Add Project</p>
				<input type="text" id="teamName" name="teamName" class="form-control" placeholder="Project Name" required /><br>
				<input type="text" id="teamDesc" name="teamDesc" class="form-control" placeholder="Project Description" required /><br>


				<select class="browser-default custom-select" id="unitOfferingID" name="unitOfferingID" required>
					<option value="" disabled="" selected="">Select Team / Unit</option>

				<!-- Populate based on units table here -->

				<?php
				foreach($unitOfferingAvailable as $key => $value) {
				$unitName = $value['teamName'] . " - "  . $value['teamID'] . " - " . $value['uName'] . " - " . $value['year'] . " - " . $value['term'] . " - " . $value['unitCode'];
				?>
				<option value="<?php echo $value['teamID']; ?>"> <?php echo $unitName; ?></option>
			<?php }?>


				</select>
				<br>

				<br><br>
				<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addData">Add</button>
			</form>
		</div>



		<!-- Tab 3 -->
		<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active show';} ?>" id="menu2">
			<form method ="post" class="was-validated"><br>
  	  	<p class="h4 mb-4 text-center">Update/Delete Team</p>
				<div class="search-box">
  <input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Team ID or Team Name" required>

    		<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
				</div>
			</form><br>

			<!-- Show Search Results -->
			<?php
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
					//print_r($searchResults);
			?>

			<div>
				<form method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 30%;">Team Name</th>
						<th style="width: 20%;">Unit Code</th>
						<th style="width: 10%;">Term</th>
    					<th style="width: 10%;">Year</th>
							<th style="width: 10%;"></th>
							<th style="width: 10%;"></th>
    				</tr>

						<?php
							if ($searchResults == NULL) {
							echo "<script type='text/javascript'>alert('Oops nothing found!');</script>";
							} else {
							foreach($searchResults as $key => $value) {
								// $name = $value['fName'] . " " . $value['lName'];
								// $email = $value['email'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $value['teamName'];?></td>
							<td><?php echo $value['unitCode'];?></td>
							<td><?php echo $value['term'];?></td>
							<td><?php echo $value['year'];?></td>
							<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $value['teamID'];?>" >Update</button></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $value['teamID'];?>" >Delete</button></td>

						</tr>

							<?php  }}?>

					</table>
				</form><br>
			</div>
			<?php  }?>

			</div>
		</div>
		</div>
		</div>
	</body>
  <?php include "../views/footer.php";  ?>
</html>
