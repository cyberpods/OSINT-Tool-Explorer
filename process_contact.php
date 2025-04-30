<?php
header('Content-Type: application/json');
// In process_contact.php
$captcha = $_POST['h-captcha-response'];
$secret = 'ES_0400ca27ad5f48d5ac35a25bb7b08cc5';
$verify = file_get_contents("https://hcaptcha.com/siteverify?secret={$secret}&response={$captcha}");
$response = json_decode($verify);

if (!$response->success) {
    die(json_encode(['error' => 'CAPTCHA verification failed']));
}
// Basic validation
if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
    http_response_code(400);
    die(json_encode(['error' => 'All fields are required']));
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid email address']));
}

// In a real application, you would:
// 1. Sanitize the input
// 2. Save to database or send email
// 3. Implement proper error handling

// For this demo, we'll just return a success message
echo json_encode(['success' => true]);