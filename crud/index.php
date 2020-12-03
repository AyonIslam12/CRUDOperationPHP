<?php
require_once "inc/functions.php";
$info = '';
$task = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';
$msg = $_GET['msg'] ?? '0';
$editMsg = $_GET['editMsg'] ?? '0';
$deleteMsg = $_GET['deleteMsg'] ?? '0';
if ('delete' == $task) {
	$id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_STRING);
	if ($id>0) {
		deleteStudent($id);
		header('location:../crud/index.php?task=report&deleteMsg=1');
	}
}
if ('seed' == $task) {
	seed();
	$info = "Seeding is complete";
}
//add data
$fname = '';
$lname = '';
$roll = '';
$class = '';
if (isset($_POST['submit'])) {
	
	$fname = htmlspecialchars($_POST['fname']);
	$lname = htmlspecialchars($_POST['lname']);
	$roll = htmlspecialchars($_POST['roll']);
	$class = htmlspecialchars($_POST['class']);
	$id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_STRING);
	
	if ($id) {
		//update the existing student
		if ($fname != '' && $lname != '' && $roll != '' && $class != ''){
			$result = updateStudent($id,$fname,$lname,$roll,$class);
			if ($result) {
				header('location:../crud/index.php?task=report&editMsg=1');
			}else{
				$error = 1;
			}
		}
	}else{
		//add new student data
		if ($fname != '' && $lname != '' && $roll != '' && $class != '') {
			$result = addStudents($fname,$lname,$roll,$class);
			if ($result) {
				header('location:../crud/index.php?task=report&msg=1');
			}else{
				$error = 1;
			}
	}

	}
		
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width= devices-width,initial-scale=1">
	<title>CRUD project</title>
	<!--google font-->
	<link href="https://fonts.googleapis.com/css2?family=Handlee&family=Montserrat&family=Roboto&display=swap" rel="stylesheet">
	<!--fontwase-->
	<script src="https://kit.fontawesome.com/c4f7856497.js" crossorigin="anonymous"></script>
	<!--Bootstrap-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	
</head>
<body>
	<div class="container-fluid bg-info">
		<div class="row ">
			<div class="col-6 offset-3 bg-dark mt-3 ">
				<h2 class="mt-4 text-center text-white">Project- CRUD Operation</h2>
				<p class="text-light text-left">A simple project to perform CRUD operations using plain files and PHP</p>
				<?php include_once('inc/templates/nav.php'); ?>

				<h2 class="text-success text-bolder py-4 text-left fa-1x"></i><?php
				if ($info != '') {
					echo "<p>{$info}</p>";
				}
				?></h2>
			</div>
		</div>
		<!--Error Check Msg(AddData)-->
		<?php if ('1' == $error) : ?>
			<div class="row">
				<div class="col-6 offset-3 bg-warning  ">
					<p class="text-dark text-center py-3">
						<i class="fas fa-exclamation-triangle fa-2x text-danger"></i> Please try again,roll number should not be duplicate!...
					</p>
				</div>
			</div>
		<?php endif; ?>
		<!--Success Check Msg(AddData)-->
		<?php if ('1' == $msg) : ?>
			<div class="row">
				<div class="col-6 offset-3 bg-light  ">
					<p class="text-dark text-center py-3">
						<i class="far fa-check-circle text-success "></i>
						Data is successfully added!!!.
					</p>
				</div>
			</div>
		<?php endif; ?>
	<!--Success Check Msg(EditData)-->
		<?php if ('1' == $editMsg) : ?>
			<div class="row">
				<div class="col-6 offset-3 bg-light  ">
					<p class="text-dark text-center py-3">
						<i class="far fa-check-circle text-success "></i>
						Data is successfully Update!!!.
					</p>
				</div>
			</div>
		<?php endif; ?>
		<!--Success Msg(DeleteData)-->
		<?php if ('1' == $deleteMsg) : ?>
			<div class="row">
				<div class="col-6 offset-3 bg-light  ">
					<p class="text-dark text-center py-3">
						<i class="far fa-check-circle text-success "></i>
						Data is successfully deleted!!!.
					</p>
				</div>
			</div>
		<?php endif; ?>

		<!-- CURD OPERATIOM Report(Read)-->
		<?php if ('report' == $task) : ?>
			<div class="row">
				<div class="col-6 offset-3 bg-dark py-4 mb-3 ">
					<?php generateReport(); ?>
					
				</div>
			</div>
		<?php endif; ?>
		<!-- (AddDataform)-->
		<?php if ('add' == $task) : ?>
			<div class="row">
				<div class="col-6 offset-3 bg-white text-dark py-4 mb-3 ">
					<form action="../crud/index.php?task=add" method="POST">
						<div class="form-group">
							<label for="fname">First Name</label>
							<input type="text" name="fname" id="fname" value="<?php echo $fname; ?>" class="form-control">
							<label for="lname">Last Name</label>
							<input type="text" name="lname" id="lname" value="<?php echo $lname; ?>" class="form-control">
							<label for="roll">Roll</label>
							<input type="number" name="roll" id="roll" value="<?php echo $roll; ?>" class="form-control">
							<label for="class">Class</label>
							<input type="text" name="class" id="class" value="<?php echo $class; ?>" class="form-control">
							<button type="submit" name="submit" class="btn-success my-3 text-light rounded pl-3 pr-3 pt-2 pb-2">Save</button>
							
						</div>
						
					</form>
				</div>
			</div>
		<?php endif; ?>
		<!-- (EditDataForm)-->
		<?php if ('edit' == $task) : 
			$id = filter_input(INPUT_GET, 'id',FILTER_SANITIZE_STRING);
			$student = getStudent($id);
			if($student) :
			?>
			<div class="row">
				<div class="col-6 offset-3 bg-white text-dark py-4 mb-3 ">
					<form action="" method="POST">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<div class="form-group">
							<label for="fname">First Name</label>
							<input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>" class="form-control">
							<label for="lname">Last Name</label>
							<input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>" class="form-control">
							<label for="roll">Roll</label>
							<input type="number" name="roll" id="roll" value="<?php echo $student['roll']; ?>" class="form-control">
							<label for="class">Class</label>
							<input type="text" name="class" id="class" value="<?php echo $student['class']; ?>" class="form-control">
							<button type="submit" name="submit" class="btn-success my-3 text-light rounded pl-3 pr-3 pt-2 pb-2">Update</button>
							
						</div>
						
					</form>
				</div>
			</div>
		<?php 
	endif;
	endif;
	 ?>
		
		
	</div>
	
	<script type="text/javascript" src="assets/js/scripts.js"></script>
	<!--Bootstrap js,proper-->
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>