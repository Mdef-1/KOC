import "./catalog";
import { initElementSdk } from "./element/sdk";
import Alpine from 'alpinejs';
import "./catalog";
import { initElementSdk } from "./element/sdk";

document.addEventListener("DOMContentLoaded", () => {
  initElementSdk();
});

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
  initElementSdk();
});