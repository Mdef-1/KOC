import { catalogProducts, svgTemplates } from "./data";

let currentFilter = "semua";

export function renderCatalog(filter = "semua") {
  const grid = document.getElementById("catalog-grid");
  if (!grid) return;

  const products =
    filter === "semua"
      ? catalogProducts
      : catalogProducts.filter(p => p.category === filter);

  grid.innerHTML = products.map(product => `
    <div class="group cursor-pointer">
      <div class="aspect-[3/4] rounded-2xl mb-4 overflow-hidden">
        <svg viewBox="0 0 300 400" class="w-full h-full">
          ${svgTemplates[product.svg]}
        </svg>
      </div>
      <h3 class="font-semibold">${product.name}</h3>
      <p class="opacity-60">Rp ${product.price.toLocaleString("id-ID")}</p>
    </div>
  `).join("");
}

export function setupCatalogFilters() {
  document.querySelectorAll(".catalog-filter").forEach(btn => {
    btn.addEventListener("click", () => {
      currentFilter = btn.dataset.category;
      renderCatalog(currentFilter);
    });
  });
}
