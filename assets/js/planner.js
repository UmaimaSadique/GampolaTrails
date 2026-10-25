// One-day trip planner: loads the chosen places, orders them, and builds a timetable.
// Uses straight-line distance x a road factor / average hill-road speed as a travel estimate.

const ROAD_FACTOR = 1.4;   // roads are longer than straight lines
const AVG_SPEED_KMH = 30;  // slow hill-country roads
const L_ = window.PLAN_LABELS;

let planPlaces = [];       // places in visiting order
let routeMap = null, routeLayer = null;

function km(a, b) {        // haversine distance in km
  const R = 6371, rad = Math.PI / 180;
  const dLat = (b.lat - a.lat) * rad, dLng = (b.lng - a.lng) * rad;
  const h = Math.sin(dLat / 2) ** 2 +
            Math.cos(a.lat * rad) * Math.cos(b.lat * rad) * Math.sin(dLng / 2) ** 2;
  return 2 * R * Math.asin(Math.sqrt(h));
}
function travelMinutes(a, b) { return Math.round(km(a, b) * ROAD_FACTOR / AVG_SPEED_KMH * 60 / 5) * 5 || 5; }

function fmtTime(mins) {
  const h = Math.floor(mins / 60) % 24, m = mins % 60;
  const ampm = h >= 12 ? 'PM' : 'AM';
  return ((h + 11) % 12 + 1) + ':' + String(m).padStart(2, '0') + ' ' + ampm;
}
function fmtDur(mins) {
  const h = Math.floor(mins / 60), m = mins % 60;
  return (h ? h + ' h ' : '') + (m ? m + ' min' : '');
}

// Nearest-neighbour ordering: always go to the closest place not yet visited
function orderByNearest(places) {
  const left = places.slice(), ordered = [];
  let current = window.GAMPOLA;
  while (left.length) {
    let best = 0;
    left.forEach((p, i) => { if (km(current, { lat: p.latitude, lng: p.longitude }) < km(current, { lat: left[best].latitude, lng: left[best].longitude })) best = i; });
    const next = left.splice(best, 1)[0];
    ordered.push(next);
    current = { lat: next.latitude, lng: next.longitude };
  }
  return ordered;
}

function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

function render() {
  const list = document.getElementById('itinerary');
  const layout = document.getElementById('planLayout');
  const empty = document.getElementById('emptyMsg');
  const warn = document.getElementById('warn');
  const summary = document.getElementById('summary');

  empty.hidden = planPlaces.length > 0;
  layout.hidden = planPlaces.length === 0;
  warn.hidden = true;
  summary.textContent = '';
  if (!planPlaces.length) return;

  const [sh, sm] = document.getElementById('startTime').value.split(':').map(Number);
  let clock = (sh || 8) * 60 + (sm || 0);
  let prev = window.GAMPOLA, totalTravel = 0, totalVisit = 0, totalKm = 0;
  let html = '';

  planPlaces.forEach((p, i) => {
    const here = { lat: p.latitude, lng: p.longitude };
    const t = travelMinutes(prev, here);
    const dist = km(prev, here) * ROAD_FACTOR;
    clock += t;
    const arrive = clock;
    clock += p.visit_minutes;
    totalTravel += t; totalVisit += p.visit_minutes; totalKm += dist;

    html += '<li>' +
      '<div class="step-time">' + fmtTime(arrive) + '</div>' +
      '<div class="step-body">' +
        '<h3>' + (i + 1) + '. <a href="place.php?id=' + p.id + '">' + esc(p.name) + '</a></h3>' +
        '<p class="small">' + L_.travel + ': ~' + fmtDur(t) + ' (~' + dist.toFixed(1) + ' km) &middot; ' +
          L_.visit + ': ' + fmtDur(p.visit_minutes) + ' &middot; ' + L_.leave + ': ' + fmtTime(clock) + '</p>' +
        '<p class="small muted">' + esc(p.opening_hours || '') + '</p>' +
        '<div class="no-print">' +
          '<button class="mini" data-act="up" data-i="' + i + '"' + (i === 0 ? ' disabled' : '') + '>&uarr;</button> ' +
          '<button class="mini" data-act="down" data-i="' + i + '"' + (i === planPlaces.length - 1 ? ' disabled' : '') + '>&darr;</button> ' +
          '<button class="mini danger" data-act="remove" data-i="' + i + '">' + L_.remove + '</button>' +
        '</div>' +
      '</div></li>';
    prev = here;
  });

  // Return trip to Gampola
  const back = travelMinutes(prev, window.GAMPOLA);
  totalTravel += back; totalKm += km(prev, window.GAMPOLA) * ROAD_FACTOR;
  const endClock = clock + back;
  html += '<li class="end"><div class="step-time">' + fmtTime(endClock) + '</div>' +
          '<div class="step-body"><h3>Gampola</h3><p class="small">' + L_.travel + ': ~' + fmtDur(back) + '</p></div></li>';
  list.innerHTML = html;

  summary.textContent = L_.total + ': ~' + totalKm.toFixed(0) + ' km, ' + L_.travel + ' ' + fmtDur(totalTravel) +
                        ', ' + L_.visit + ' ' + fmtDur(totalVisit) + '.';
  if (endClock > 18 * 60 + 30) { warn.textContent = L_.warn_late; warn.hidden = false; }
  drawRoute();
}

