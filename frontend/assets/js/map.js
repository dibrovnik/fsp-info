// document.addEventListener("DOMContentLoaded", () => {
//   const regions = document.querySelectorAll(".cls-1");
//   const tooltip = document.getElementById("tooltip");

//   const windowWidth = window.screen.availWidth;
//   console.log(windowWidth);

//   regions.forEach((region) => {
//     if (windowWidth < 768) {
//       region.addEventListener("click", function (el) {
//         el.preventDefault();
//       });
//     }

//     region.addEventListener("mouseover", (e) => {
//       const regionName = region.getAttribute("data-region-name");
//       const eventsCount = region.getAttribute("data-events-count"); // Количество мероприятий

//       // Формируем содержимое всплывающего окна
//       if (windowWidth < 768) {
//         tooltip.innerHTML = `
//         <p class="fs-20 title"> ${regionName}</p>
//         <p class="fs-16"><span class="fs-20">${eventsCount} </span> Активных мероприятий </p>
//         <a href="/" class="tooltip-link">Смотреть мероприятия</a>
//         `;
//       } else {
//         tooltip.innerHTML = `
//         <p class="fs-20 title"> ${regionName}</p>
//         <p class="fs-16"> <span class="fs-20">${eventsCount} </span> Активных мероприятий </p>
//         `;
//       }

//       tooltip.style.pointerEvents = "auto"; // Включаем обработку событий для ссылки
//       tooltip.classList.add("show");
//     });

//     region.addEventListener("mousemove", (e) => {
//       const tooltipWidth = tooltip.offsetWidth; // Получаем ширину всплывающего окна
//       const positionX = e.pageX + 20;

//       // Если курсор близко к правому краю экрана, показываем окно слева от курсора
//       if (positionX + tooltipWidth > window.innerWidth) {
//         tooltip.style.left = `${e.pageX - tooltipWidth - 10}px`; // Позиция слева от курсора
//       } else {
//         tooltip.style.left = `${positionX}px`; // Позиция справа от курсора
//       }

//       tooltip.style.top = `${e.pageY + 10}px`;
//     });

//     region.addEventListener("mouseout", () => {
//       // Скрываем всплывающее окно только если мышь не находится над ним
//       tooltip.classList.remove("show");
//     });

//     // Добавляем обработчик события на сам tooltip, чтобы не скрывать его, когда пользователь наводит мышь
//     tooltip.addEventListener("mouseover", () => {
//       tooltip.classList.add("show");
//     });

//     tooltip.addEventListener("mouseout", () => {
//       tooltip.classList.remove("show");
//     });
//   });
// });
