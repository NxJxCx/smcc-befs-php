<?php

authenticated_page("student");

$query = "UPDATE students SET logged_in = 'NO' WHERE id = '" . user_id() . "'";	  
if (conn()->query($query)) 
{
	header("Location: " . base_url() . "/student/students_profile");
} 
else 
{
	echo "Error: " . $query . "<br>" . mysqli_error(conn()->get_conn());
}