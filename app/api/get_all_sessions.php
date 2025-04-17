<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "GET"):
    check_api_key($_GET['api_key'] ?? []);
    // $STATE_BASE_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . "training_states";
    // $allSessions = array_diff(glob($STATE_BASE_DIR . DIRECTORY_SEPARATOR . "*.json"), ['.', '..']);
    $folderPath = "/training_states/";
    try {
        $files = globAllFilesFromStorageApi($folderPath);
        if (array_key_exists("files", $files)) {
            $allSessions = array_filter($files["files"], fn($f) => str_ends_with($f, ".json"));
        } else {
            throw new Exception("Failed to retrieve files from storage API");
        }
    } catch (Exception $e) {
        die(json_encode(["detail" => $e->getMessage()]));
    }
    $sessions = [];
    foreach ($allSessions as $session) {
        $sessions[] = pathinfo(basename($session), PATHINFO_FILENAME);
    }
    echo json_encode(["data" => $sessions]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;