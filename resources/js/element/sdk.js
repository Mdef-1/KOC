import { defaultConfig } from "./defaultConfig";

export function initElementSdk() {
  if (!window.elementSdk) return;

  window.elementSdk.init({
    defaultConfig,
    onConfigChange(config) {
      document.body.style.backgroundColor =
        config.background_color || defaultConfig.background_color;
    }
  });
}
