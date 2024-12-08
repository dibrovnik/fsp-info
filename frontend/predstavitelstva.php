<section class="wrapper">
  <?php
  function file_get_contents_curl_regions($url)
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

  $json_regions = file_get_contents_curl_regions('https://final-hackathone.dev-level.ru/api/v1/regions');
  $data_regions = $json_regions[1];
  ?>


  <div class="accordion">
    <p class="accordion__title fw-800 fs-48">Регионы</p>
    <div class="search-bar">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd"
          d="M9 2C5.13401 2 2 5.13401 2 9C2 12.866 5.13401 16 9 16C10.8859 16 12.5977 15.2542 13.8564 14.0414C13.8827 14.0072 13.9115 13.9742 13.9429 13.9429C13.9742 13.9115 14.0072 13.8827 14.0414 13.8564C15.2542 12.5977 16 10.8859 16 9C16 5.13401 12.866 2 9 2ZM16.0319 14.6177C17.2635 13.078 18 11.125 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18C11.125 18 13.078 17.2635 14.6177 16.0319L18.2929 19.7071C18.6834 20.0976 19.3166 20.0976 19.7071 19.7071C20.0976 19.3166 20.0976 18.6834 19.7071 18.2929L16.0319 14.6177Z"
          fill="#BDCFE4" />
      </svg>
      <input id="search-input" type="text" placeholder="Ручной поиск по региону">
      <div id="results" class="search-result"></div>
    </div>

    <?php
    if (is_array($data_regions)) {
      foreach ($data_regions as $ogrug) {

    ?>
    <div class="accordion-item">
      <div class="accordion-header fs-32 fw-600">
        <?php echo $ogrug->name ?>
        <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
            fill="none">
            <path
              d="M19.0611 8.35399C18.9218 8.2146 18.7564 8.10402 18.5744 8.02858C18.3923 7.95314 18.1972 7.91431 18.0001 7.91431C17.8031 7.91431 17.6079 7.95314 17.4259 8.02858C17.2438 8.10402 17.0784 8.2146 16.9391 8.35399L12.3531 12.939C12.2594 13.0327 12.1322 13.0854 11.9996 13.0854C11.867 13.0854 11.7399 13.0327 11.6461 12.939L7.06113 8.35399C6.77986 8.07259 6.39834 7.91445 6.00048 7.91436C5.60262 7.91427 5.22102 8.07222 4.93963 8.35349C4.65823 8.63475 4.50009 9.01627 4.5 9.41413C4.49991 9.81199 4.65786 10.1936 4.93913 10.475L9.52513 15.061C9.85014 15.386 10.236 15.6439 10.6607 15.8198C11.0853 15.9957 11.5405 16.0862 12.0001 16.0862C12.4598 16.0862 12.9149 15.9957 13.3396 15.8198C13.7643 15.6439 14.1501 15.386 14.4751 15.061L19.0611 10.475C19.3423 10.1937 19.5003 9.81223 19.5003 9.41449C19.5003 9.01674 19.3423 8.63528 19.0611 8.35399Z"
              fill="black" />
          </svg></span>
      </div>
      <div class="accordion-content">
        <?php
            if (is_array($ogrug->regions)) {
              foreach ($ogrug->regions as $region) {

            ?>
        <div class="accordion-subitem" data-name="<?= htmlspecialchars($region->name) ?>">
          <span class="fs-20 fw-600"><?php echo $region->name ?></span>
          <a href="/region/<?= $region->id ?>" class="btn--white-round ">Смотреть аккаунт</a>
        </div>
        <? }
            } ?>

      </div>
    </div>
    <?
      }
    }
    ?>
  </div>


</section>
<script>
const districts = JSON.parse(<?php echo $json_regions[0] ?>);

const searchInput = document.getElementById("search-input");
const resultsDiv = document.getElementById("results");

// Функция рендеринга результатов
function renderResults(results) {
  resultsDiv.innerHTML = results
    .map(
      (region) => `
        <a href="/region/${region.id}"  class="result-item">
            <h3>${region.name}</h3>
            <p>${region.description ? region.description : ''}</p>
        </a>
    `
    )
    .join("");
  resultsDiv.classList.add('open');
}


// Обработчик изменения ввода
searchInput.addEventListener("input", () => {
  const query = searchInput.value.toLowerCase();
  const filteredRegions = districts
    .flatMap((district) => district.regions)
    .filter((region) => region.name.toLowerCase().includes(query));
  renderResults(filteredRegions);
  if (query == '') {
    resultsDiv.innerHTML = '';
    resultsDiv.classList.remove('open');
  }

});
</script>