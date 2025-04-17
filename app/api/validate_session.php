<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "GET"):
    check_api_key($_GET['api_key'] ?? []);
    $username = $_GET['username'] ?? null;
    $session_key = $_GET['session_key'] ?? null;
    $token = $_GET['token'] ?? null;
    if ($username === null || $session_key === null || $token === null) {
        http_response_code(400);
        die(json_encode(["detail" => "Bad Request"]));
    }
    $folderPath = "/training_states/";
    $filename = "$token.json";
    // $STATE_BASE_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . "training_states";
    // $filepath = $STATE_BASE_DIR . DIRECTORY_SEPARATOR . $filename;
    // if (is_file($filepath)) {
    //     $f = file_get_contents($filepath);
    //     $sess = json_decode($f, true);
    //     if (($sess["username"] ?? null) === $username && ($sess["session_key"] ?? null) === $session_key) {
    //         die(json_encode(['valid' => true]));
    //     }
    // }
    try {
        $f = getFileFromStorageApi($filename, "application/json", $folderPath);
        $sess = json_decode($f, true);
        if (($sess["username"] ?? null) === $username && ($sess["session_key"] ?? null) === $session_key) {
            die(json_encode(['valid' => true]));
        }
    } catch (Exception $e) {
        // skip error
    }
    echo json_encode(["valid" => false]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;