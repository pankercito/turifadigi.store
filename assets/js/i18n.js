// Sistema de traducciones
document.addEventListener("DOMContentLoaded", () => {
  const languageSwitcher = document.getElementById(
    "language-selector-dropdown"
  );

  if (languageSwitcher) {
    const savedLang = localStorage.getItem("language") || "es";
    const defaultText = languageSwitcher.querySelector(".default.text");
    const hiddenInput = languageSwitcher.querySelector('input[type="hidden"]');
    const menu = languageSwitcher.querySelector(".menu"); // Cache the menu element

    hiddenInput.value = savedLang;
    defaultText.textContent = savedLang.toUpperCase();
    i18n.changeLang(savedLang);

    languageSwitcher.addEventListener("click", function (e) {
      e.stopPropagation();
      this.classList.toggle("active");
      menu.style.display = this.classList.contains("active") ? "block" : "none"; // Use cached menu
    });

    languageSwitcher.querySelectorAll(".item").forEach((item) => {
      item.addEventListener("click", function (e) {
        e.stopPropagation();
        const selectedLang = this.getAttribute("data-value");

        defaultText.textContent = selectedLang.toUpperCase();
        hiddenInput.value = selectedLang;

        localStorage.setItem("language", selectedLang);
        i18n.changeLang(selectedLang);

        languageSwitcher.classList.remove("active");
        menu.style.display = "none"; // Use cached menu
      });
    });

    document.addEventListener("click", function (e) {
      if (!languageSwitcher.contains(e.target)) {
        languageSwitcher.classList.remove("active");
        menu.style.display = "none"; // Use cached menu
      }
    });
  } else {
    console.log("language-selector-dropdown no encontrado en el DOM.");
  }
});

const i18n = {
  translations: {},
  currentLang: "es",

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

  saveLanguageToStorage(lang) {
    localStorage.setItem("language", lang);
  },

  getLanguageFromStorage() {
    return localStorage.getItem("language");
  },

  async init() {
    try {
      const [esResponse, enResponse] = await Promise.all([
        fetch("assets/language/es.json"),
        fetch("assets/language/en.json"),
      ]);

      if (!esResponse.ok || !enResponse.ok) {
        throw new Error("No se encontraron los archivos de traducción");
      }

      const cookieLang = this.getLanguageCookie();
      const storageLang = this.getLanguageFromStorage();
      this.currentLang = cookieLang || storageLang || "es";

      await this.loadTranslations(this.currentLang);
      this.translatePage();
    } catch (error) {
      console.warn(
        "No se encontraron los archivos de traducción. Por favor, verifica que existan es.json y en.json en la carpeta assets/language/"
      );
    }
  },

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

  t(key) {
    return this.translations[key] || key;
  },

  async changeLang(lang) {
    this.currentLang = lang;
    this.setLanguageCookie(lang);
    this.saveLanguageToStorage(lang);

    await this.loadTranslations(lang);
    this.translatePage();

    window.dispatchEvent(
      new CustomEvent("languageChanged", { detail: { language: lang } })
    );

    // Recargar la página después de cambiar el idioma
    window.location.reload();
  },

  translatePage() {
    document.querySelectorAll("[data-i18n]").forEach((element) => {
      element.textContent = this.t(element.getAttribute("data-i18n"));
    });

    document.querySelectorAll("table th[data-i18n-th]").forEach((th) => {
      th.textContent = this.t(th.getAttribute("data-i18n-th"));
    });

    document.querySelectorAll("[data-i18n-placeholder]").forEach((element) => {
      element.placeholder = this.t(element.getAttribute("data-i18n-placeholder"));
    });

    document.querySelectorAll("[data-i18n-title]").forEach((element) => {
      element.title = this.t(element.getAttribute("data-i18n-title"));
    });

    document.querySelectorAll("[data-i18n-html]").forEach((element) => {
      element.innerHTML = this.t(element.getAttribute("data-i18n-html"));
    });

    document.querySelectorAll("span[data-msg-key]").forEach((span) => {
      const key = span.getAttribute("data-msg-key");
      if (key) {
        span.textContent = this.t(key);
      }
    });
  },
};

document.addEventListener("DOMContentLoaded", () => {
  i18n.init();
});

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
