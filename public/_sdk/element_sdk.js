(function(){
  const STORAGE_KEY = 'koc_element_config';
  let defaultConfig = {};
  let currentConfig = {};
  let onConfigChangeCb = null;
  let mapToCapabilitiesFn = null;
  let mapToEditPanelValuesFn = null;

  function loadConfig() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : {};
    } catch (e) {
      return {};
    }
  }

  function saveConfig(cfg) {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(cfg)); } catch(e) {}
  }

  function notifyChange() {
    if (typeof onConfigChangeCb === 'function') {
      try { onConfigChangeCb(currentConfig); } catch(e) { /* noop */ }
    }
  }

  window.elementSdk = {
    init({ defaultConfig: def = {}, onConfigChange, mapToCapabilities, mapToEditPanelValues } = {}) {
      defaultConfig = def || {};
      onConfigChangeCb = onConfigChange || null;
      mapToCapabilitiesFn = mapToCapabilities || null;
      mapToEditPanelValuesFn = mapToEditPanelValues || null;

      const stored = loadConfig();
      currentConfig = Object.assign({}, defaultConfig, stored);

      // initial notify
      notifyChange();
      return { isOk: true };
    },
    setConfig(partial = {}) {
      currentConfig = Object.assign({}, currentConfig, partial);
      saveConfig(currentConfig);
      notifyChange();
      return { isOk: true };
    },
    getConfig() { return Object.assign({}, currentConfig); },
    getCapabilities() {
      return typeof mapToCapabilitiesFn === 'function' ? mapToCapabilitiesFn(currentConfig) : {};
    },
    getEditPanelValues() {
      return typeof mapToEditPanelValuesFn === 'function' ? mapToEditPanelValuesFn(currentConfig) : new Map();
    }
  };

  // Sync across tabs
  window.addEventListener('storage', (e) => {
    if (e.key === STORAGE_KEY) {
      try { currentConfig = Object.assign({}, defaultConfig, JSON.parse(e.newValue || '{}')); } catch(_) {}
      notifyChange();
    }
  });
})();
