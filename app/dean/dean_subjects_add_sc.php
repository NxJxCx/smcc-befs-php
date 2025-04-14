<?php 

authenticated_page("dean");


// Fetch current school year and dean's associated course
$school_year_id = null;
$course_id = null;

// Get current school year
$school_year_query = conn()->query("SELECT id FROM school_year WHERE status = 'Current Set'") or die(mysqli_error(conn()->get_conn()));
if ($school_year_row = mysqli_fetch_array($school_year_query)) {
    $school_year_id = $school_year_row['id'];
} else {
    die("No active school year found.");
}

// Get dean's associated course from dean_course table
$dean_course_query = conn()->query("SELECT course_id FROM dean_course WHERE user_id = '" . user_id() . "'") or die(mysqli_error(conn()->get_conn()));
if ($dean_course_row = mysqli_fetch_array($dean_course_query)) {
    $course_id = $dean_course_row['course_id'];
} else {
    die("No course associated with the dean.");
}

if (isset($_POST['add_subjects'])) {
    // Retrieve form inputs
    $subject_code = conn()->sanitize($_POST['subject_code']);
    $description = conn()->sanitize($_POST['description']);
    $year_level = conn()->sanitize($_POST['year_level']);

    // Set current date and time
    date_default_timezone_set("Asia/Manila");
    $dt = date("Y-m-d H:i:s");

    // Insert subject into the database
    $insert_subject_query = "INSERT INTO subjects (code, description, year_level_id, course_id, school_year_id, date_entry, status) 
                             VALUES ('$subject_code', '$description', '$year_level', '$course_id', '$school_year_id', '$dt', 'Active')";

    if (conn()->query($insert_subject_query)) {
        // Fetch the newly inserted subject
        $subject_query = conn()->query("SELECT id FROM subjects WHERE code = '$subject_code' AND description = '$description' AND year_level_id = '$year_level' AND course_id = '$course_id'") or die(mysqli_error(conn()->get_conn()));
        if ($subject_row = mysqli_fetch_array($subject_query)) {
            $subject_id = $subject_row['id'];

            // Insert related timers and percentages
            $insert_timer_query = "INSERT INTO subjects_timer (subjects_id, timer) VALUES ('$subject_id', '10')";
            $insert_percent_query = "INSERT INTO subject_percent (sub_id, percent) VALUES ('$subject_id', '100')";

            if (conn()->query($insert_timer_query) && conn()->query($insert_percent_query)) {
                echo "<script type='text/javascript'>alert('Subject Successfully Saved!');
                document.location='dean_subjects'</script>";
            } else {
                echo "Error: Failed to insert related timers or percentages. " . mysqli_error(conn()->get_conn());
            }
        } else {
            echo "Error: Failed to fetch the newly inserted subject. " . mysqli_error(conn()->get_conn());
        }
    } else {
        echo "Error: Failed to insert subject. " . mysqli_error(conn()->get_conn());
    }
}
