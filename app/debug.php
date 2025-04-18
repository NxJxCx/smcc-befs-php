<?php
try {
    $log = getFileFromStorageApi("debug.log", "text/plain", "/debug/", false);
} catch (Exception $e) {
    $log = null;
}
$log = $log === null ? "No log file found." : $log;
$log = str_replace("\r\n", "<br>", $log);
$log = str_replace("\n", "<br>", $log);
?>
<code style="white-space: pre-wrap; background-color: #f4f4f4; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 14px;">
    <?= $log ?>
</code>
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