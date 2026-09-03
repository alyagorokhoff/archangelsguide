(() => {
  "use strict";

  const isEditable = (target) =>
    target instanceof Element &&
    Boolean(target.closest("input, textarea, select, [contenteditable='true']"));

  const stopOutsideFields = (event) => {
    if (!isEditable(event.target)) event.preventDefault();
  };

  const style = document.createElement("style");
  style.textContent = `
    html, body, main, section, article, img {
      -webkit-user-select: none;
      user-select: none;
      -webkit-touch-callout: none;
    }
    img {
      -webkit-user-drag: none;
      user-drag: none;
    }
    input, textarea, select, [contenteditable="true"] {
      -webkit-user-select: text;
      user-select: text;
      -webkit-touch-callout: default;
    }
  `;
  document.head.appendChild(style);

  document.addEventListener("copy", stopOutsideFields);
  document.addEventListener("cut", stopOutsideFields);
  document.addEventListener("contextmenu", stopOutsideFields);
  document.addEventListener("dragstart", stopOutsideFields);
  document.addEventListener("selectstart", stopOutsideFields);

  document.addEventListener("keydown", (event) => {
    if (isEditable(event.target)) return;
    const key = event.key.toLowerCase();
    if ((event.ctrlKey || event.metaKey) && ["c", "x", "s", "u", "p"].includes(key)) {
      event.preventDefault();
    }
  });
})();
