<?php
session_start();

$to = "info@example.com"; // Change to your actual email

if (isset($_SESSION['last_contact']) && time() - $_SESSION['last_contact'] < 60) {
    http_response_code(429);
    echo "Please wait before sending another message.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["website"])) {
        http_response_code(403);
        echo "Spam detected.";
        exit;
    }

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $message = strip_tags($_POST["message"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email.";
        exit;
    }

    $body = "From: $email\n\nMessage:\n$message";
    $headers = "From: no-reply@example.com\r\nReply-To: $email";

    if (mail($to, "New OSINT Contact", $body, $headers)) {
        $_SESSION['last_contact'] = time();
        echo "Message sent successfully!";
    } else {
        http_response_code(500);
        echo "Failed to send message.";
    }
}
?>
