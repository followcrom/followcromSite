<?php
$errors = [];
$inputs = [];
$rate_limited = false;

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === 'POST') {

    // Rate limiting check
    $ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_file = sys_get_temp_dir() . '/contact_rate_limit_' . md5($ip);
    $rate_limit_duration = 60; // seconds

    if (file_exists($rate_limit_file)) {
        $last_submission = (int)file_get_contents($rate_limit_file);
        $time_since_last = time() - $last_submission;

        if ($time_since_last < $rate_limit_duration) {
            // Too soon - show rate limit message
            $rate_limited = true;
            $wait_time = $rate_limit_duration - $time_since_last;
        }
    }

    if (!$rate_limited) {
        // Honeypot check — if filled, likely a bot
        if (!empty($_POST['website'])) {
            // Log bot attempt (optional)
            file_put_contents('bot.log', date('c') . " Bot blocked: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);

            // Silent exit
            header('HTTP/1.1 200 OK');
            exit;
        }

        // Process the POST submission - capture output
        ob_start();
        require __DIR__ . '/inc/post.php';
        $post_output = ob_get_clean();
    }
}

// Include header
include 'header.html';
?>

<div class="form_box">

    <?php
    if ($rate_limited) {
        // Show rate limit message
    ?>
        <div class="submitted icons">
            <p><strong>429 Error! You've been rate limited</strong>
                <br>Are you a bot? If not, please wait <i><?php echo $wait_time; ?> seconds</i> before submitting again.
            </p>
            <div class="icon-list">
                <div class="icon-item">
                    <i class="fa-solid fa-envelope" style="color: #FF9900;"></i>
                    <a class="page" href="contact.php">Return to contact form</a>
                </div>
            </div>
        </div>
    <?php
    } elseif ($request_method === 'POST') {
        // Post was processed - either show errors or success message
        if (count($errors) > 0) {
            require __DIR__ . '/inc/get.php';
        } else {
            // Output the success message that was captured from post.php
            echo $post_output;
        }
    } else {
        // For GET requests just show the form
        require __DIR__ . '/inc/get.php';
    }
    ?>

</div>

</body>

</html>