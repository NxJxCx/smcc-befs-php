<?php

authenticated_page("dean");

$s_id = conn()->sanitize($_POST['s_id'] ?? $_REQUEST['s_id']);
$percent = conn()->sanitize($_POST['percent'] ?? null);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $percent !== null) {
    $query = conn()->query("UPDATE subject_percent SET percent = '$percent' WHERE sub_id = '$s_id'") or die(mysqli_error(conn()->get_conn()));
    if ($query) {
        echo "<script>window.location.href = 'dean_subjects';</script>";
    } else {
        echo "<script>alert('Error updating percent.');</script>";
    }
}