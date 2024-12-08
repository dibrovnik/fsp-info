# FSP INFO — Платформа для взаимодействия центральных и региональных представительств Федерации спортивного программирования

## Описание проекта

**FSP INFO** — это централизованная платформа для улучшения взаимодействия между центральными и 89 региональными представительствами Федерации спортивного программирования. Платформа автоматизирует процессы подачи и обработки заявок, управления мероприятиями, а также повышает прозрачность и эффективность работы Федерации. Включает инструменты аналитики и уведомлений, улучшая координацию на всех уровнях.

## Проблематика

Федерация спортивного программирования сталкивается с проблемой чрезмерного объема коммуникаций между центральным и региональными представительствами. Это приводит к потере времени и ресурсов, замедляет принятие решений и усложняет планирование мероприятий.

### Основные проблемы:
- Чрезмерный объем коммуникаций между регионами и центральным офисом.
- Замедленное принятие решений из-за несогласованности процессов.
- Сложности с планированием мероприятий.
- Недостаток аналитики для оценки эффективности работы регионов и событий.

## Решение

### Ссылки
- https://final-hackathone.dev-level.ru/ - личные кабинеты регионального и федерального представителя
- https://dev-level.ru/ - пользовательский сайт
- https://drive.google.com/drive/folders/1Dp8r3Z3tLQ_250XhECjb1UA9IHxXcju2 - диск с скринкастом, презентацией и скриншотами
- https://app.swaggerhub.com/apis/pankinnkt/your-api/1.0.0#/default - файл документации к API

### Доступы
Федеральный представитель:
- логин: dibrovnik@yandex.ru
- пароль: 26122004

Представитель Владимирской области:
- логин: vladimir@vladimir.ru
- пароль: 123456789

Представитель Ставропольский край:
- логин: mrssss1@gmail.com
- пароль: T6noFPWm




Платформа **FSP INFO** решает эти проблемы, предлагая:
- Централизованное управление заявками.
- Общий календарь мероприятий с фильтрацией по регионам и типам.
- Аналитические инструменты для построения отчетов.
- Интерактивную карту регионов и события, актуализируемую в реальном времени.

## Основные функциональные блоки

### 1. Централизованный портал
- Личный кабинет для региональных и центральных представителей.
- Управление заявками через единый сервис.
- Интерактивная карта с актуальными данными о регионах и мероприятиях.

### 2. Автоматизация процессов
- Подача заявок на мероприятия через форму с прикреплением файлов.
- Синхронизация данных и обновление статистики в реальном времени.
- Уведомления через Telegram-бота.

### 3. Аналитика и отчетность
- Генерация отчетов по регионам, командам и участникам.
- Визуализация данных в графиках и таблицах.
- Возможность экспорта отчетов в PDF.

### 4. Интерактивные функции
- Реализация мессенджера для быстрого общения между регионами и центральным офисом.
- Фото-верификация региональных представителей.
- Мультизагрузка результатов соревнований.

### 5. Телеграм-бот
- Уведомления о статусах заявок и изменениях в календаре.
- Отправка уведомлений о новых мероприятиях и событиях.

## Меню в личных кабинетах

### **Федеральный представитель**:
- **Профиль** — управление личной информацией.
- **Аналитика** — просмотр отчетов по регионам, командам и участникам.
- **Отправить заявку** — подача заявки на мероприятия.
- **Создать новость** — создание новостей для региональных представительств.
- **Отправленные заявки** — просмотр статуса отправленных заявок.
- **Загрузить результаты** — загрузка итогов соревнований.
- **Верификация аккаунта** — процесс подтверждения подлинности аккаунта.
- **Отчет по мероприятию** — создание отчетов по конкретным мероприятиям.
- **Отчет по округу** — отчеты по активности и результатам по регионам.
- **Создать нового представителя** — создание новых аккаунтов региональных представителей.

### **Региональный представитель**:
- **Профиль** — управление личной информацией.
- **Аналитика** — просмотр отчетов по мероприятию.
- **Отправить заявку** — подача заявки на мероприятия.
- **Создать новость** — создание новостей для региона.
- **Отправленные заявки** — просмотр статуса отправленных заявок.
- **Загрузить результаты** — загрузка итогов соревнований.
- **Верификация аккаунта** — процесс подтверждения подлинности аккаунта.
- **Отчет по мероприятию** — создание отчетов по мероприятиям региона.

## Архитектура

