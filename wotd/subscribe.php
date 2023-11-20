<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        // Send notification email
        $to = 'followcrom@gmail.com';
        $subject = 'New Word of the Day Subscriber';
        $message = "You have a new subscriber: " . $email;
        $headers = 'From: info@followcrom.online' . "\r\n";
        mail($to, $subject, $message, $headers);

        // Redirect to a different page after handling the subscription
        header('Location: subscribed.html');
        exit; // Always call exit after header redirects
    } else {
        echo "Invalid email address.";
        // Optionally, add a redirect here as well to an error page or back to the form
    }
} else {
    echo "Invalid request.";
    // Optionally, add a redirect here as well
}
?>