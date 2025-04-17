<?php

enable_CORS();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "GET"):
    check_api_key($_GET['api_key'] ?? []);
    $username = $_GET['username'] ?? null;
    $session_key = $_GET['session_key'] ?? null;
    $algo = $_GET['algo'] ?? null;
    $train_token = $_GET['train_token'] ?? null;

    if ($username === null || $session_key === null || $algo === null) {
        http_response_code(400);
        die(json_encode(["detail" => "Bad Request"]));
    }
    $folderPath = "/training_states/";
    // $STATE_BASE_DIR = dirname(__DIR__) . DIRECTORY_SEPARATOR . "training_states";
    // $allSessions = glob($STATE_BASE_DIR . DIRECTORY_SEPARATOR . "*.json");
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
    $session = null;
    foreach ($allSessions as $s) {
        $jsonFilename = pathinfo(basename($s), PATHINFO_FILENAME);
        $jsonFullFilename = "$folderPath$jsonFilename.json";
        if ($train_token === null || $train_token === $jsonFilename) {
            // $f = file_get_contents($s);
            try {
                $f = getFileFromStorageApi($jsonFullFilename, "application/json", $folderPath);
                if ($f === null || $f === false || strlen($f) === 0) {
                    continue;
                }
            } catch (Exception $e) {
                continue;
            }
            $sess = json_decode($f, true);
            if (($sess["username"] ?? null) === $username && ($sess["session_key"] ?? null) === $session_key && ($sess[""] ?? null) === $algo) {
                $session = $s;
                break;
            }
        }
    }
    echo json_encode(["session_token" => $session]);
else:
    http_response_code(401);
    echo json_encode(["detail" => "Invalid Access"]);
endif;