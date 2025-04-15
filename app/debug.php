<?php
$log = (file_exists("debug.log")) ? file_get_contents("debug.log") : "No log file found.";
echo "<code>$log</code>";

$model_paths = __DIR__ . DIRECTORY_SEPARATOR . "inference";
$inference_models = glob($model_paths . DIRECTORY_SEPARATOR . "*.onnx");
$api_pages = array_map(fn($page) => "/inference/" . pathinfo(basename($page), PATHINFO_FILENAME), array_diff($inference_models, ['.', '..']));
echo "<br><br>Model Pages:<br>";
foreach ($api_pages as $page) {
    echo "<a href='$page'>$page</a><br>";
}