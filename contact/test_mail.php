<?php
$to = "followcrom@gmail.com";
$subject = "Test email from PHP";
$message = "This is a test email sent from PHP on your server.";
$headers = "From: followcrom@gmail.com";

if(mail($to, $subject, $message, $headers)) {
    echo "Test email sent successfully!";
} else {
    echo "Failed to send test email.";
}
?>