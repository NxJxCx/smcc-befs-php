<?php
if (file_exists("debug.log")) {
    $log = file_get_contents("debug.log");
} else {
    $log = "No log file found.";
}