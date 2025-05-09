<?php

authenticated_page("dean");


// Fetch the dean's course from the dean_course table
$query = conn()->query("SELECT course_id FROM dean_course WHERE user_id = '" . user_id() . "'");
$dean_course = mysqli_fetch_assoc($query)['course_id'];

// Update all students associated with the dean's course to PREBOARD2
$update_query = "UPDATE students 
                 SET level = 'PREBOARD2'
                 WHERE course_id = '$dean_course' AND status = 'active'";

if (conn()->query($update_query)) {
    // Redirect back to the students list with success message
    header("Location: dean_students_all?message=success");
    exit;
} else {
    // Redirect back with an error message
    header("Location: dean_students_all?message=error");
    exit;
}
