<html lang="ru">

<?
$new_id = !empty($_GET['new_id']) ? $_GET['new_id'] : null;
function file_get_contents_curl($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  $data = curl_exec($ch);
  curl_close($ch);
  $json = [];
  $json[0] = json_encode($data);
  $json[1] = json_decode($data);
  return $json;
}

$json = file_get_contents_curl('https://final-hackathone.dev-level.ru/api/v1/new/' . $new_id);


?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://dev-level.ru/assets/css/styles.css" />
  <link rel="icon" type="image/ico" href="https://events.fsp-russia.com/favicon-fsp.ico">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <title>ФСП ИФНО - <?=$json[1]->title?></title>
</head>


<body>
  <?
  include 'header.php';
  ?>
  <main>

    <section class="wrapper new--page">
      <img src="<?= $json[1]->photo->path ?? 'https://dev-level.ru/assets/img/News.png' ?>" alt="">
      <h1 class="fs-36 fw8"><?= $json[1]->title ?></h1>
      <p class="fs-18 fw4"><?= $json[1]->content ?></p>
      <? if (!empty($json[1]->link)) { ?>
      <a href="<?= $json[1]->link ?>" class="btn btn--blue fs-18 fw4" style="width: fit-content;">Перейти</a>
      <? } ?>
    </section>
  </main>

  <?
  include 'footer.php';
  ?>
  <script src="https://dev-level.ru/assets/js/script.js"></script>
  <script src="https://dev-level.ru/assets/js/map.js"></script>
  <script src="https://dev-level.ru/assets/js/accordion.js"></script>
</body>

</html>