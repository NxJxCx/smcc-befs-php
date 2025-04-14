<?php

authenticated_page("dean");

// Validate and fetch parameters from the URL
$faculty_id = conn()->sanitize($_GET['faculty_id'] ?? null); // Faculty ID
$school_year = conn()->sanitize($_GET['school_year'] ?? null); // School Year
$course_id = conn()->sanitize($_GET['course_id'] ?? null); // Course ID
$sub_id = conn()->sanitize($_GET['sub_id'] ?? null); // Subject ID to remove

// Ensure all required parameters are present
if (!user_id() || !$faculty_id || !$school_year || !$course_id || !$sub_id) {
    die("Error: Missing required parameters. Please log in again.");
}

// Delete the assigned subject from the faculty_subjects table
$query = "DELETE FROM faculty_subjects WHERE faculty_id = '$faculty_id' AND subjects_id = '$sub_id'";
if (conn()->query($query)) {
    // Successfully removed, redirect back to the Assign Subjects page
    header("Location: dean_reviewer_assign_subjects?faculty_id=$faculty_id&school_year=$school_year&course_id=$course_id");
    exit();
} else {
    die("Error: Could not remove the subject. Please try again.");
}
