<?php

authenticated_page("dean");

  // The admin's user ID
$stud_id = conn()->sanitize($_REQUEST['stud_id']);  // The student ID to enroll

if (isset($_POST['enroll_student'])) {

    // Set timezone and current date/time
    date_default_timezone_set("Asia/Manila");
    $dt = date("Y-m-d") . " " . date("H:i:s");

    // Only change the student's status to 'Enrolled'
    $query = "UPDATE students 
              SET status = 'Active' 
              WHERE id = '$stud_id'";

    // Execute the query
    if (conn()->query($query)) {
        // Successfully enrolled, redirect with alert
        echo "<script type='text/javascript'>
                alert('Student Successfully Approve!');
                document.location='dean_students_pending';
              </script>";
    } else {
        // Query failed, display error message
        echo "Error: " . mysqli_error(conn()->get_conn());
    }
}
