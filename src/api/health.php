<?php
namespace PlanItOut\Api;

header('Content-Type: application/json');

// Simple health check endpoint
echo json_encode([
    'status' => 'success',
    'message' => 'I am healthy',
    'ui_message' => '<strong>Success!</strong> Everything is functioning smoothly.',
    'timestamp' => date('Y-m-d H:i:s')
]);
