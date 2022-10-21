<!DOCTYPE html>
<html>

<head>
  <title>followCrom</title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" media="(min-width: 1024px)" href="css/large.css" />
  <link rel="stylesheet" media="(min-width: 640px) and (max-width: 1023px)" href="css/medium.css" />
  <link rel="stylesheet" media="(max-width: 639px)" href="css/small.css" />
  <link rel="shortcut icon" href="images/favicon_dom.ico" />

  <script type="text/javascript" language="JavaScript" src="js/analytics.js"></script>
</head>

<body class="main">

  <div class="banner">

    <h1>followCrom</h1>
    <img src="images/banner_man.gif" alt="followCrom" title="followCrom">


  </div>
  <div class="content">

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
</div>



<div class="bottomrow">
    <div class="grid-container">
      <div class="grid-item"><a class="footer" href="index.html">home</a></div>
      <div class="grid-item"><a class="footer" href="contact.php">contact</a></div>
    </div>

    <div>
        <a class="footer" href="https://github.com/followcrom/" target="_blank"
          ><img
            class="icon"
            src="images/icons/github.png"
            alt="Go to Git Hub"
            title="Go to Git Hub"
        /></a>
        <a class="footer" href="https://linkedin.com/in/followcrom" target="_blank"
          ><img
          class="icon"
          src="images/icons/linkedin.png"
          alt="Go to Linked In"
          title="Go to Linked In"
        /></a>
      </div>

</body>

</html>