<?php include 'header.html';?>

<div class="form_box">

    <?php


$errors = [];
$inputs = [];

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === 'GET') {
    // show the form
    require __DIR__ . '/inc/get.php';
} elseif ($request_method === 'POST') {
    // handle the form submission
    require    __DIR__ .  '/inc/post.php';
    // show the form if the error exists
    if (count($errors) > 0) {
        require __DIR__ . '/inc/get.php';
    }
}

?>

</div>

</body>

</html>