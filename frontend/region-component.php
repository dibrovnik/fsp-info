<?
$region_id = !empty($_GET['region_id']) ? $_GET['region_id'] : null;
function file_get_contents_curl_region($url)
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

$json_region = file_get_contents_curl_region('https://final-hackathone.dev-level.ru/api/v1/region/' . $region_id);
?>

<div class="wrapper volunteerPage">
  <img class="volunteerPage__banner"
    src="<?= $json_region[1]->banner->path ?? 'https://dev-level.ru/assets/img/News.png' ?>" alt="" />
  <div class="volunteerPage__info">
    <div class="volunteerPage__info__name">
      <img src="<?= $json_region[1]->avatar->path ?? 'https://dev-level.ru/assets/img/News.png' ?>" />
      <div class="volunteerPage__info__name__title">
        <h1 class="fs-24 fw-600"><?= $json_region[1]->name ?></h1>
        <div class="volunteerPage__info__name__title__flex">
            <?
            $date = new DateTime($json_region[1]->updated_at); // Преобразуем строку в DateTime
            $currentDate = new DateTime(); // Текущая дата

            // Вычисляем разницу между датами
            $dateDiff = $date->diff($currentDate);

            // Проверяем, если разница меньше одного месяца
            if ($dateDiff->m < 1 && $dateDiff->y === 0) {
                    ?>
          <div class="volunteerPage__info__name__title__flex__line">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
              <g clip-path="url(#clip0_16_7727)">
                <path
                  d="M12.5797 2.6747C12.0195 2.1992 11.4322 1.6997 10.8345 1.1567C10.5622 0.908365 10.2387 0.72288 9.88682 0.613367C9.53496 0.503854 9.16332 0.472989 8.79821 0.522955C8.44308 0.569404 8.10246 0.693215 7.80039 0.885655C7.49831 1.07809 7.24215 1.33447 7.04996 1.6367C6.33748 2.80712 5.80027 4.07558 5.45546 5.4017C5.29172 5.18398 5.08176 5.00525 4.84068 4.87838C4.5996 4.7515 4.3334 4.67963 4.06123 4.66794C3.78905 4.65625 3.51767 4.70503 3.2666 4.81077C3.01554 4.9165 2.79102 5.07657 2.60921 5.27945C1.33787 6.57962 0.633926 8.33084 0.651714 10.1492C0.634811 11.9946 1.2358 13.7926 2.35899 15.257C3.48217 16.7213 5.06298 17.7677 6.84971 18.2297C7.55007 18.4078 8.2698 18.4985 8.99246 18.4997C10.0891 18.5012 11.1752 18.2864 12.1888 17.8677C13.2023 17.449 14.1233 16.8345 14.8991 16.0595C15.6749 15.2844 16.2903 14.364 16.71 13.3509C17.1298 12.3378 17.3456 11.2518 17.3452 10.1552C17.3452 6.7247 15.1372 4.8497 12.5797 2.6747ZM12.7432 14.9642C12.5287 15.1293 12.3031 15.2797 12.0682 15.4142C12.2834 14.9648 12.3961 14.4732 12.3982 13.975C12.3982 12.5297 11.391 10.852 10.1482 9.3812C10.0056 9.21435 9.82859 9.08038 9.62926 8.98851C9.42993 8.89665 9.21306 8.84908 8.99359 8.84908C8.77411 8.84908 8.55724 8.89665 8.35792 8.98851C8.15859 9.08038 7.98154 9.21435 7.83896 9.3812C6.07496 11.4497 4.97021 13.6862 5.99996 15.4997C5.05295 14.9671 4.26612 14.1902 3.72145 13.2501C3.17678 12.3099 2.89422 11.2409 2.90321 10.1545C2.87995 9.09036 3.23771 8.05299 3.91196 7.22945C4.01796 7.40345 4.13171 7.5717 4.25321 7.7342C4.45387 8.00513 4.72952 8.21132 5.04609 8.32729C5.36266 8.44325 5.70628 8.46391 6.03446 8.3867C6.36738 8.31247 6.67181 8.14379 6.91126 7.90088C7.15071 7.65797 7.31501 7.35115 7.38446 7.0172C7.66164 5.54518 8.19227 4.13246 8.95271 2.84195C8.96831 2.81633 8.98963 2.79466 9.01501 2.77866C9.04038 2.76265 9.06912 2.75274 9.09896 2.7497C9.1387 2.74453 9.17908 2.74808 9.21729 2.76013C9.25551 2.77218 9.29063 2.79242 9.32021 2.81945C9.93821 3.3812 10.545 3.8972 11.124 4.3877C13.5157 6.4187 15.0952 7.7627 15.0952 10.1522C15.0983 11.0811 14.8878 11.9983 14.4799 12.8329C14.0721 13.6676 13.4779 14.3973 12.7432 14.9657V14.9642Z"
                  fill="#0057FF" />
              </g>
              <defs>
                <clipPath id="clip0_16_7727">
                  <rect width="18" height="18" fill="white" transform="translate(0 0.5)" />
                </clipPath>
              </defs>
            </svg>
            <p class="fs-16 fw8">Актуальные данные</p>
          </div>
          <?
          }
          ?>
        </div>
      </div>
    </div>
    <div class="volunteerPage__info__buttons">
      <a href="/regions" class="fs-14 16 fw8 btn btn--blue"
        style="width: fit-content"><?= $json_region[1]->district->short_name ?></a>
        <?
        $date = new DateTime($json_region[1]->updated_at); // Преобразуем строку в DateTime
            $currentDate = new DateTime(); // Текущая дата

            // Вычисляем разницу между датами
            $dateDiff = $date->diff($currentDate);

            // Проверяем, если разница меньше одного месяца
            if ($dateDiff->m < 1 && $dateDiff->y === 0) {
              ?>
      <div class="volunteerPage__info__name__title__flex__line">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
          <g clip-path="url(#clip0_16_7727)">
            <path
              d="M12.5797 2.6747C12.0195 2.1992 11.4322 1.6997 10.8345 1.1567C10.5622 0.908365 10.2387 0.72288 9.88682 0.613367C9.53496 0.503854 9.16332 0.472989 8.79821 0.522955C8.44308 0.569404 8.10246 0.693215 7.80039 0.885655C7.49831 1.07809 7.24215 1.33447 7.04996 1.6367C6.33748 2.80712 5.80027 4.07558 5.45546 5.4017C5.29172 5.18398 5.08176 5.00525 4.84068 4.87838C4.5996 4.7515 4.3334 4.67963 4.06123 4.66794C3.78905 4.65625 3.51767 4.70503 3.2666 4.81077C3.01554 4.9165 2.79102 5.07657 2.60921 5.27945C1.33787 6.57962 0.633926 8.33084 0.651714 10.1492C0.634811 11.9946 1.2358 13.7926 2.35899 15.257C3.48217 16.7213 5.06298 17.7677 6.84971 18.2297C7.55007 18.4078 8.2698 18.4985 8.99246 18.4997C10.0891 18.5012 11.1752 18.2864 12.1888 17.8677C13.2023 17.449 14.1233 16.8345 14.8991 16.0595C15.6749 15.2844 16.2903 14.364 16.71 13.3509C17.1298 12.3378 17.3456 11.2518 17.3452 10.1552C17.3452 6.7247 15.1372 4.8497 12.5797 2.6747ZM12.7432 14.9642C12.5287 15.1293 12.3031 15.2797 12.0682 15.4142C12.2834 14.9648 12.3961 14.4732 12.3982 13.975C12.3982 12.5297 11.391 10.852 10.1482 9.3812C10.0056 9.21435 9.82859 9.08038 9.62926 8.98851C9.42993 8.89665 9.21306 8.84908 8.99359 8.84908C8.77411 8.84908 8.55724 8.89665 8.35792 8.98851C8.15859 9.08038 7.98154 9.21435 7.83896 9.3812C6.07496 11.4497 4.97021 13.6862 5.99996 15.4997C5.05295 14.9671 4.26612 14.1902 3.72145 13.2501C3.17678 12.3099 2.89422 11.2409 2.90321 10.1545C2.87995 9.09036 3.23771 8.05299 3.91196 7.22945C4.01796 7.40345 4.13171 7.5717 4.25321 7.7342C4.45387 8.00513 4.72952 8.21132 5.04609 8.32729C5.36266 8.44325 5.70628 8.46391 6.03446 8.3867C6.36738 8.31247 6.67181 8.14379 6.91126 7.90088C7.15071 7.65797 7.31501 7.35115 7.38446 7.0172C7.66164 5.54518 8.19227 4.13246 8.95271 2.84195C8.96831 2.81633 8.98963 2.79466 9.01501 2.77866C9.04038 2.76265 9.06912 2.75274 9.09896 2.7497C9.1387 2.74453 9.17908 2.74808 9.21729 2.76013C9.25551 2.77218 9.29063 2.79242 9.32021 2.81945C9.93821 3.3812 10.545 3.8972 11.124 4.3877C13.5157 6.4187 15.0952 7.7627 15.0952 10.1522C15.0983 11.0811 14.8878 11.9983 14.4799 12.8329C14.0721 13.6676 13.4779 14.3973 12.7432 14.9657V14.9642Z"
              fill="#0057FF" />
          </g>
          <defs>
            <clipPath id="clip0_16_7727">
              <rect width="18" height="18" fill="white" transform="translate(0 0.5)" />
            </clipPath>
          </defs>
        </svg>
        <p class="fs-16 fw8">Актуальные данные</p>
      </div>
      <?}?>
      <? if ($json_region[1]->contacts[0]) { ?>
      <a href="<?= $json_region[1]->contacts[0]->value ?>" class="fs-20 fw-500 btn btn--blue">
        Связаться
      </a>
      <? } ?>
    </div>
  </div>
