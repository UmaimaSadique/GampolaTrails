// ---------- Trip plan storage (kept in the browser's localStorage) ----------
const PLAN_KEY = 'gt_plan';

function getPlan() {
  try { return JSON.parse(localStorage.getItem(PLAN_KEY)) || []; }
  catch (e) { return []; }
}
function savePlan(plan) {
  localStorage.setItem(PLAN_KEY, JSON.stringify(plan));
  refreshPlanUI();
}
function togglePlace(id) {
  const plan = getPlan();
  const i = plan.indexOf(id);
  if (i === -1) plan.push(id); else plan.splice(i, 1);
  savePlan(plan);
}

// Update the badge in the menu and the text/state of every "Add to plan" button
function refreshPlanUI() {
  const plan = getPlan();
  const badge = document.getElementById('planCount');
  if (badge) badge.textContent = plan.length;
  document.querySelectorAll('.plan-btn').forEach(btn => {
    const inPlan = plan.includes(Number(btn.dataset.id));
    btn.textContent = inPlan ? window.LABELS.remove : window.LABELS.add;
    btn.classList.toggle('added', inPlan);
  });
}

document.addEventListener('click', e => {
  const btn = e.target.closest('.plan-btn');
  if (btn) togglePlace(Number(btn.dataset.id));
});

// ---------- Search + category filter (home page) ----------
const searchBox = document.getElementById('search');
const chips = document.getElementById('chips');
let activeCat = 'all';

function applyFilters() {
  const q = searchBox ? searchBox.value.trim().toLowerCase() : '';
  let shown = 0;
  document.querySelectorAll('#placeGrid .card').forEach(card => {
    const matchText = card.dataset.name.includes(q);
    const matchCat = activeCat === 'all' || card.dataset.cats.split(' ').includes(activeCat);
    card.hidden = !(matchText && matchCat);
    if (!card.hidden) shown++;
  });
  const none = document.getElementById('noResults');
  if (none) none.hidden = shown > 0;
}
if (searchBox) searchBox.addEventListener('input', applyFilters);
if (chips) chips.addEventListener('click', e => {
  const chip = e.target.closest('.chip');
  if (!chip) return;
  activeCat = chip.dataset.cat;
  chips.querySelectorAll('.chip').forEach(c => c.classList.toggle('active', c === chip));
  applyFilters();
});

// ---------- Map (Leaflet + OpenStreetMap) ----------
const CAT_COLORS = { historical: '#b45309', religious: '#7c3aed', natural: '#15803d', recreational: '#0369a1' };

function makeIcon(color) {
  return L.divIcon({
    className: '',
    html: '<span class="pin" style="background:' + color + '"></span>',
    iconSize: [18, 18], iconAnchor: [9, 9], popupAnchor: [0, -8],
  });
}

const mapEl = document.getElementById('map');
if (mapEl && window.MAP_PLACES && typeof L !== 'undefined') {
  const map = L.map('map');
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18, attribution: '&copy; OpenStreetMap contributors',
  }).addTo(map);

  const points = [];
  window.MAP_PLACES.forEach(p => {
    const link = window.SINGLE_PLACE ? '' : '<br><a href="place.php?id=' + p.id + '">Details</a>';
    L.marker([p.lat, p.lng], { icon: makeIcon(CAT_COLORS[p.category] || '#333') })
      .addTo(map).bindPopup('<strong>' + p.name + '</strong>' + link);
    points.push([p.lat, p.lng]);
  });

  if (window.SINGLE_PLACE) {
    map.setView(points[0], 14);
  } else {
    map.fitBounds(points, { padding: [30, 30] });
  }
}

refreshPlanUI();
