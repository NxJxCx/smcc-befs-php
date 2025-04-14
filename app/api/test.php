<?php

enable_CORS();
header("Content-Type: application/json");


echo json_encode(["success" => "You are connected to the API!"]);