document.querySelectorAll(".accordion-header").forEach((header) => {
  header.addEventListener("click", () => {
    const content = header.nextElementSibling;
    const icon = header.querySelector(".icon");

    if (content.classList.contains("open")) {
      content.classList.remove("open");
      icon.classList.remove("rotate");
    } else {
      // Close other open items
      document
        .querySelectorAll(".accordion-content.open")
        .forEach((openContent) => {
          openContent.classList.remove("open");
          openContent.previousElementSibling
            .querySelector(".icon")
            .classList.remove("rotate");
        });
      content.classList.add("open");
      icon.classList.add("rotate");
    }
  });
});
