# AskBangladesh 🇧🇩

**Everything about Bangladesh, in one place.**

A dependency-free PHP web app that gathers the things people actually look up about
Bangladesh — an interactive map, all 64 districts, live clocks and converters, prayer
times, gold rates, government services, travel guides and geography — behind a single
global search box.

No framework, no build step, no database, no tracker. Drop it on any PHP 8 host and it runs.

---

## Features

| Section | What it does |
| --- | --- |
| 🗺️ **Interactive map** | Hand-projected SVG of Bangladesh with 116 pins — 64 districts, 11 peaks, 24 destinations, 8 airports, 5 ports and the 4 extreme points. Drag to pan, scroll to zoom, toggle layers, click any pin for a detail panel. |
| 🔍 **Global search** | One index across every dataset in the app. Live dropdown with keyboard navigation, plus a full results page grouped by category. Press <kbd>/</kbd> anywhere to focus it. |
| 📍 **Districts** | All 64 districts with Bangla names, division, area, population and what each is known for. Filter by division or free text. |
| ⛰️ **Geography** | Continent and region, the Ganges–Brahmaputra–Meghna delta, highest peaks, major rivers, the six Bengali seasons and the country's four extreme points. |
| 🧳 **Travel** | 24 destinations with type, district and best season — beaches, hills, mangroves, tea gardens, wetlands and UNESCO sites. |
| 🛣️ **Transport** | National highways, fares for every mode from rickshaw to metro, airports, seaports and the megaprojects that redrew the map. |
| 🕰️ **Time** | Live Bangladesh Standard Time (digital + analogue), 34 world clocks, a two-way time-zone converter and countdowns to national days. |
| 💱 **Currency** | The Taka against 31 currencies, with an instant converter, remittance-corridor cards and a full rate table. |
| 🥇 **Gold** | BAJUS-style 22K/21K/18K/traditional gold and silver board, plus a converter across bhori, ana, roti, gram, tola and troy ounce. |
| 🕌 **Prayer times** | Astronomically calculated salah times for any district, with a live next-prayer countdown and sehri/iftar times. |
| 🕊️ **Religion** | Population shares, major sites for each faith, and the national festival calendar. |
| 🏛️ **Government** | Structure of the state, ten e-service portals and every national emergency hotline. |
| 🇧🇩 **About** | National profile, symbols, a history timeline, food and facts. |

### Design

Dark and light themes (following the system preference, with a manual toggle that persists),
an animated aurora background, scroll-reveal transitions, counting statistics, pointer-tracking
tiles, a typewriter headline and a draw-on map outline. Everything degrades cleanly under
`prefers-reduced-motion`, and the whole app is responsive down to small phones.

---

## Requirements

- PHP **8.0+** (developed and tested on 8.4)
- No extensions required. `ext-curl` is used for live rates when present; the app falls
  back to the streams wrapper, and then to bundled data.

## Running it

```bash
git clone https://github.com/Towfique-Ahmed/AskBangladesh.git
cd AskBangladesh
php -S localhost:8000
```

Open <http://localhost:8000>.

## Deployment

**Point the document root at the folder that contains `index.php`.** The application is not
nested in a `public/` subfolder — the repository root *is* the web root.

```
/var/www/askbangladesh/     <-- document root goes here
├── index.php               <-- the only entry point
├── api/
├── assets/
├── includes/
├── pages/
└── storage/cache/          <-- must be writable
```

- **Apache / LiteSpeed** — the bundled `.htaccess` sets `DirectoryIndex index.php` and blocks
  the internal directories. Ensure `AllowOverride All` is set for the vhost, or copy the
  directives into the vhost itself.
- **Nginx** — `.htaccess` is ignored. Use `deploy/nginx.conf.example` as the server block.
- **PHP-FPM** — PHP 8.0+, no extensions required.
- **Permissions** — `storage/cache/` needs to be writable by the PHP user. If it is not,
  everything still works; the app simply re-fetches the rate feeds each request.

### Troubleshooting

**A bare `File not found.` for `/`** — this is the *web server's* 404, not the app's (the
app's 404 is a styled page). PHP was never reached. In order of likelihood:

1. The document root does not contain `index.php` — it points one level too high, or at a
   `public_html/` that the repository was cloned *beside* rather than *into*.
2. The app was deployed into a subfolder, so it lives at `/AskBangladesh/` rather than `/`.
3. The vhost's directory index list has no `index.php` entry. The bundled `.htaccess` fixes
   this on Apache and LiteSpeed; on Nginx set `index index.php;`.
4. The deploy pulled a branch that does not contain the app.

Confirm with `ls /path/to/docroot` — you should see `index.php` at the top level.

**A blank white page** — PHP failed. Check the PHP-FPM error log, and confirm the PHP version
is 8.0 or newer (`php -v`).

## Project layout

```
index.php               front controller — routes ?p=<page>
includes/
  config.php            constants and bootstrap
  functions.php         data access, search index, map projection, HTTP client
  services.php          currency, gold and prayer-time services
  data/
    districts.php       8 divisions, 64 districts
    places.php          geography, mountains, rivers, travel, roads, transport
    nation.php          profile, symbols, government, religions, festivals, history
  layout/               header and footer
pages/                  one file per route
api/                    JSON endpoints
assets/                 css and js
storage/cache/          live-rate cache (gitignored)
```

## JSON API

Every dataset is also readable as JSON.

| Endpoint | Example |
| --- | --- |
| `api/search.php` | `?q=sylhet&limit=8` |
| `api/rates.php` | `?amount=100&from=USD&to=BDT` |
| `api/gold.php` | — |
| `api/prayer.php` | `?district=Sylhet&school=hanafi&date=2026-03-20` |

```bash
curl 'localhost:8000/api/prayer.php?district=Dhaka'
```

```json
{
  "district": "Dhaka",
  "method": "University of Islamic Sciences, Karachi (Fajr 18°, Isha 18°)",
  "times": { "Fajr": "04:14", "Sunrise": "05:34", "Dhuhr": "12:05",
             "Asr": "16:38", "Maghrib": "18:34", "Isha": "19:52" }
}
```

## How the data works

**Static data** — districts, geography, travel, transport, government and cultural content
live in plain PHP arrays under `includes/data/`. Editing them updates the pages, the map and
the search index at once.

**Prayer times** are computed, not fetched: `bd_prayer_times()` implements the standard solar
position algorithm (Julian day → solar declination and equation of time → hour angles) using
the University of Islamic Sciences, Karachi convention of 18° for Fajr and Isha, with a
selectable Hanafi or Shafi Asr shadow ratio. This means they work offline for any district
and any date.

**Currency and gold** try a live source first, cache the result for an hour, and fall back to
bundled indicative figures when the network is unavailable. The UI always states which of the
two you are looking at.

## Accuracy

Exchange rates, gold prices and prayer times are indicative and shown for reference. Confirm
with Bangladesh Bank, BAJUS and your local mosque before acting on them. The map outline is a
stylised projection built for exploration, not for navigation or any question of borders.

## Licence

MIT.
