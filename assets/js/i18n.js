// Sistema de traducciones

document.addEventListener("DOMContentLoaded", () => {
  const languageSwitcher = document.getElementById(
    "language-selector-dropdown"
  );

  if (languageSwitcher) {
    // Obtener el idioma guardado o usar español por defecto
    const savedLang = localStorage.getItem("language") || "es";

    // Establecer el valor inicial del dropdown
    const defaultText = languageSwitcher.querySelector(".default.text");
    const items = languageSwitcher.querySelectorAll(".item");
    const hiddenInput = languageSwitcher.querySelector('input[type="hidden"]');

    // Configurar el valor inicial
    hiddenInput.value = savedLang;
    defaultText.textContent = savedLang.toUpperCase();

    // Cambiar el idioma al cargar la página
    i18n.changeLang(savedLang);

    // Manejar clic en el dropdown
    languageSwitcher.addEventListener("click", function (e) {
      e.stopPropagation();
      this.classList.toggle("active");
      const menu = this.querySelector(".menu");
      menu.style.display = this.classList.contains("active") ? "block" : "none";
    });

    // Manejar selección de idioma
    items.forEach((item) => {
      item.addEventListener("click", function (e) {
        e.stopPropagation();
        const selectedLang = this.getAttribute("data-value");

        // Actualizar el texto visible
        defaultText.textContent = selectedLang.toUpperCase();
        hiddenInput.value = selectedLang;

        // Guardar y cambiar el idioma
        localStorage.setItem("language", selectedLang);
        i18n.changeLang(selectedLang);

        // Cerrar el dropdown
        languageSwitcher.classList.remove("active");
        languageSwitcher.querySelector(".menu").style.display = "none";

        // Recargar la página después de cambiar el idioma
        window.location.reload();
      });
    });

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener("click", function (e) {
      if (!languageSwitcher.contains(e.target)) {
        languageSwitcher.classList.remove("active");
        languageSwitcher.querySelector(".menu").style.display = "none";
      }
    });
  } else {
    console.log("language-selector-dropdown no encontrado en el DOM.");
  }
});

const i18n = {
  // Almacena las traducciones
  translations: {},
  // Idioma actual
  currentLang: "es",

  // Funciones de manejo de cookies
  setLanguageCookie(lang) {
    document.cookie = `language=${lang}; max-age=${365 * 24 * 60 * 60}; path=/`;
  },

  getLanguageCookie() {
    const name = "language=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length);
      }
    }
    return null;
  },

  // Funciones de manejo de localStorage
  saveLanguageToStorage(lang) {
    localStorage.setItem("language", lang);
  },

  getLanguageFromStorage() {
    return localStorage.getItem("language");
  },

  // Inicializar el sistema
  async init() {
    try {
      const esResponse = await fetch("assets/language/es.json");
      const enResponse = await fetch("assets/language/en.json");

      if (!esResponse.ok || !enResponse.ok) {
        throw new Error("No se encontraron los archivos de traducción");
      }

      // Intentar obtener el idioma de las cookies o localStorage
      const cookieLang = this.getLanguageCookie();
      const storageLang = this.getLanguageFromStorage();

      // Establecer el idioma actual con prioridad: cookie > localStorage > español por defecto
      this.currentLang = cookieLang || storageLang || "es";

      // Cargar las traducciones
      await this.loadTranslations(this.currentLang);

      // Traducir la página
      this.translatePage();
    } catch (error) {
      console.warn(
        "No se encontraron los archivos de traducción. Por favor, verifica que existan es.json y en.json en la carpeta assets/language/"
      );
    }
  },

  // Cargar traducciones desde el archivo JSON
  async loadTranslations(lang) {
    try {
      const response = await fetch(`assets/language/${lang}.json`);
      if (!response.ok) {
        throw new Error(`No se encontró el archivo ${lang}.json`);
      }
      this.translations = await response.json();
    } catch (error) {
      console.warn(
        `Error cargando traducciones para ${lang}. Verifica que el archivo ${lang}.json exista y tenga el formato correcto.`
      );
    }
  },

  // Obtener una traducción
  t(key) {
    return this.translations[key] || key;
  },

  // Cambiar el idioma
  async changeLang(lang) {
    this.currentLang = lang;

    // Guardar en cookie y localStorage
    this.setLanguageCookie(lang);
    this.saveLanguageToStorage(lang);

    await this.loadTranslations(lang);
    this.translatePage();

    // Disparar evento de cambio de idioma
    window.dispatchEvent(
      new CustomEvent("languageChanged", { detail: { language: lang } })
    );
  },

  // Traducir la página
  translatePage() {
    // Traducir elementos con atributo data-i18n
    document.querySelectorAll("[data-i18n]").forEach((element) => {
      const key = element.getAttribute("data-i18n");
      element.textContent = this.t(key);
    });

    // Traducir elementos th dentro de tablas con atributo data-i18n-th
    document.querySelectorAll("table th[data-i18n-th]").forEach((th) => {
      const key = th.getAttribute("data-i18n-th");
      th.textContent = this.t(key);
    });

    // Traducir placeholders
    document.querySelectorAll("[data-i18n-placeholder]").forEach((element) => {
      const key = element.getAttribute("data-i18n-placeholder");
      element.placeholder = this.t(key);
    });

    // Traducir títulos
    document.querySelectorAll("[data-i18n-title]").forEach((element) => {
      const key = element.getAttribute("data-i18n-title");
      element.title = this.t(key);
    });

    // Traducir contenido HTML
    document.querySelectorAll("[data-i18n-html]").forEach((element) => {
      const key = element.getAttribute("data-i18n-html");
      element.innerHTML = this.t(key);
    });

    // Traducir mensajes de validación dinámicos
    document.querySelectorAll("span[data-msg-key]").forEach((span) => {
      const key = span.getAttribute("data-msg-key");
      if (key) {
        span.textContent = this.t(key);
      }
    });
  },
};

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
  i18n.init();
});

//const idiomas = {
//  ES: "es",
//  EN: "en",
//};

// Función para obtener el texto según el idioma actual
function getTextByLanguage(jsonText) {
  try {
    if (typeof jsonText === "string") {
      const texts = JSON.parse(jsonText);
      const currentLang = localStorage.getItem("language") || "es";
      const langKey = currentLang.toUpperCase();
      return texts[langKey] || texts["ES"] || Object.values(texts)[0];
    }
    return jsonText;
  } catch (e) {
    console.error("Error al parsear texto JSON:", e);
    return jsonText;
  }
}
