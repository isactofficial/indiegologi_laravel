function googleTranslateElementInit() {
    console.log("SUCCESS: Google Translate script is ready.");
  
    var config = {
      pageLanguage: 'id',
      includedLanguages: 'id,en',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    };
  
    // Render untuk Desktop
    if (document.getElementById('google_translate_element_desktop')) {
      new google.translate.TranslateElement(config, 'google_translate_element_desktop');
    }
  
    // Render untuk Mobile
    if (document.getElementById('google_translate_element_mobile')) {
      new google.translate.TranslateElement(config, 'google_translate_element_mobile');
    }
  }