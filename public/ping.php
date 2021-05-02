<?php

$response = [
    'pong' => true,
];

header("Content-Type: application/json");
echo json_encode($response);
exit;
