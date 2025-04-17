<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST"):
    check_api_key($_GET['api_key'] ?? []);
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    $username = $data["username"] ?? null;
    $session_key = $data["session_key"] ?? null;
    $algo = $data["algo"] ?? null;
    $token = $data["token"] ?? null;
    if ($username === null || $session_key === null || $algo === null) {
        http_response_code(400);
        die(json_encode(["detail" => "Bad Request"]));
    }
    $filename = "$token.json";
    $folderPath = "/training_states/";
    // if (is_file($filepath)) {
    //     http_response_code(400);
    //     die(json_encode(["detail" => "Already Has Session"]));
    // }
    try {
        $respd = getFileFromStorageApi($filename, "application/json", $folderPath);
        debug_out(json_encode(
            $respd
        ));
        die(json_encode(["detail" => "Already Has Session"]));
    } catch (Exception $e) {
        // File not found, proceed to create a new session
    }
    
    $tmpDir = sys_get_temp_dir();
    $tmpFile = tempnam($tmpDir, "smccbefs_");
    rename($tmpFile, $tmpFile .= ".json"); // Give it a .json extension

    $state_content = json_encode([
        "username" => $username,
        "session_key" => $session_key,
        "algo" => $algo,
        "token"=> $token,
        "state" => [
            "connection" => "disconnected",
            "status" => "idle",
            "username" => $username,
            "session_id" => $session_key,
            "algo" => $algo,
            "token" => $token,
            "random_state" => 42,
            "test_size" => 0.2
        ],
    ], JSON_PRETTY_PRINT);
    file_put_contents(
        $tmpFile,
        $state_content
    );
    // Upload to external storage
    try {
        $respd = uploadToStorageApi($tmpFile, "application/json", $filename, $folderPath);
        debug_out(json_encode(
            $respd
        ));
    } catch (Exception $err) {
        http_response_code(500);
        die(json_encode(["detail" => $err->getMessage()]));
    }
    http_response_code(201);
    echo json_encode(["token" => $token]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;