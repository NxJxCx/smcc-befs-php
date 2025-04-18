<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST"):
    check_api_key($_GET['api_key'] ?? []);
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    $token = $data['token'] ?? null;
    if ($token === null) {
        http_response_code(400);
        die(json_encode(["detail" => "Bad Request"]));
    }
    $folderPath = "/training_states/";
    $filename = "$token.json";
    // $STATE_BASE_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . "training_states";
    // $filepath = $STATE_BASE_DIR . DIRECTORY_SEPARATOR . $filename;
    
    // if (is_file($filepath)) {
    //     unlink($filepath);
    // }

    try {
        $respd = deleteFromStorageApi($filename, $folderPath);
        debug_out("DELETED SESSION: {$respd}");
    } catch (Exception $e) {
        // skip error
    }

    echo json_encode(["success" => true, "detail" => "OK"]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;