$('.course-preference').change(function(){
var prefrence = [];
$('.course-preference').each(function(i,obj){
	// console.log(obj.value);
	prefrence.push(obj.value);
});
var error = false;
$.grep( prefrence, function( n, i ) {
if(n=='')
{
error = true;
return;
}
});
//creating data
if(!error){
formData = 'getCourseDependencies=yes&preference[0]='+prefrence[0]+'&preference[1]='+prefrence[1]+'&preference[2]='+prefrence[2];
$.post( "register.php", formData )
.done(function(subjects) {
$('#ApplicantMarks').find('div').remove();
subjects  = $.parseJSON(subjects);
$.each( subjects , function( index, name ) {
$('#ApplicantMarks').append('<div><h4>'+name+'</h4><input type="hidden" name="subject_name[]" value="'+name+'"> <label for="">max mark</label><input style="width:30px;" type="text" name="max_mark[]" value="">	<label for="">awarded mark</label>	<input style="width:30px;" type="text"  name="awarded_mark[]" value=""><br></div>');
console.log(name);
});


  });
}
else{
	$('#ApplicantMarks').find('div').remove();
}
});
