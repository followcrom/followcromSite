<?php include 'header.html'; ?>

<div class="form_box">

    <?php
    $errors = [];
    $inputs = [];

    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($request_method === 'POST') {

        // Honeypot check — if filled, likely a bot
        if (!empty($_POST['website'])) {
            // Log bot attempt (optional)
            file_put_contents('bot.log', date('c') . " Bot blocked: " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);

            // Silent exit
            header('HTTP/1.1 200 OK');
            exit;
        }

        // Process the POST submission
        require __DIR__ . '/inc/post.php';

        // If there are errors, show the form again with errors
        if (count($errors) > 0) {
            require __DIR__ . '/inc/get.php';
        } else {
        }
    } else {
        // For GET requests just show the form
        require __DIR__ . '/inc/get.php';
    }
    ?>

</div>

</body>

</html>