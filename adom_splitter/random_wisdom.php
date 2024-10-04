<!DOCTYPE html>
<html>

<head>
  <title>followCrom - Adom Splitter</title>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <meta name="description" content="An early version of domdom that uses PHP explode() to get a quote
            from a text list." />

  <link rel="stylesheet" media="(min-width: 1024px)" href="css/large.css" />
  <link rel="stylesheet" media="(min-width: 640px) and (max-width: 1023px)" href="css/medium.css" />
  <link rel="stylesheet" media="(max-width: 639px)" href="css/small.css" />
  <link rel="shortcut icon" href="images/favicon_dom.ico" />

  <meta content="followCrom" name="author" />

  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow" />

  <!-- <script type="text/javascript" language="JavaScript" src="js/analytics.js"></script> -->


  <?php
  $text = file_get_contents("words_split.txt");
  $textArray = explode(":newInc:", $text);
  $randArrayIndexNum = array_rand($textArray);
  $randPhrase = $textArray[$randArrayIndexNum];
  ?>

</head>


<body class="main">

  <!-- <div class="top_banner">
    <h1><a href="../index.html">followCrom</a></h1>
  </div> -->


  <script>
    $(function() {
      // when the button with id "refresh" is clicked
      $("#refresh").click(function() {
        // load the content of "random_wisdom.php" into the element with id "content"
        $("#content").load("random_wisdom.php")

        // prevent the default action of the link
        return false;
      })
    })
  </script>


  <div class="banner">
    <img src="images/djrr.png" alt="DJ Double R" height="120px">

    <div class="button button1">
      <a href="random_wisdom.php">Random<br>Wisdom</a>
    </div>

  </div>

  <div class="top_bar">
    A php script pulls a random quote from a server-side file. Click above to reload
  </div>



  <div class="content">

    <div class="content_box"><?php echo $randPhrase; ?></div>

  </div>

  <div class="bottomrow">
    <div class="grid-container">
      <div class="grid-item">
        <a class="footer" href="../index.html"><img class="icon" src="images/icons/fc_logo.png" alt="followCrom online"
            title="followCrom online"><br>Home</a>
      </div>

      <div class="grid-item">
        <a class="footer" href="../contact/contact.php"><img class="icon" src="images/icons/contact-us.png"
            alt="Contact us" title="Contact us"><br>Contact</a>
      </div>

      <div class="grid-item">
        <a class="footer" href="https://github.com/followcrom" target="_blank"><img class="icon"
            src="images/icons/github.png" alt="followCrom on Git Hub" title="followCrom on Git Hub"><br>GitHub</a>
      </div>

      <div class="grid-item">
        <a class="footer" href="https://linkedin.com/in/followCrom" target="_blank"><img class="icon"
            src="images/icons/linkedin.png" alt="followCrom on inked In" title="followCrom on Linked In"><br>LinkedIn</a>
      </div>

      <div class="grid-item">
        <a class="footer" href="https://medium.com/@followcrom" target="_blank"><img class="icon"
            src="images/icons/medium.png" alt="followCrom on Medium" title="followCrom on Medium"><br>Medium</a>
      </div>
    </div>
  </div>


  </div>
</body>

</html>