</div>

<section class="wrapper contacts">
  <div class="section-text">
    <p class="fs-32 fw-800 section-text__title">Контакты представительства</p>
    <!-- <p class="fs-16 fw-500 section-text__title-contacts">Официальные представители</p> -->
  </div>
  <div class="cards">
    <!-- <div class="persons-grid">
      <div class="persons-grid__card">
        <div class="persons-grid__card__img">
          <img src="assets/img/News.png" alt="">
        </div>
        <div class="persons-grid__card__name">
          <p class="fs-14 fw-700">Имя фамилия отчество</p>
          <button class="btn--white fs-14">Показать контакты </button>
        </div>
      </div>

      <div class="persons-grid__card">
        <div class="persons-grid__card__img">
          <img src="assets/img/News.png" alt="">
        </div>
        <div class="persons-grid__card__name">
          <p class="fs-14 fw-700">Имя фамилия отчество</p>
          <button class="btn--white fs-14">Показать контакты </button>
        </div>
      </div>

      <div class="persons-grid__card">
        <div class="persons-grid__card__img">
          <img src="assets/img/News.png" alt="">
        </div>
        <div class="persons-grid__card__name">
          <p class="fs-14 fw-700">Имя фамилия отчество</p>
          <button class="btn--white fs-14">Показать контакты </button>
        </div>
      </div>

      <div class="persons-grid__card">
        <div class="persons-grid__card__img">
          <img src="assets/img/News.png" alt="">
        </div>
        <div class="persons-grid__card__name">
          <p class="fs-14 fw-700">Имя фамилия отчество</p>
          <button class="btn--white fs-14">Показать контакты </button>
        </div>
      </div>
    </div> -->
    <div class="contacts-grid">
      <?
      if ($json_region[1]->contacts) {
        foreach ($json_region[1]->contacts as $contact) { ?>
      <div class="contacts-grid__card">
        <p class="fs-20 fw-600"><?= $contact->title ?></p>
        <div class="contacts-grid__card__number">
          <a href="<?= $contact->value ?>" class="fs-16 fw-600"><?= $contact->value ?></a>
        </div>
      </div>
      <? }
      } ?>
    </div>
  </div>
</section>