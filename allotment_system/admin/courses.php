<!--admin religion.php  -->
<?php 
session_start();
require_once '../lib/functions.php';
require_once '../lib/db_class.php';
$db = new Database();
//if wrong user try to access this page then redirect to the home page by destroying session.
if(!is_logged_in() || get_user_type()!='admin' ){
	logout();
}

//If logout is set then do logout
if(isset($_GET['logout'])){
	logout();
}


//add new course
if(isset($_POST['addCourse'])){
	$db->query('INSERT INTO courses (
		name,
		seats
		)
	VALUES (
		"'.$_POST["name"].'",
		"'.$_POST["seats"].'"
		)');
	unset($_POST);
}




//delete a course.
if(isset($_POST['deleteCourse'])){
	$db->query('DELETE FROM courses WHERE course_id="'.$_POST['course_id'].'"');
	$db->query('DELETE FROM course_dependencies WHERE course_id="'.$_POST['course_id'].'"');
	unset($_POST);

	//delete course dependencies
}


//update course
if(isset($_POST['updateCourse'])){
	$db->query('UPDATE courses SET name = "'.$_POST['name'].'",
		seats = "'.$_POST['seats'].'"
		WHERE course_id = "'.$_POST["course_id"].'"');
	echo "<script>alert(' successfully updated course : ".$_POST['name']."')</script>";
	unset($_POST);

}

//add new course depencdencies
if(isset($_POST['addCourseDependency'])){
	$db->query('INSERT INTO course_dependencies (
		dependency,
		course_id
		)
	VALUES (
		"'.$_POST["dependency"].'",
		"'.$_POST["course_id"].'"
		)');
	unset($_POST);
}


//delete course dependency
if(isset($_POST['deleteCourseDependency'])){
	$db->query('DELETE FROM course_dependencies WHERE course_id="'.$_POST['course_id'].'" AND dependency = "'.$_POST['old_dependency'].'"');
	unset($_POST);
}

//update course dependency
if(isset($_POST['updateCourseDependency'])){
		$db->query('UPDATE course_dependencies SET dependency = "'.$_POST['dependency'].'"
		WHERE course_id = "'.$_POST["course_id"].'" AND dependency = "'.$_POST['old_dependency'].'"' );
			unset($_POST);
				echo "<script>alert(' updated course  dependency')</script>";
}



//list courses
$results =  $db->query('SELECT * FROM courses');
$course_count = mysqli_num_rows($results);
if($course_count!=0){
	$i = 0;
	while($row=mysqli_fetch_array($results,MYSQLI_ASSOC)){
		$courses[$i] = $row;
		$course_dependencies =  $db->query('SELECT dependency FROM course_dependencies WHERE course_id='.$row["course_id"]);
		$courses[$i]['course_dependency_count'] = mysqli_num_rows($course_dependencies);
		while ($course_dependency=mysqli_fetch_array($course_dependencies,MYSQLI_ASSOC) ){
			$courses[$i]['course_dependencies'][] = $course_dependency['dependency'];
		}
		$i++;
	}
}



// print_r($courses);

include '../templates/admin/header.php';
?>


<div class="container main-content">


	<div class="content-full" >

		<div class="add-table">
			<h3>Add new course</h3>


			<form action="courses.php" method="post">
				<table cellspacing="5px" class="religion-add-table">
					<tbody>
						<th>course name</th>
						<th>Seats</th>
						<th></th>
						<tr>
							<td><input type="text" name="name"></td>
							<td><input type="text" name="seats"></td>
							<td><input class="btn btn-success add-religion-btn"  type="submit" name="addCourse" value="add"></td>
						</tr>
					</tbody>
				</table>
			</form>

		</div>



		<div class="edit-courses">
			<h4 class="heading"> Courses ( <?php echo (isset($course_count) ? $course_count : 0 ); ?> )</h4>
			<ul class="courses">

				<?php
				foreach ($courses as $course) {
					?>
					<li class="course">
						<form action="courses.php" method="post">
							<input type="hidden" name="course_id" value="<?=$course['course_id']?>">
							<label for="">course name</label>
							<input type="text" style="width:100px;"  name="name" value="<?=$course['name'] ?>">
							<label for="">seats</label>
							<input type="text" style="width:30px;" name="seats" value="<?=$course['seats'] ?>">
							<button type="submit" name="deleteCourse" value="yes" class="religion-edit-btn btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
							<button type="submit" name="updateCourse" value="yes" class="religion-edit-btn btn btn-primary"><i class="glyphicon glyphicon-open"></i></button>
						</form>
						<label class="courseDependencyLink"  for=""><a href="">course dependencies(<?php echo (isset($course['course_dependency_count']) ? $course['course_dependency_count'] : 0 ); ?>)</a></label>

						<ul class="courseDependencies" style="display:none;" >
						
								<form class="dependency-add-form" action="courses.php" method="post">
									<input type="hidden" name="course_id" value="<?=$course['course_id']?>">
									<input type="text" name="dependency">
									<button type="submit" name="addCourseDependency" value="yes" class="religion-edit-btn btn btn-success">
										<i class="glyphicon glyphicon-plus"></i></button>
									</form>							
							
								<?php  if(isset($course['course_dependencies'])) foreach ($course['course_dependencies'] as $dependency) {
									?>
									<li>
										<form class="dependency-edit-form" action="courses.php" method="post">
											<input type="hidden" name="old_dependency" value="<?=(isset($dependency) ? $dependency : '' )?>">
											<input type="hidden" name="course_id" value="<?=$course['course_id']?>">
											<input type="text" name="dependency" value="<?php echo (isset($dependency) ? $dependency : '' ); ?>">
											<button type="submit" name="deleteCourseDependency" value="yes" class="religion-edit-btn btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
											<button type="submit" name="updateCourseDependency" value="yes" class="religion-edit-btn btn btn-primary"><i class="glyphicon glyphicon-open"></i></button>

										</form>
									</li>

									<?php } ?>


								</ul>

							</li>



							<?php } ?>

						</ul>

					</div>
					<script type="text/javascript" src="<?php echo BASE_URL.'/js/jquery.js'; ?>"></script>
					<script type="text/javascript" src="<?php echo BASE_URL.'/js/courses.js'; ?>"></script>
					<?php include '../templates/admin/footer.php';?>