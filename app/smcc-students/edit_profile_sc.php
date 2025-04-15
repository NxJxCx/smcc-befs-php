<?php

authenticated_page("student");

$query = "UPDATE students SET logged_in = 'NO' WHERE id = '" . user_id() . "'";	  
if (conn()->query($query)) 
{
	echo "<script type='text/javascript'>alert('You have logged out!'); window.location.href = \"". base_url() . "/student/students_profile\"; </script>";
} 
else 
{
	echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
}