Проект построен на основе **микросервисной архитектуры**, что позволяет эффективно масштабировать платформу и интегрировать новые модули и функции по мере необходимости. 

Для связи между модулями используется **REST API**, что обеспечивает стандартизированный способ взаимодействия между различными компонентами системы.

### Стек технологий

#### **Frontend**:
- **HTML** — для построения структуры веб-страниц.
- **SASS/SCSS** — для написания стилей, что позволяет использовать возможности CSS-препроцессоров для улучшения организации стилей.
- **Chart.js** — для визуализации данных в виде графиков и диаграмм.
- **FullCalendar.io** — для отображения календаря событий и мероприятий.

#### **Backend**:
- **PHP, Laravel** — основной стек для серверной части, обеспечивающий мощный фреймворк для разработки веб-приложений с поддержкой MVC, маршрутизации и ORM.
- **MySQL** — база данных для хранения всей информации о пользователях, заявках, мероприятиях и аналитике.
- **Apache** — веб-сервер для обработки HTTP-запросов и обслуживания приложений.
- **Swagger** — для документирования и тестирования REST API, что позволяет разработчикам легко работать с API и интегрировать его в другие системы.

#### **Другие технологии**:
- **Python, Flask, Jinja2** — для реализации некоторых микросервисов и API, а также для генерации отчетов в формате PDF и обработки запросов.
- **PDFKid** — для работы с PDF-документами, например, для создания отчетов и выгрузки результатов.
- **Requests** — для отправки HTTP-запросов и интеграции с внешними сервисами.

#### **Дополнительные инструменты**:
- **CDN** — для доставки статического контента (например, изображений и стилей) с максимальной скоростью.
- **UML** — для проектирования архитектуры и моделирования взаимодействий между компонентами системы.
- **Insomnia** — для тестирования и отладки REST API.

## Связь между модулями

Для обеспечения бесшовной связи между различными модулями системы используется **REST API**, что позволяет каждому компоненту (например, модулю заявок, календаря или аналитики) взаимодействовать с другими компонентами через стандартизированные HTTP-запросы. Это упрощает интеграцию внешних сервисов и позволяет разрабатывать каждый микросервис независимо от других.

## REST API

Платформа предоставляет полноценный **REST API** с готовой документацией для интеграции с внешними системами. API позволяет управлять заявками, мероприятиями, пользователями и данными аналитики.

## Виджет расписания

Мы также разработали **виджет расписания**, который можно встроить на любой сайт. Это позволяет отображать актуальные мероприятия ФСП на сторонних ресурсах с обновлениями в реальном времени.

## Безопасность

Для повышения безопасности личный кабинет представителя и пользовательский сайт разнесены по разным доменам:
- **Пользовательский сайт:** [https://dev-level.ru/](https://dev-level.ru/)
- **Кабинет для взаимодействия представителей:** [https://final-hackathone.dev-level.ru/](https://final-hackathone.dev-level.ru/)

## План развития продукта

### 1-3 месяц:
- Завершение разработки личных кабинетов и интеграции ЕКП.
- Реализация функционала подачи и подтверждения заявок.
- Синхронизация данных и интеграция аналитических модулей.

### 4-6 месяц:
- Оптимизация аналитических инструментов.
- Оптимизация взаимодействия через чат и интерактивные элементы.

### 7-9 месяц:
- Внедрение ИИ для быстрой загрузки документов о результатах соревнований.
- Масштабирование и оптимизация производительности платформы.

## Киллер-фичи
- **Интерактивная карта** с актуальной информацией о мероприятиях в регионах.
- **Знак актуальности данных** — для стимуляции поддержания актуальности данных.
- **Уведомления в телеграмме** — просмотр уведомлений не отрываясь от обычного флоу.
- **Мессенджер внутри заявки** для коммуникации между представителями и центральным офисом. Параллельно доставляем сообщения в телеграмм.
- **Виджет расписания** для встраивания на любые сайты.
- **REST API** с документацией для интеграции в сторонние сервисы, включая множество существующих сайтов ФСП.
- **Хранение информации о победителях** для создания базы данных специалистов. Также вывод победителей в отчете.
- **Верификация региональных представителей** при помощи фотографии с листиком для повышения безопасности.

## Заключение

**FSP INFO** — это платформа, которая объединяет регионы и центральный офис ФСП, автоматизирует процессы и улучшает координацию. Платформа сокращает время на обработку заявок и повышает прозрачность в принятии решений, что способствует развитию спортивного программирования в России.

