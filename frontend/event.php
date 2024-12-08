<html lang="ru">
<?
$event_id = !empty($_GET['event_id']) ? $_GET['event_id'] : null;
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

$json = file_get_contents_curl('https://final-hackathone.dev-level.ru/api/v1/event/' . $event_id);
$dateFrom = new DateTime($json[1]->date_from);
$dateTo = new DateTime($json[1]->date_to);
$currentDateTime = new DateTime();

if ($currentDateTime < $dateFrom) {
    $status = 'Будущее';
    $statusColor = 'purple';
} elseif ($currentDateTime > $dateTo) {
    $status = 'Завершенное';
    $statusColor = '';
} else {
    $status = 'Проходит сейчас';
    $statusColor = 'magenta';
}
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://dev-level.ru/assets/css/styles.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="icon" type="image/ico" href="https://events.fsp-russia.com/favicon-fsp.ico">

  <title>ФСП ИНФО - <?=$json[1]->name?></title>
</head>


<body>
  <?
  include 'header.php';
  ?>
  <main>

    <section class="wrapper event--page">
      <div class="event--page__title">
        <div class="event--page__title__flex">
          <h1 class="fs-36 fw8"><?= $json[1]->name ?></h1>
          <div class="event--page__title__flex__line">
            <p class="fs-16 fw7 label <?=$statusColor?>"><?=$status?></p>
            <div class="event--page__dates">
              <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M9.16667 2.5C5.02453 2.5 1.66667 5.85787 1.66667 10C1.66667 14.1422 5.02453 17.5 9.16667 17.5C13.3088 17.5 16.6667 14.1422 16.6667 10C16.6667 5.85787 13.3088 2.5 9.16667 2.5ZM0 10C0 4.93739 4.10406 0.833336 9.16667 0.833336C14.2292 0.833336 18.3333 4.93739 18.3333 10C18.3333 15.0626 14.2292 19.1667 9.16667 19.1667C4.10406 19.1667 0 15.0626 0 10ZM9.16667 4.16667C9.62692 4.16667 10 4.53977 10 5V9.485L12.8727 10.9213C13.2843 11.1272 13.4512 11.6277 13.2453 12.0393C13.0395 12.451 12.539 12.6178 12.1273 12.412L8.794 10.7453C8.51167 10.6042 8.33333 10.3157 8.33333 10V5C8.33333 4.53977 8.70642 4.16667 9.16667 4.16667Z"
                  fill="#868698" />
              </svg>
              <p class="fs-16 fw4"><?= $json[1]->date_from . ' - ' . $json[1]->date_to . '' ?></p>
            </div>
          </div>
          <button class="open-modal fs-14 fw7 btn">Регистрация</button>
        </div>
        <img src="<?= $json[1]->photo->path ?? 'https://dev-level.ru/assets/img/News.png' ?>" alt="">
      </div>
      <div class="event--page__links">
        <p class="fs-16 fw4">Поделиться:</p>
        <script async src="https://telegram.org/js/telegram-widget.js?22"
          data-telegram-share-url="https://final-hackathone.dev-level.ru/api/v1/event/<?= $event_id ?>"
          data-text="notext"></script>
      </div>
      <div class="event--page__content">
        <h2 class="fs-32 fw8">О мероприятии</h2>
        <p class="fs-16 fw4"><?= $json[1]->description ?></p>
      </div>

    </section>

    <style>
      .label.magenta {
        --color-cyan-33: #E91E63;
      }
      .label.purple {
        --color-cyan-33: #673AB7;
      }
    </style>








    <style>
    #calendar {
      max-width: 1200px;
      margin: 0 auto;
    }

    .fc-button-group {
      gap: 0.5rem !important;
    }

    .fc-gridCards-button,
    .fc-listMonth-button,
    .fc-dayGridMonth-button {
      padding: 0.625rem 1rem !important;
      border-radius: 0.25rem !important;
      background: transparent !important;
      border: var(--stroke-weight-1, 1px) solid #bdcfe4 !important;
      color: #2185fb !important;
      transition: all 0.3s ease-in-out !important;
      box-shadow: 0px 3px 5px 0px rgba(142, 165, 192, 0.20), 0px 6px 10px 0px rgba(142, 165, 192, 0.14), 0px 1px 18px 0px rgba(142, 165, 192, 0.12);
    }

    .fc .fc-button-primary {
      background: transparent !important;
      border: unset !important;
      color: #2185fb !important;
    }

    .fc-toolbar-title {
      color: #2185fb !important;
      text-transform: capitalize;
    }

    .fc-theme-standard .fc-scrollgrid {
      border: unset !important;
    }

    .fc-theme-standard :is(td, th) {
      border: unset !important;
    }

    .fc-col-header th {
      border-bottom: 1px solid #bdcfe4 !important;
      padding-bottom: 0.5rem !important;
    }

    .fc-day {
      padding: 0.5rem !important;
    }

    .fc .fc-daygrid-day-number {
      color: #7c8296 !important;
    }

    .calendar--event {
      all: unset !important;
    }

    .fc-event-title {
      color: #000 !important;
    }

    .fc-daygrid-day-events {
      display: flex;
      flex-direction: column;
    }

    .fc-event-title-container {
      padding: 0.125rem 0.75rem !important;
      border-radius: 3rem !important;
      background: #2185fb !important;
      margin-top: 0.25rem !important;
      cursor: pointer !important;
    }

    .fc-event-title-container .fc-event-title {
      color: #fff !important;
    }

    .single-day-event {
      all: unset !important;
    }

    .single-day-event .fc-event-title-container .fc-event-title {
      color: #000 !important;
    }

    .single-day-event .fc-event-title-container {
      all: unset !important;
    }

    .fc-daygrid-dot-event {
      margin-top: 0.25rem !important;
      display: flex !important;
      align-items: center !important;
    }

    .fc-event-time {
      color: #7c8296 !important;
    }

    .fc-daygrid-event-dot {
      border-color: #2185fb !important;
    }

    .fc-day-today {
      background: #cfe8ff !important;
    }

    :is(.fc-gridCards-button,
      .fc-listMonth-button,
      .fc-dayGridMonth-button):is(:hover, :focus) {
      background: #e1ebf7 !important;
      box-shadow: unset !important;
    }

    :is(.fc-gridCards-button,
      .fc-listMonth-button,
      .fc-dayGridMonth-button).fc-button-active {
      background: #2185fb !important;
      color: #fff !important;
    }

    .fc-list-event {
      display: flex !important;
      flex-direction: row !important;
      gap: 1rem !important;
      width: 100% !important;
    }

    .fc-list-event td {
      background: transparent !important;
    }

    .fc-list-event .fc-list-event-time {
      width: fit-content !important;
      flex-shrink: 0 !important;
      color: #7c8296 !important;
    }

    .fc-list-event .fc-list-event-title {
      flex: 1 !important;
      color: #000 !important;
    }

    .fc-list-event:is(:hover, :focus) {
      background: #e1ebf7 !important;
    }

    .fc-list-day th {
      background: #e1ebf7 !important;
      z-index: 1 !important;
    }

    .fc-scrollgrid-sync-table,
    tr[role="row"] {
      border-bottom: 1px solid #2185fb !important;
    }
    </style>
    <?php
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

    $json_region = file_get_contents_curl_region('https://final-hackathone.dev-level.ru/api/v1/events/' . $json[1]->agent->region_id);
    $data_region = $json_region[1];
    ?>

    <div class="wrapper" style="margin-bottom: 5rem;">
      <p style="margin-bottom:20px; margin-left: 20px" class="fw-800 fs-36 calendar-title">Календарь</p>
      <div id="calendar"></div>
      <div id="news-block" class="" style="display: none; gap:20px">
        <div class="calendar-news-grid">
          <?php
          if (is_array($data_region)) {
            foreach ($data_region as $value) {
          ?>
          <a href="/event/<?php echo $value->id ?>" class="news-item calendar-item">
            <img src="https://dev-level.ru/assets/img/News.png" alt="">
            <div class="calendar-item__inner">
              <div class="dates">
                <p class="dates__tag"><?=$value->date_from?></p>
              </div>
              <p class="text__title fw-500 fs-18">
                <?php echo $value->name ?>
              </p>
              <p class="text__descr fs-15">
                <?php
                    echo $value->short_description && mb_strlen($value->short_description) > 100
                      ? mb_substr($value->short_description, 0, 100) . '...'
                      : $value->short_description;
                    ?>
              </p>

              <div class="dates__date fs-12">
                </p class="">
                <?php
                    $date = new DateTime($value->date_from); // Преобразуем строку в DateTime
                    $formattedDate = $date->format('d.m.Y'); // Форматируем дату
                    echo $formattedDate
                    ?>
                <p>
                <div class="dates__address">
                  <p>
                    <?php
                        echo $value->address
                        ?>
                  </p>
                  <div class="dates__participants">
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g clip-path="url(#clip0_26_7429)">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M6.87989 3.6372C6.34946 3.6372 5.84075 3.84791 5.46568 4.22299C5.09061 4.59806 4.87989 5.10677 4.87989 5.6372C4.87989 6.16763 5.09061 6.67634 5.46568 7.05141C5.84075 7.42649 6.34946 7.6372 6.87989 7.6372C7.41033 7.6372 7.91904 7.42649 8.29411 7.05141C8.66918 6.67634 8.87989 6.16763 8.87989 5.6372C8.87989 5.10677 8.66918 4.59806 8.29411 4.22299C7.91904 3.84791 7.41033 3.6372 6.87989 3.6372ZM3.54689 5.6372C3.54689 4.75323 3.89805 3.90547 4.52311 3.28041C5.14817 2.65535 5.99593 2.3042 6.87989 2.3042C7.76386 2.3042 8.61162 2.65535 9.23668 3.28041C9.86174 3.90547 10.2129 4.75323 10.2129 5.6372C10.2129 6.52117 9.86174 7.36893 9.23668 7.99399C8.61162 8.61904 7.76386 8.9702 6.87989 8.9702C5.99593 8.9702 5.14817 8.61904 4.52311 7.99399C3.89805 7.36893 3.54689 6.52117 3.54689 5.6372ZM10.8999 2.8902C10.9439 2.71909 11.0541 2.57245 11.2061 2.48246C11.3582 2.39247 11.5397 2.36648 11.7109 2.4102C12.428 2.59366 13.0636 3.01059 13.5175 3.59525C13.9714 4.1799 14.2178 4.89902 14.2178 5.6392C14.2178 6.37937 13.9714 7.09849 13.5175 7.68315C13.0636 8.26781 12.428 8.68474 11.7109 8.8682C11.5415 8.90645 11.3638 8.87713 11.2157 8.78646C11.0676 8.69578 10.9607 8.55091 10.9177 8.38265C10.8747 8.21438 10.8991 8.03598 10.9856 7.88539C11.0721 7.73479 11.2139 7.62387 11.3809 7.5762C11.8111 7.46605 12.1924 7.21585 12.4647 6.86504C12.737 6.51424 12.8848 6.08278 12.8848 5.6387C12.8848 5.19461 12.737 4.76316 12.4647 4.41235C12.1924 4.06155 11.8111 3.81135 11.3809 3.7012C11.2098 3.65737 11.0631 3.54748 10.9729 3.39563C10.8828 3.24379 10.8565 3.06238 10.8999 2.8912V2.8902ZM1.85589 11.2802C2.16525 10.9704 2.53267 10.7246 2.93711 10.557C3.34155 10.3894 3.77508 10.3031 4.21289 10.3032H9.54689C9.98467 10.3032 10.4182 10.3894 10.8226 10.557C11.2271 10.7246 11.5945 10.9702 11.904 11.2798C12.2135 11.5894 12.459 11.9569 12.6265 12.3614C12.7939 12.7659 12.88 13.1994 12.8799 13.6372V14.9702C12.8732 15.1425 12.8001 15.3055 12.6758 15.4251C12.5516 15.5446 12.3858 15.6114 12.2134 15.6114C12.041 15.6114 11.8752 15.5446 11.751 15.4251C11.6267 15.3055 11.5536 15.1425 11.5469 14.9702V13.6372C11.5469 13.1068 11.3362 12.5981 10.9611 12.223C10.586 11.8479 10.0773 11.6372 9.54689 11.6372H4.21289C3.68246 11.6372 3.17375 11.8479 2.79868 12.223C2.42361 12.5981 2.21289 13.1068 2.21289 13.6372V14.9702C2.21637 15.0599 2.2017 15.1494 2.16977 15.2333C2.13785 15.3172 2.08931 15.3938 2.02708 15.4584C1.96484 15.5231 1.89019 15.5746 1.8076 15.6097C1.725 15.6449 1.63616 15.663 1.54639 15.663C1.45663 15.663 1.36779 15.6449 1.28519 15.6097C1.2026 15.5746 1.12794 15.5231 1.06571 15.4584C1.00348 15.3938 0.954944 15.3172 0.923015 15.2333C0.891086 15.1494 0.876419 15.0599 0.879895 14.9702V13.6372C0.879895 12.7532 1.23089 11.9052 1.85589 11.2802ZM13.5679 10.8902C13.6121 10.7191 13.7225 10.5725 13.8748 10.4827C14.027 10.3928 14.2087 10.3671 14.3799 10.4112C15.0953 10.5953 15.7291 11.0121 16.1817 11.5959C16.6343 12.1798 16.8799 12.8975 16.8799 13.6362V14.9702C16.8732 15.1425 16.8001 15.3055 16.6758 15.4251C16.5516 15.5446 16.3858 15.6114 16.2134 15.6114C16.041 15.6114 15.8752 15.5446 15.751 15.4251C15.6267 15.3055 15.5536 15.1425 15.5469 14.9702V13.6372C15.5466 13.1941 15.3991 12.7636 15.1276 12.4134C14.8561 12.0631 14.476 11.813 14.0469 11.7022C13.8756 11.6582 13.7287 11.5479 13.6387 11.3956C13.5487 11.2433 13.5239 11.0615 13.5679 10.8902Z"
                          fill="#7A818B" />
                      </g>
                      <defs>
                        <clipPath id="clip0_26_7429">
                          <rect width="16" height="16" fill="white" transform="translate(0.879883 0.970215)" />
                        </clipPath>
                      </defs>
                    </svg>
                    <p>
                      <?php
                          echo $value->participants
                          ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </a>
          <?
            }
          }
          ?>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
    const newsBlockEl = document.getElementById("news-block");
    let dataEvents = JSON.parse(<?php echo $json_region[0]; ?>);

    console.log(dataEvents);
    let dataEventForCalendar = dataEvents.map((event) => {
      return {
        title: event.name,
        start: event.date_from,
        end: event.date_to ? event.date_to : '',
        url: `/event/${event.id}`,
      }
    });
    console.log(dataEventForCalendar)
    document.addEventListener("DOMContentLoaded", function() {
      const calendarEl = document.getElementById("calendar");
      const calendar = new FullCalendar.Calendar(calendarEl, {
        firstDay: 1,
        // dateClick: function() {
        //     alert("a day has been clicked!");
        // },
        // eventClick: function() {
        //     alert("an event has been clicked!");
        // },
        initialView: "dayGridMonth",
        locale: "ru",
        headerToolbar: {
          left: "prev,next",
          center: "title",
          right: "dayGridMonth,listMonth,gridCards",
        },
        buttonText: {
          dayGridMonth: "Месяц",
          listMonth: "Список",
        },
        customButtons: {
          gridCards: {
            text: "Карточки",
            click: function() {
              calendarEl.style.display = "none";
              newsBlockEl.style.display = "grid";
            },
          },
        },
        noEventsContent: "В данный момент нет нужный событий",
        eventClassNames: function(info) {
          // Если мероприятие однодневное
          if (info.event.start && !info.event.end) {
            return "single-day-event"; // Добавляем кастомный класс
          }
          return "calendar--event";
        },
        dayMaxEvents: 3,
        events: dataEventForCalendar,
      });
      // calendar.on("dateClick", function(info) {
      //     console.log("clicked on " + info.dateStr);
      // });
      // calendar.on("eventClick", function(info) {
      //     console.log(info.event.title);
      // });

      const backToCalendarButton = document.createElement("button");
      backToCalendarButton.textContent = "Перейти к календарю";
      backToCalendarButton.style.cursor = "pointer";
      backToCalendarButton.classList.add('calendar-btn', 'btn', 'fs-14', 'fw-700')
      newsBlockEl.appendChild(backToCalendarButton);

      backToCalendarButton.addEventListener("click", function() {
        newsBlockEl.style.display = "none";
        calendarEl.style.display = "grid";
        window.scrollTo({
          top: calendarEl.offsetTop - 100,
          behavior: 'smooth'
        });
      });

      calendar.render();
    });
    </script>
  </main>

  <?
  include 'footer.php';
  ?>
  <script src="https://dev-level.ru/assets/js/script.js"></script>
  <script src="https://dev-level.ru/assets/js/map.js"></script>
  <script src="https://dev-level.ru/assets/js/accordion.js"></script>
</body>

</html>