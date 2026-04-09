import { initElementSdk } from "./element/sdk";
import Alpine from 'alpinejs';

// Initialize Element SDK
document.addEventListener("DOMContentLoaded", () => {
  initElementSdk();
});

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();