<?php

authenticated_page("reviewer");

$qid = conn()->sanitize($_REQUEST['qid']);
$s_id = $_REQUEST['s_id'];
$active_tab = $_REQUEST['active_tab'] ?? ''; // Default to empty if not set

$query = "DELETE FROM question_answer WHERE id = '$qid'" or die(mysqli_error(conn()->get_conn()));

if (conn()->query($query)) {
    // If the active_tab is set (i.e., the user is on Preboard 2), include it in the redirect
    if ($active_tab) {
        header("Location: reviewer_test_questions?s_id=$s_id&active_tab=$active_tab");
    } else {
        // Otherwise, just go back to Preboard 1 (without active_tab)
        header("Location: reviewer_test_questions?s_id=$s_id");
    }
    exit;
}
