<?php
try {
    $log = getFileFromStorageApi("debug.log", "text/plain", "/debug/", false);
} catch (Exception $e) {
    $log = null;
}
if ($log !== null) {
    $l = explode("\n", $log);
    $log = array_map(fn($line) => "<div style=\"padding-bottom: 4px;\">" . str_replace("\r", "", $line) . "</div>", $l);
    $log = implode("", $log);
} else {
    $log = "No log file found";
}
?>
<div style="border: 1px solid gray; border-radius: 5px; padding: 10px; white-space: normal; word-wrap: break-word; background-color: #eee;">
    <code>
        <?= $log ?>
    </code>
</div>
<?php

$folderPath = "/inference/";
try {
    $inference_models = globAllFilesFromStorageApi($folderPath);
} catch (Exception $e) {
    $inference_models = [];
}
$api_pages = array_map(fn($page) => "/inference/{$page}", array_diff($inference_models["files"] ?? [], ['.', '..']));
?>
<br><br>Model Pages:<br>
<?php
foreach ($api_pages as $page):
?>
    <a href="<?= external_storage_api_url() . "/files" . $page ?>"><?=  $page ?></a><br>
<?php
endforeach;