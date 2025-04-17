<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST"):
    check_api_key($_GET['api_key'] ?? []);
    $token = $_GET['token'] ?? null;
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    $username = $data['username'] ?? null;
    $session_key = isset($data['session_id']) ? ($data['session_id'] ?: null) : ($data['session_key'] ?: null);
    $algo = $data['algo'] ?? null;
    if ($token === null || $username === null || $session_key === null || $algo === null) {
        http_response_code(400);
        die(json_encode(['detail' => 'Bad Request']));
    }
    $folderPath = "/training_states/";
    $filename = "$token.json";
    // $STATE_BASE_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . "training_states";
    // $filepath = $STATE_BASE_DIR . DIRECTORY_SEPARATOR . $filename;
    // if (is_file($filepath)) {
    //     $f = file_get_contents($filepath);
    //     $session = json_decode($f, true);
    //     if (($session["username"] ?? null) === $username && ($session['session_key'] ?? null) === $session_key && ($session['token'] ?? null) === $token && ($session['algo'] ?? null) === $algo) {
    //         $session["state"] = $data;
    //         file_put_contents($filepath, json_encode($session, JSON_PRETTY_PRINT));
    //         die(json_encode(["success" => true]));
    //     }
    // }

    try {
        $f = getFileFromStorageApi($filename, "application/json", $folderPath);
        $session = json_decode($f, true);
        if (($session["username"] ?? null) === $username && ($session['session_key'] ?? null) === $session_key && ($session['token'] ?? null) === $token && ($session['algo'] ?? null) === $algo) {
            $session["state"] = $data;
            $respd = uploadToStorageApi($filename, json_encode($session, JSON_PRETTY_PRINT), "application/json", $folderPath);
            die(json_encode(["success" => true]));
        }
    } catch (Exception $e) {
        // skip error
    }

    echo json_encode(["success" => false]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;