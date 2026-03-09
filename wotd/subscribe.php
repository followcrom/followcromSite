<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Invalid request.";
    exit;
}

// --- Bot checks ---

// 1. Honeypot: must be empty
if (!empty($_POST['website'])) {
    // Silently redirect as if success — don't alert bots that they were caught
    header('Location: subscribed.html');
    exit;
}

// 2. Referer check: must originate from this site
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
if (strpos($referer, 'followcrom') === false) {
    header('Location: subscribed.html');
    exit;
}

// --- Process legitimate submission ---

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // Send notification email
    $to = 'followcrom@gmail.com';
    $subject = 'New Word of the Day Subscriber';
    $message = "You have a new subscriber: " . $email;
    $headers = 'From: info@followcrom.online' . "\r\n";
    mail($to, $subject, $message, $headers);

    header('Location: subscribed.html');
    exit;
} else {
    echo "Invalid email address.";
}
?>
