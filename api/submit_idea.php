<?php
/**
 * NextGen Farming Incubation Hub - API Handler for Landing Page Idea Submissions
 * Endpoint: POST /api/submit_idea.php
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

// Basic Anti-Spam Honeypot check
if (!empty($_POST['website_trap'])) {
    http_response_code(200);
    echo json_encode([
        'status'   => 'success',
        'message'  => 'Idea submission received successfully.',
        'redirect' => 'index.php?unlocked=1'
    ]);
    exit;
}

// Gather form fields
$ideaData = [
    'full_name'        => $_POST['full_name'] ?? '',
    'email'            => $_POST['email'] ?? '',
    'phone'            => $_POST['phone'] ?? '',
    'local_address'    => $_POST['local_address'] ?? '',
    'education'        => $_POST['education'] ?? '',
    'idea_description' => $_POST['idea_description'] ?? '',
    'video_duration'   => $_POST['video_duration'] ?? null,
];

$videoFile = $_FILES['pitch_video'] ?? null;

$result = saveVisitorIdea($ideaData, $videoFile);

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    || (isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1');

if ($result['success']) {
    http_response_code(200);
    echo json_encode([
        'status'   => 'success',
        'message'  => $result['message'],
        'id'       => $result['id'] ?? null,
        'redirect' => 'index.php?unlocked=1'
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => $result['message']
    ]);
}
exit;
