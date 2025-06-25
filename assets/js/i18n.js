// --- FUNCIÓN displayTermsAndConditions MODIFICADA ---
// Ahora toma un 'targetElementId' como argumento opcional
function displayTermsAndConditions(termsContentObject, targetElementId = 'terms-content') {
  if (!termsContentObject || !termsContentObject.title_general || !termsContentObject.conten) {
    console.error("Datos de términos y condiciones no encontrados o mal estructurados.");
    return;
  }

  const { title_general, conten } = termsContentObject;

  // Intentar encontrar el contenedor específico o el body como fallback
  let targetElement = document.getElementById(targetElementId);
  if (!targetElement) {
    // Si el ID especificado no existe, se busca un contenedor existente
    // con la clase '.terms-container' o se crea uno nuevo en el body.
    // Esto es un ajuste para evitar adjuntar directamente al body sin un contenedor.
    targetElement = document.querySelector('.terms-container') || document.body;
    console.warn(`Elemento con id '${targetElementId}' no encontrado. Se usará el primer '.terms-container' o el 'body'.`);
  }

  // Eliminar el contenido anterior solo dentro del targetElement para evitar duplicados.
  // Es crucial limpiar SOLO el contenedor donde se van a insertar los nuevos términos.
  // Si el targetElement ya es '.terms-container', limpiamos ese.
  // Si es el 'body', podríamos crear un '.terms-container' interno para limpiarlo después.
  let termsContainer = targetElement.querySelector('.terms-container');
  if (termsContainer) {
    termsContainer.innerHTML = ''; // Limpiar su contenido
  } else {
    termsContainer = document.createElement('div');
    termsContainer.className = 'terms-container';
    targetElement.appendChild(termsContainer);
  }


  // Agrega el título general
  const titleElement = document.createElement('h1');
  titleElement.textContent = title_general;
  termsContainer.appendChild(titleElement);

  // Itera sobre cada sección de los términos y condiciones
  conten.forEach(section => {
    const sectionDiv = document.createElement('div');
    sectionDiv.className = 'term-section';

    const sectionTitle = document.createElement('h2');
    sectionTitle.textContent = section.titulo;
    sectionDiv.appendChild(sectionTitle);

    const sectionContent = document.createElement('p');
    // Reemplaza los saltos de línea literales '\n' con etiquetas <br> para HTML
    sectionContent.innerHTML = section.contenido.replace(/\n/g, '<br>');
    sectionDiv.appendChild(sectionContent);

    termsContainer.appendChild(sectionDiv);
  });
}

// --- OBJETO i18n MODIFICADO ---
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

      // ¡IMPORTANTE! Eliminamos la llamada automática aquí:
      // this.loadAndDisplayTerms();

    } catch (error) {
      console.error("Error en i18n.init():", error);
      alert(
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
      // console.log(`Traducciones cargadas para ${lang}:`, this.translations); // <--- AGREGAR ESTO
    } catch (error) {
      console.error(`Error cargando traducciones para ${lang}:`, error);
      alert(
        `Error cargando traducciones para ${lang}. Verifica que el archivo ${lang}.json exista y tenga el formato correcto.`
      );
    }
  },

  t(key) {
    const keys = key.split('.');
    let value = this.translations;
    for (let i = 0; i < keys.length; i++) {
      if (value && typeof value === 'object' && value.hasOwnProperty(keys[i])) {
        value = value[keys[i]];
      } else {
        return key;
      }
    }
    return value;
  },

  async changeLang(lang) {
    this.currentLang = lang;
    this.setLanguageCookie(lang);
    this.saveLanguageToStorage(lang);

    await this.loadTranslations(lang);
    this.translatePage();

    // --- ¡Esta línea es clave! Asegúrate de que no se haya comentado o movido. ---
    window.dispatchEvent(
      new CustomEvent("languageChanged", { detail: { language: lang } })
    );

    // --- NUEVA VALIDACIÓN AQUÍ ---
    const currentUrl = window.location.href;
    const sorteoUrl = 'http://turifadigital.local/sorteo';

    if (currentUrl === sorteoUrl) {
      window.location.reload(); // Recarga solo si estás en la URL del sorteo
    }

    // ¡IMPORTANTE! Eliminamos la llamada automática aquí:
    // this.loadAndDisplayTerms(); // Ya no se llama automáticamente

    // Si quieres que los términos se actualicen SOLAMENTE si ya están visibles,
    // podrías añadir una lógica aquí, pero la idea es que tú controles cuándo se llaman.
    // Por ejemplo: if (document.getElementById('my-terms-modal').style.display === 'block') { this.loadAndDisplayTerms(); }
  },

  // --- FUNCIÓN loadAndDisplayTerms (ahora con argumento targetElementId) ---
  loadAndDisplayTerms(targetElementId = 'terms-content') {
    const termsContent = this.translations.terms_conditions_conten;
    if (typeof termsContent === 'object' && termsContent !== null) {
      displayTermsAndConditions(termsContent, targetElementId); // Pasa el ID al displayer
    } else {
      console.warn("No se encontraron los datos de términos y condiciones para el idioma actual en las traducciones cargadas.");
    }
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

// Inicia el sistema de i18n cuando el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  i18n.init();
});

// El bloque de "languageSwitcher" va aquí, dentro del DOMContentLoaded listener
document.addEventListener("DOMContentLoaded", () => {
  const languageSwitcher = document.getElementById(
    "language-selector-dropdown"
  );

  if (languageSwitcher) {
    const savedLang = localStorage.getItem("language") || "es";
    const defaultText = languageSwitcher.querySelector(".default.text");
    const hiddenInput = languageSwitcher.querySelector('input[type="hidden"]');
    const menu = languageSwitcher.querySelector(".menu");

    hiddenInput.value = savedLang;
    defaultText.textContent = savedLang.toUpperCase();

    languageSwitcher.addEventListener("click", function (e) {
      e.stopPropagation();
      this.classList.toggle("active");
      menu.style.display = this.classList.contains("active") ? "block" : "none";
    });

    languageSwitcher.querySelectorAll(".item").forEach((item) => {
      item.addEventListener("click", function (e) {
        e.stopPropagation();
        const selectedLang = this.getAttribute("data-value");

        defaultText.textContent = selectedLang.toUpperCase();
        hiddenInput.value = selectedLang;

        localStorage.setItem("language", selectedLang);
        i18n.changeLang(selectedLang); // Esta llamada ya se encarga de todo

        languageSwitcher.classList.remove("active");
        menu.style.display = "none";
      });
    });

    document.addEventListener("click", function (e) {
      if (!languageSwitcher.contains(e.target)) {
        languageSwitcher.classList.remove("active");
        menu.style.display = "none";
      }
    });
  } else {
    console.log("language-selector-dropdown no encontrado en el DOM.");
  }
});