<?php
define("DB_NAME", "c:\\xampp\\htdocs\\RawPhp\\crud\\data\\db.txt");
function seed(){
	$data = array(
		array(
			'id' => 1,
			'fname'=> 'Mehedi',
			'lname' => 'Hasan',
			'roll' => 1,
			'class' => 'BCSE',

		),
		array(
			'id' => 2,
			'fname'=> 'Sopon',
			'lname' => 'Khan',
			'roll' => 2,
			'class' => 'BCSE',

		),
		array(
			'id' => 3,
			'fname'=> 'Mumu',
			'lname' => 'Khan',
			'roll' => 3,
			'class' => 'BCSE',

		),
		array(
			'id' => 4,
			'fname'=> 'Muna',
			'lname' => 'Khan',
			'roll' => 4,
			'class' => 'BCSE',

		),
		array(
			'id' => 5,
			'fname'=> 'Azharul',
			'lname' => 'Islam',
			'roll' => 5,
			'class' => 'BCSE',

		),


	);
	$serializedData = serialize($data);
	file_put_contents(DB_NAME, $serializedData,LOCK_EX);
}

//Report
function generateReport(){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title></title>
		<style>
			.crudbutton a{
				text-decoration: none;
			}

		</style>
	</head>
	<body>
		<table class="table text-white">
			<thead>
				<tr>

					<th scope="col">Id</th>
					<th scope="col">Name</th>
					<th scope="col">Roll</th>
					<th scope="col">Class</th>
					<th class="text-center" scope="col">Action</th>
				</tr>
			</thead>
			<?php 
			foreach ($students as $student) {
				?>
				<tbody>
					<tr>
						<td><?php printf('%s',$student['id']); ?></td>
						<td><?php printf('%s %s',$student['fname'],$student['lname']); ?></td>
						<td><?php printf('%s',$student['roll']); ?></td>
						<td><?php printf('%s',$student['class']); ?></td>
						<td class="crudbutton text-center"><?php printf('<a href="../crud/index.php?task=edit&id=%s"><i class="fas fa-edit text-success"></i></a> | <a class = "delete" href="../crud/index.php?task=delete&id=%s"><i class="fas fa-trash text-danger"></i></a>',$student['id'],$student['id']) ?></td>

					</tr>

				</tbody>
				<?php
			}
			?>

		</table>

	</body>
	</html>
	<?php

}
//addfunction
function addStudents($fname,$lname,$roll,$class){
	$found = false;
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	foreach ($students as $_student) {
		if ($_student['roll'] == $roll) {
			$found = true;
			break;
		}
	}
	if (!$found) {	
		$newId = getNewId($students);
		$student = array(
			'id' => $newId,
			'fname'=> $fname,
			'lname' => $lname,
			'roll' => $roll,
			'class' => $class
		);
		array_push($students, $student);
		$serializedData = serialize($students);
		file_put_contents(DB_NAME, $serializedData,LOCK_EX);
		return true;
	}
	return false;


}
//getStudent function
function getStudent($id){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	foreach ($students as $student) {
		if ($student['id'] == $id) {
			return $student;
		}
	}
	return false;

}
//update Student function
function updateStudent($id,$fname,$lname,$roll,$class){
	$found = false;
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	foreach ($students as $_student) {
		if ($_student['roll'] == $roll && $_student['id'] != $id) {
			$found = true;
			break;
		}
	}
	if (!$found) {
		

		$students[$id-1]['fname'] = $fname;
		$students[$id-1]['lname'] = $lname;
		$students[$id-1]['roll'] = $roll;
		$students[$id-1]['class'] = $class;
		$serializedData = serialize($students);
		file_put_contents(DB_NAME, $serializedData,LOCK_EX);
		return true;
	}
	return false;
}
//DeleteFunction
function deleteStudent($id){
	$serializedData = file_get_contents(DB_NAME);
	$students = unserialize($serializedData);
	unset($students[$id-1]);
	$serializedData = serialize($students);
	file_put_contents(DB_NAME, $serializedData,LOCK_EX);

}

function getNewId($students){
	$maxId = max(array_column($students, 'id'));
	return  $maxId+1;
}