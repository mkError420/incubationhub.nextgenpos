<?php
/**
 * NextGen Farming Incubation Hub - API Handler for Form Submissions
 * Endpoint: POST /api/submit.php
 */

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/../includes/db.php';

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Method Not Allowed. POST request expected.'
    ]);
    exit;
}

// Receive payload from either application/x-www-form-urlencoded or application/json
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$data = [];

if (stripos($contentType, 'application/json') !== false) {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true) ?: [];
} else {
    $data = $_POST;
}

// Basic Anti-Spam Honeypot check
if (!empty($data['website_trap'])) {
    // Bot triggered honeypot field
    http_response_code(200);
    echo json_encode([
        'status'  => 'success',
        'message' => 'Submission received successfully.'
    ]);
    exit;
}

// Sanitize inputs
$submissionData = [
    'full_name'    => $data['full_name'] ?? '',
    'email'        => $data['email'] ?? '',
    'phone'        => $data['phone'] ?? '',
    'organization' => $data['organization'] ?? '',
    'category'     => $data['category'] ?? '',
    'sub_interest' => $data['sub_interest'] ?? '',
    'pitch_url'    => $data['pitch_url'] ?? '',
    'message'      => $data['message'] ?? '',
];

$result = saveHubSubmission($submissionData);

if ($result['success']) {
    http_response_code(200);
    echo json_encode([
        'status'  => 'success',
        'message' => $result['message'],
        'id'      => $result['id'] ?? null,
        'mode'    => $result['mode'] ?? 'db'
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => $result['message']
    ]);
}
exit;
