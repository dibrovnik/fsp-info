<html lang="ru">
<?
$region_id = !empty($_GET['region_id']) ? $_GET['region_id'] : null;
function file_get_contents_curl_reg($url)
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

$json = file_get_contents_curl_reg('https://final-hackathone.dev-level.ru/api/v1/region/' . $region_id);
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://dev-level.ru/assets/css/styles.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="icon" type="image/ico" href="https://events.fsp-russia.com/favicon-fsp.ico">

  <title>ФСП ИФНО - <?=$json[1]->name?></title>
</head>

<body>
  <?
  include 'header.php';
  ?>
  <main>
    <?php
    include 'region-component.php';
    include 'calendar.php';
    ?>
    <?php
    $news_page = !empty($_GET['page']) ? $_GET['page'] : null;

    function file_get_contents_curl_news($url)
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

    $json = file_get_contents_curl_news('https://final-hackathone.dev-level.ru/api/v1/news/' . $region_id);
    $data_raw = $json[1];

    ?>
    <div class="news wrapper" style="margin-top: 5rem;">
      <p class="fw-800 fs-36">Новости представительства</p>
      <div class="news__grid">
        <?php

        if (is_array($data_raw)) {
          foreach ($data_raw as $value) {
        ?>

        <div class="news__item">
          <img src="<?= $value->photo->path ?? 'https://dev-level.ru/assets/img/News.png' ?>" alt="">
          <p class="text__title fw-500 fs-18"><?php echo $value->title ?></p>
          <p class="text__descr fs-15"><?php echo $value->short_description ?></a>
          <div class="dates">
            <p><?php
                    $date = new DateTime($value->created_at); // Преобразуем строку в DateTime
                    $formattedDate = $date->format('d.m.Y'); // Форматируем дату
                    echo $formattedDate ?></p>
          </div>
          <a href="/news/<?php echo $value->id ?>">Читать далее</a>
        </div>
        <?
          }
        }
        ?>
      </div>
    </div>
  </main>

  <?
  include 'footer.php';
  ?>
  <script src="https://dev-level.ru/assets/js/script.js"></script>
  <script src="https://dev-level.ru/assets/js/map.js"></script>
  <script src="https://dev-level.ru/assets/js/accordion.js"></script>
</body>

</html>