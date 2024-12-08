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

$json = file_get_contents_curl_news('https://final-hackathone.dev-level.ru/api/v1/news');
$data_raw = $json[1];

$data_raw = $news_page ? $data_raw : array_slice($data_raw, 0, 4);
?>
<div class="news wrapper">
  <p class="fw-800 fs-36">Новости</p>
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
  <? if (empty($news_page)) { ?>
  <a href="/news" class="news__button btn--white fw-700 fs-14">Смотреть всё</a>
  <? } ?>
</div>