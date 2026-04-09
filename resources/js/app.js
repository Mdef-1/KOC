import "./catalog";
import { initElementSdk } from "./element/sdk";
import Alpine from 'alpinejs';

document.addEventListener("DOMContentLoaded", () => {
  initElementSdk();
});

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
  initElementSdk();
});