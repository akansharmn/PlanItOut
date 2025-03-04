
<?php
namespace PlanItOut\Api;

header('Content-Type: application/json');

// Simple health check endpoint
echo json_encode([
    'status' => 'success',
    'message' => 'I am healthy',
    'timestamp' => date('Y-m-d H:i:s')
]);
