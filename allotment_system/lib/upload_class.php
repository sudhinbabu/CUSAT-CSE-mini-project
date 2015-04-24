<?php
class Upload{
 private $target_dir;
 private $target_file;
 private $max_size;
 private $allowed_types;
 private $result;

 function __construct() {
     $this->allowed_types = array('jpg','png','jpeg');
 }

public function upload_file($user_id,
    $target_dir='uploads/profile_photos/',
    $max_size=500000,
    $allowed_types=array('jpg','png','jpeg'), 
    $input_field='fileToUpload',
    $Height=150,$Width=150){

    $this->target_dir = $target_dir;
    $this->max_size = $max_size;
    $this->allowed_types = $allowed_types;
    $imageFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
    $this->target_file = $target_dir .'/_'.$user_id.'.'.$imageFileType;

    //check image is valid
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check == false) {
        $result['error'] = TRUE;
        $result['upload_error'][]='invalid file';
    } 

//check resolution of image
$imageInformation = getimagesize($_FILES['fileToUpload']['tmp_name']);
$imageWidth = $imageInformation[0]; //Contains the Width of the Image
$imageHeight = $imageInformation[1]; //Contains the Height of the Image
$result['error']=FALSE;
if(!$imageWidth >= $Height || !$imageHeight >= $Width)
{
        $result['error'] = TRUE;
        $result['upload_error'][]='allowed resolution h x w = 150 * 150';
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > $max_size) {
   $result['error'] = TRUE;
   $result['upload_error'][]='allowed max file size is'.$max_size;
}




// Check if file already exists
if (file_exists($this->target_file)) {
     unlink($this->target_file);
}

// Allow certain file formats
if(!in_array($imageFileType, $allowed_types)){
     $result['error'] = TRUE;
     $result['upload_error'][]='Sorry, only jpg, jpeg, and png are allowed.';
 }

if ($result['error']) {
   return $result;
} else {
    // if everything is ok, try to upload file
   if(!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $this->target_file)){
     $result['error'] = TRUE;
     $result['upload_error'][]='upload failed';
   }

$result['error'] = FALSE;
return $result;
}



}
public function get_allowed_types(){
    return $this->allowed_types;
}



}
?>