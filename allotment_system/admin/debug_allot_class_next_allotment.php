<?php  
require_once '../lib/functions.php';
require_once '../lib/db_class.php';
require_once 'custom_stack.php';
global $db;

define('TOTAL_INDEX_MARGIN',$db->query('SELECT  value FROM settings WHERE label="TOTAL_INDEX_MARGIN"',TRUE,TRUE));
define('PREFERENCE_INDEX_MARGIN',$db->query('SELECT  value FROM settings WHERE label="PREFERENCE_INDEX_MARGIN"',TRUE,TRUE));

$db = new Database();
/**
* Allotment class
*/
class Allotments
{

private $courses;
private $applicant;

	public function make_first_allotment(){
//make first allotment based on  religions.
		global $db;
$count = $db->query('SELECT COUNT(user_id) FROM applications WHERE chellan_payment = 1',TRUE,TRUE);
if($count < 1){
	return;
}


		$db->query('UPDATE settings SET value = "FALSE" WHERE label="SUBMIT_APPLICATION"');
		$db->query('UPDATE settings SET value = 1 WHERE label = "SYSTEM_STATUS"');

//get all relgions with reservation percentage > 0 and religions which has atleast one applicant.
		$religions = $db->query('SELECT 
			DISTINCT religions.religion_id, religions.reservation_percentage
			FROM
			religions
			JOIN applications ON applications.religion_id = religions.religion_id
			WHERE
			religions.reservation_percentage > 0
			ORDER BY religions.reservation_percentage DESC
			',TRUE);

		$courses = $db->query('SELECT course_id, seats  FROM courses',TRUE);

		foreach ($religions as $religion) {


//select applicats based on index mark where religion id = current religion id.
			$applicants = $db->query('SELECT 
				applications.user_id, marks.subject_name,applications.current_preference,applications.religion_id,
				( SUM(marks.awarded_mark) / sum(marks.max_mark) * '.TOTAL_INDEX_MARGIN.'  ) AS index_mark  
				FROM
				applications
				JOIN
				marks ON applications.user_id = marks.user_id
				WHERE applications.religion_id = '.$religion['religion_id'].' AND applications.chellan_payment = 1
				GROUP BY 
				applications.user_id
				HAVING 
				marks.subject_name = "total_marks"
				ORDER BY
				index_mark DESC',TRUE);



//removing subject name from index.( remove unwanted data)
			
if(array_key_exists(0,$applicants)){
	$temp = array();
foreach ($applicants as $applicant) {
				unset($applicant['subject_name']);
				$temp[] = $applicant;
			}

		$applicants = $temp;	
}else{
	unset($applicants['subject_name']);
}


			$courses_with_reserved_seats = array();
			//make course with religion seats.
			foreach ($courses as $course) {
		//find reserved seats for course for this religion[in the religions loop]
		$course['reserved_seats'] = round($religion['reservation_percentage'] * $course['seats'] / 100);
			$i=1;
			while($i<=$course['reserved_seats']){
					$course[$i] = '';
					$i++;
				}
$courses_with_reserved_seats[] = $course;
	}//foreach ($courses as $course) 

	$this->allot_applicants($applicants ,$courses_with_reserved_seats);
	$this->add_alloted_students_to_db();
	
}//#foreach ($religions as $religion)
	//add alloted students to database.
	$db->query('UPDATE applications SET  current_preference = 1 ');
	$this->make_first_allotment_for_general_category($religions,$courses);

}




private function allot_applicants($applicants,$courses){
	$this->courses = $courses;
	if(array_key_exists(0,$applicants)){
		foreach ($applicants as $applicant) {
			$this->allot_applicant_to_courses($applicant);
			}
 	}
  else{
 	$this->allot_applicant_to_courses($applicants);
}
}






private function allot_applicant_to_courses($applicant){
	global $db;
	$this->applicant = $applicant;
	$this->applicant['alloted'] = FALSE;
	$this->applicant['current_preference'] = 1;
	$loop = TRUE;
	while($loop){
		
		//Get preferred course based on current preference.
		switch ($this->applicant['current_preference']) {
		case '1':
		$this->applicant['preference_id']=$db->query('SELECT first_preference FROM applications WHERE user_id = "'.$this->applicant['user_id'].'"',TRUE, TRUE);
		break;

		case '2':
		$this->applicant['preference_id']=$db->query('SELECT second_preference FROM applications WHERE user_id = "'.$this->applicant['user_id'].'"',TRUE, TRUE);
		break;

		case '3':
		$this->applicant['preference_id']=$db->query('SELECT third_preference FROM applications WHERE user_id = "'.$this->applicant['user_id'].'"',TRUE, TRUE);
		break;		
	}
	//Get prefernce based index mark 
	$this->applicant['preference_index']= $this->get_preference_based_index($this->applicant['user_id'], $this->applicant['preference_id']);
		
	//Get preffered course from intilized courses.
		foreach ($this->courses as $course) {
			if($course['course_id']==$this->applicant['preference_id']){
				$prefered_course = $course;
			}
		}

		//Try applicant with preffered course.
		$this->allot_applicant_to_course($prefered_course);

		//increment preference.
		if( ($this->applicant['alloted']==TRUE) OR (++$this->applicant['current_preference'] > 3) ){
			$loop = FALSE;
		}
	}

}//#  function allot_applicant_to_courses($applicant,$courses)


private function  allot_applicant_to_course($prefered_course){

	for($i=1;$i<=$prefered_course['reserved_seats'];$i++){
		if(empty($prefered_course[$i])){
			$prefered_course[$i]=$this->applicant;
			$this->applicant['alloted'] = TRUE;
			break;
		}
		elseif($prefered_course[$i]['preference_index'] < $this->applicant['preference_index']){
			$removed_applicant = $prefered_course[$i];
			$prefered_course[$i] =  $this->applicant;
			$this->applicant = $removed_applicant;
			$this->applicant['alloted'] =	FALSE;
		}
	}

	//merging courses after allotment.
	foreach ($this->courses as $course) {
		if($course['course_id']==$this->applicant['preference_id']){
			$temp[] = $prefered_course;
		}else{
			$temp[] = $course;
		}
	}

	$this->courses = $temp;
}
private function make_first_allotment_for_general_category($religions,$courses){
global $db;


	$general_category_applicants = $db->query("SELECT 
    applications.user_id,
    marks.subject_name,
    applications.religion_id,
    applications.chellan_payment,
    applications.current_preference,
    (SUM(marks.awarded_mark) / sum(marks.max_mark) * 1200) AS index_mark
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id
WHERE applications.user_id NOT IN(SELECT user_id FROM applications WHERE applications.status IN('confirmed','alloted') )
GROUP BY applications.user_id
HAVING marks.subject_name = 'total_marks'
    AND applications.chellan_payment = 1
ORDER BY index_mark DESC",TRUE);



if(array_key_exists(0,$general_category_applicants)){
			foreach ($general_category_applicants as $applicant) {
				unset($applicant['subject_name']);
				// unset($applicant['religion_id']);
				unset($applicant['chellan_payment']);
				$temp[] = $applicant;
			}
$general_category_applicants =$temp;
unset($temp);
}else{
	unset($general_category_applicants['subject_name']);
	// unset($general_category_applicants['religion_id']);
	unset($general_category_applicants['chellan_payment']);
}


//get all courses with general seats.
	/*general seats =  total seats - total reserved seats by all religions 
		here general seat is named as reserved seat[we can use religion based course].
	*/



		foreach ($courses as $course) {
			$course['reserved_seats'] = $course['seats'];
			foreach ($religions as $religion) {
				$course['reserved_seats'] = $course['reserved_seats'] - round($course['seats'] * $religion['reservation_percentage'] / 100  );
			}
			$i=1;
			while($i<=$course['reserved_seats']){
					$course[$i] = '';
					$i++;
			}


			$courses_with_general_seats[] = $course; 
		}
$courses_with_alloted_applicants_temp = $this->allot_applicants($general_category_applicants ,$courses_with_general_seats);
//add alloted students to database.
	$this->add_alloted_students_to_db();
	$db->query('UPDATE applications SET  current_preference = 1 ');

}


private function add_alloted_students_to_db(){

	global $db;
	foreach ($this->courses as $course_with_students) {
	for($i=1;$i<=$course_with_students['reserved_seats'];$i++){
		if(!empty($course_with_students[$i])){
$db->query('UPDATE applications SET 
	alloted_course_id = '.$course_with_students["course_id"].',
	allotment_number = '.(ALLOTMENT_NO + 1).',
	status = "alloted"
	WHERE user_id = '.$course_with_students[$i]['user_id'].'
	');
		}
	}	
}
}// #private function add_alloted_students_to_db($courses)


private function get_preference_based_index($user_id,$preference_course_id){
	global $db;
	$preference_index = $db->query('SELECT 
		SUM(marks.awarded_mark) / SUM(marks.max_mark) * '.PREFERENCE_INDEX_MARGIN.' AS prefernce_index
		FROM
		courses
		JOIN
		course_dependencies ON courses.course_id = course_dependencies.course_id
		JOIN
		marks ON course_dependencies.dependency = marks.subject_name
		WHERE
		courses.course_id = '.$preference_course_id.' AND user_id = '.$user_id , TRUE , TRUE);
	return $preference_index;
}


//next allotments.
public function make_next_allotment(){
	global $db;
	//get all relgions with reservation percentage > 0 and religions which has atleast one applicant.
		$religions = $db->query('SELECT 
			DISTINCT religions.religion_id, religions.reservation_percentage
			FROM
			religions
			JOIN applications ON applications.religion_id = religions.religion_id
			WHERE
			reservation_percentage > 0
			ORDER BY reservation_percentage DESC
			',TRUE);
		foreach ($religions as $religion) {
//select applicats based on index mark where religion id = current religion id.
//and allotment.status != confirmed.

		$applicants = $db->query('SELECT 
    applications.user_id,
    marks.subject_name,
    applications.current_preference,
    (SUM(marks.awarded_mark) / sum(marks.max_mark) * 1200) AS index_mark
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id

WHERE    applications.religion_id = '.$religion['religion_id'].'
    AND applications.chellan_payment = 1
AND  applications.status!="confirmed"
GROUP BY applications.user_id
HAVING marks.subject_name = "total_marks"
ORDER BY index_mark DESC
',TRUE);
	
//removing subject name from index.( remove unwanted data)
if(array_key_exists(0,$applicants)){
	$temp = array();
foreach ($applicants as $applicant) {
				unset($applicant['subject_name']);
				$temp[] = $applicant;
			}

		$applicants = $temp;	
}else{
	unset($applicants['subject_name']);
}

			
	//get courses 
	$courses = $db->query('SELECT course_id, seats  FROM courses',TRUE);
	$courses_with_reserved_seats = array();
			//make course with reserved seats for religion.
			foreach ($courses as $course) {
				echo nl2br(str_replace(' ','&nbsp;&nbsp;&nbsp;',print_r($course,TRUE))).'<hr>';
		//find reserved seats for course for this religion[in the religions loop]
		$course['reserved_seats'] = round($religion['reservation_percentage'] *$course['seats'] / 100);
//finding confirmed seats for this religion.		
$confirmed_seats_for_this_religion = $db->query('SELECT 
    COUNT(applications.status)
FROM
    applications
WHERE applications.religion_id = '.$religion['religion_id'].' AND alloted_course_id = '.$course['course_id'].' AND  applications.status="confirmed"',TRUE,TRUE);
$course['reserved_seats'] = $course['reserved_seats'] - $confirmed_seats_for_this_religion;

//if all seats are confirmed in this religion then move to  next religion
echo "confirmed_seats ".$confirmed_seats_for_this_religion."<br>";
echo "reserved_seats ".$course['reserved_seats']."<br>";
		
				$i=1;
						while($i<=$course['reserved_seats']){
								$course[$i] = '';
								$i++;
							}
			$courses_with_reserved_seats[] = $course;
	
		
echo "inside courses ..<br>";
echo nl2br(str_replace(' ','&nbsp;&nbsp;&nbsp;',print_r($courses_with_reserved_seats,TRUE))).'<hr>';
}//#foreach ($courses as $course)
$this->allot_applicants($applicants ,$courses_with_reserved_seats);
$this->add_alloted_students_to_db();
		}//#foreach ($religions as $religion) 
$db->query('UPDATE applications SET  current_preference = 1 ');
$this->make_next_allotment_for_general_category($religions,$courses);
}//#function make_next_allotment()



private function make_next_allotment_for_general_category($religions,$courses){
global $db;

//get all students in general category
	$general_category_applicants = $db->query('SELECT 
    applications.user_id,
    marks.subject_name,
    applications.religion_id,
    applications.chellan_payment,
    applications.current_preference,
    (SUM(marks.awarded_mark) / sum(marks.max_mark) * 1200) AS index_mark
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id
    WHERE applications.user_id NOT IN(SELECT user_id FROM applications WHERE applications.status="confirmed")
GROUP BY applications.user_id
HAVING marks.subject_name = "total_marks"
    AND applications.chellan_payment = 1
ORDER BY index_mark DESC',TRUE);

	if(array_key_exists(0,$general_category_applicants)){
			foreach ($general_category_applicants as $applicant) {
				unset($applicant['subject_name']);
				// unset($applicant['religion_id']);
				unset($applicant['chellan_payment']);
				$temp[] = $applicant;
			}
$general_category_applicants =$temp;
unset($temp);
}else{
	unset($general_category_applicants['subject_name']);
	// unset($general_category_applicants['religion_id']);
	unset($general_category_applicants['chellan_payment']);
}

//get all courses with general seats.
	/*general seats =  total seats - total reserved seats by all religions 
		here general seat is named as reserved seat[we can use religion based course].
	*/

		foreach ($courses as $course) {

			$course['reserved_seats'] = $course['seats'];
			foreach ($religions as $religion) {
				$course['reserved_seats'] = $course['reserved_seats'] - round($course['seats'] * $religion['reservation_percentage'] / 100  );
			$confirmed_seats = $db->query('SELECT 
    COUNT(applications.status)
FROM
    applications
WHERE
    applications.religion_id NOT IN (SELECT 
            religion_id
        FROM
            religions
        WHERE
            reservation_percentage > 0)
          AND alloted_course_id = '.$course['course_id'].'
        AND applications.status = "confirmed"',TRUE,TRUE);
			}
$course['reserved_seats'] = $course['reserved_seats'] - $confirmed_seats;		
			$i=1;
			while($i<=$course['reserved_seats']){
					$course[$i] = '';
					$i++;
			}

			$courses_with_general_seats[] = $course; 
		}
$courses_with_alloted_applicants = $this->allot_applicants($general_category_applicants ,$courses_with_general_seats);
//add alloted students to database.
	$this->add_alloted_students_to_db();
	$db->query('UPDATE applications SET  current_preference = 1 ');
}


public function stop_allotments(){
	global $db;
	$db->query('UPDATE applications SET status = "confirmed" WHERE status = "alloted" ');
	$db->query('UPDATE settings SET value = 2 WHERE label = "SYSTEM_STATUS"');
}





}//#class Allotments 



?>
