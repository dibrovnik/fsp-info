<html lang="ru">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/styles.css" />
  <link rel="icon" type="image/ico" href="https://events.fsp-russia.com/favicon-fsp.ico">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <title>ФСП ИФНО</title>
</head>

<body>
  <?
  include 'header.php';
  ?>
  <main>
    <?php
    include 'hero.php';
    include 'news-component.php';
    include 'calendar.php';
    include 'map.php';
    // include 'accaunt_otdeleniya.php';
    // include 'predstavitelstva.php';
    ?>
  </main>

  <?
  include 'footer.php';
  ?>
  <script src="assets/js/script.js"></script>
  <script src="assets/js/map.js"></script>
  <script src="assets/js/accordion.js"></script>
</body>

</html>