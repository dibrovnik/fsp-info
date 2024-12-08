document.addEventListener("DOMContentLoaded", () => {
  var $ = jQuery;
  const burgerIcon = document.querySelector(".__burger_icon");
  const extraButtons = document.querySelector(".__extra_buttons");

  // Функция для закрытия меню
  const closeMenu = () => {
    burgerIcon.classList.remove("open");
    extraButtons.classList.remove("open");
  };

  // Обработчик клика по иконке бургер-меню
  burgerIcon.addEventListener("click", (e) => {
    e.stopPropagation(); // Предотвращаем закрытие меню при клике на иконку
    burgerIcon.classList.toggle("open");
    extraButtons.classList.toggle("open");
  });

  // Обработчик клика вне меню
  document.addEventListener("click", (e) => {
    if (!extraButtons.contains(e.target) && !burgerIcon.contains(e.target)) {
      closeMenu();
    }
  });

  // Обработчик прокрутки страницы
  window.addEventListener("scroll", () => {
    closeMenu();
  });
});