function drawRoute() {
  if (typeof L === 'undefined') return;
  if (!routeMap) {
    routeMap = L.map('map');
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18, attribution: '&copy; OpenStreetMap contributors',
    }).addTo(routeMap);
  }
  if (routeLayer) routeLayer.remove();
  routeLayer = L.layerGroup().addTo(routeMap);

  const pts = [[window.GAMPOLA.lat, window.GAMPOLA.lng]];
  L.marker(pts[0]).addTo(routeLayer).bindPopup('Gampola');
  planPlaces.forEach((p, i) => {
    pts.push([p.latitude, p.longitude]);
    L.marker([p.latitude, p.longitude], {
      icon: L.divIcon({ className: '', html: '<span class="pin num">' + (i + 1) + '</span>', iconSize: [24, 24], iconAnchor: [12, 12] }),
    }).addTo(routeLayer).bindPopup(esc(p.name));
  });
  pts.push(pts[0]);
  L.polyline(pts, { color: '#0f766e', weight: 4, dashArray: '8 6' }).addTo(routeLayer);
  routeMap.fitBounds(pts, { padding: [30, 30] });
  setTimeout(() => routeMap.invalidateSize(), 100);
}

async function loadPlan() {
  const ids = getPlan();
  if (!ids.length) { planPlaces = []; render(); return; }
  const res = await fetch('api/places.php?ids=' + ids.join(','));
  const data = await res.json();
  // keep the user's chosen order, then optimise it the first time
  const byId = Object.fromEntries(data.map(p => [p.id, p]));
  planPlaces = orderByNearest(ids.map(id => byId[id]).filter(Boolean));
  render();
}

document.getElementById('itinerary').addEventListener('click', e => {
  const b = e.target.closest('button[data-act]');
  if (!b) return;
  const i = Number(b.dataset.i);
  if (b.dataset.act === 'remove') {
    planPlaces.splice(i, 1);
  } else {
    const j = b.dataset.act === 'up' ? i - 1 : i + 1;
    [planPlaces[i], planPlaces[j]] = [planPlaces[j], planPlaces[i]];
  }
  savePlan(planPlaces.map(p => p.id));
  render();
});
document.getElementById('startTime').addEventListener('input', render);
document.getElementById('printBtn').addEventListener('click', () => window.print());
document.getElementById('clearBtn').addEventListener('click', () => { savePlan([]); planPlaces = []; render(); });

loadPlan();
