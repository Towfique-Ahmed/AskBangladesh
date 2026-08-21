# AskBangladesh 🇧🇩

**Everything about Bangladesh, in one place.**

A dependency-free PHP web app that gathers the things people actually look up about
Bangladesh — an interactive map, all 64 districts, live clocks and converters, sunrise
and sunset times, gold rates, government services, travel guides and geography — behind a
single global search box.

No framework, no build step, no database. Drop it on any PHP 8 host and it runs.

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
| 🌅 **Sunrise & sunset** | Calculated sunrise, sunset, first and last light, solar noon, golden hours and day length for any district, plus tomorrow's figures. |
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
index.php               front controller — resolves clean URLs via bd_route()
includes/
  config.php            constants and bootstrap
  functions.php         data access, search index, map projection, HTTP client
  services.php          currency, gold and sun-time services
  seo.php               per-page metadata, JSON-LD and the sitemap URL list
  data/
    districts.php       8 divisions, 64 districts
    places.php          geography, mountains, rivers, travel, roads, transport
    nation.php          profile, symbols, government, religions, festivals, history
  layout/               header and footer
pages/                  one file per route, incl. sitemap.php and robots.php
api/                    JSON endpoints
assets/                 css and js
storage/cache/          live-rate cache (gitignored)
```

## URLs

Clean, keyword-bearing paths throughout — no query strings on content pages.

| Pattern | Example | Count |
| --- | --- | --- |
| `/` | the landing page | 1 |
| `/{section}` | `/districts`, `/gold`, `/sunrise-sunset` | 15 |
| `/district/{slug}` | `/district/coxs-bazar` | 64 |
| `/division/{slug}` | `/division/sylhet` | 8 |
| `/travel/{slug}` | `/travel/sajek-valley` | 24 |
| `/sunrise-sunset/{slug}` | `/sunrise-sunset/khulna` | 64 |

175 indexable URLs in total, every one listed in `/sitemap.xml`.

Rewriting is handled by `.htaccess` on Apache and LiteSpeed, and by `try_files` in
`deploy/nginx.conf.example` on Nginx. Anything that is not a real file reaches `index.php`,
which resolves it with `bd_route()`.

## SEO

- **Unique title and meta description on every page** — titles kept under 60 characters and
  descriptions under 160, so neither is truncated in results. The brand suffix is appended
  only when it still fits, and `bd_trim_meta()` cuts long descriptions on a word boundary.
- **Canonical URL** on every page, built from `SITE_URL`.
- **Structured data** (JSON-LD): `WebSite` with `SearchAction` sitewide, `BreadcrumbList` on
  every nested page, plus `AdministrativeArea` on districts and divisions,
  `TouristAttraction` on destinations, `ItemList` on the ranked lists and `FAQPage` on the
  pages that answer real questions. Every FAQ is rendered as a **visible** accordion from the
  same array that produces the markup, which is what Google's guidelines require — see
  `bd_render_faq()`.
- **`Organization`** identity and a `dateModified` derived from the data files
  (`bd_content_modified()`), shared by the pages and the sitemap's `lastmod`.
- **Open Graph and Twitter cards** with a generated `assets/og-image.png` preview (1200×630).
- **`/sitemap.xml`** generated from the data files, with `lastmod`, `changefreq` and
  `priority`; **`/robots.txt`** points at it.
- **Search result pages are `noindex, follow`** — thin and effectively infinite — while the
  links they contain are still crawled.
- `?district=` on the sun page **301-redirects** to its canonical `/sunrise-sunset/{slug}`.
- The prayer-times feature has been removed entirely. `/prayer` and `/prayer/{slug}` no
  longer exist and return 404.

## Data currency

Figures carry different confidence levels, and the app says which is which:

| Data | Source | Note |
| --- | --- | --- |
| Districts, divisions, area | 2022 BBS census | Stable |
| National population, GDP | 2026 estimates | Projections, not counts |
| Sunrise & sunset | Calculated | Exact for any date and district |
| Exchange rates, gold | Live feed, hourly cache | Falls back to bundled indicative values |

The bundled currency and gold figures are a **fallback only**, used when the live feed is
unreachable. The UI always states which of the two is being shown.

Set `SITE_URL` in the environment so canonical tags and the sitemap emit your real origin:

```
SITE_URL=https://askbangladesh.towfique.com
```

Without it the origin is derived per-request from the `Host` header, which works but pins
nothing if the site answers on more than one hostname.

## Analytics

Google Analytics 4 (`gtag.js`) is emitted immediately after `<head>` on every HTML page,
per Google's installation instructions. The measurement ID lives in `includes/config.php`
and can be overridden per environment:

```
GA_MEASUREMENT_ID=G-XXXXXXXXXX    # override the ID
GA_MEASUREMENT_ID=                # empty disables the tag entirely
GA_FORCE=1                        # also tag localhost (off by default)
```

`localhost`, `127.0.0.1`, `.local` and `.test` hosts are skipped so development traffic does
not land in the production property. `sitemap.xml` and `robots.txt` are never tagged.

## JSON API

Every dataset is also readable as JSON.

| Endpoint | Example |
| --- | --- |
| `api/search.php` | `?q=sylhet&limit=8` |
| `api/rates.php` | `?amount=100&from=USD&to=BDT` |
| `api/gold.php` | — |
| `api/sun.php` | `?district=Sylhet&date=2026-03-20` |

```bash
curl 'localhost:8000/api/sun.php?district=Dhaka'
```

```json
{
  "district": "Dhaka",
  "method": "Solar position algorithm; sunrise and sunset at -0.833°…",
  "times": { "First light": "05:12", "Sunrise": "05:35", "Solar noon": "12:05",
             "Sunset": "18:34", "Last light": "18:57", "Day length": "12:59" }
}
```

## How the data works

**Static data** — districts, geography, travel, transport, government and cultural content
live in plain PHP arrays under `includes/data/`. Editing them updates the pages, the map and
the search index at once.

**Sun times** are computed, not fetched: `bd_sun_times()` implements the standard solar
position algorithm (Julian day → solar declination and equation of time → hour angles).
Sunrise and sunset use an altitude of −0.833° to allow for atmospheric refraction and the
width of the solar disc; civil twilight uses −6° and the golden hours +6°. This means they
work offline for any district and any date.

**Currency and gold** try a live source first, cache the result for an hour, and fall back to
bundled indicative figures when the network is unavailable. The UI always states which of the
two you are looking at.

## Accuracy

Exchange rates and gold prices are indicative and shown for reference. Confirm
with Bangladesh Bank, BAJUS and your local mosque before acting on them. The map outline is a
stylised projection built for exploration, not for navigation or any question of borders.

## Licence

MIT.
