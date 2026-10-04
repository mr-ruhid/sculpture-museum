# Azerbaijan Sculptures

A multilingual web platform and admin panel for the digital cataloging of sculptures, monuments, and historical objects in Azerbaijan.

**Version:** 1.1.5 · **Year:** 2026

---

## Overview

**Azerbaijan Sculptures** is a standalone website built as a derivative of [RJ CMS Lite](https://github.com/mr-ruhid). It reuses and extends the code base of the RJ Museum theme, adapting it into a dedicated project for documenting and presenting sculptures, complete with locations, galleries, and 360° panoramas.

The platform consists of two parts:

- **Public website**: a catalog of sculptures with an interactive map, scroll-driven showcase, gallery, and panorama views.
- **Admin panel**: a management interface for content, languages, media, SEO, and site settings.

---

## Key Features

### Public Website

- **Interactive map** powered by Leaflet and OpenStreetMap (no API key required)
- **Scroll-driven showcase** on the home page featuring the latest published sculptures
- **Lightbox gallery** with full-screen image viewing
- **360° panorama** support via Google Maps embed
- **Short URL system** providing permanent links for QR codes
- **Fully responsive** layout for mobile, tablet, and desktop

### Admin Panel

- Multilingual data management
- Image and gallery upload (converted to WebP)
- 360° panorama management
- SEO management
- Site settings
- Dynamic language management
- Two-factor authentication (2FA)
- Cache management
- QR short link management (blocking and statistics)

### Multilingual Support

- **4 languages:** Azerbaijani, English, Russian, Georgian
- URL prefixes (`/az/...`, `/en/...`)
- Automatic language selection via the `Accept-Language` header
- Admin panel is available in Azerbaijani only (hardcoded)

### Short URLs and QR Codes

Permanent links designed for QR codes:

- `/{domain}/q/{code}` issues a 301 redirect to `/{locale}/sculptures/{slug}`
- Language is selected automatically based on the browser language

---

## Technology Stack

| Category | Technology |
|---|---|
| Backend | Laravel 12.x |
| Frontend | Blade + Tailwind CSS (CDN), vanilla JavaScript |
| Map | Leaflet + OpenStreetMap |
| Images | Intervention/Image (WebP) |
| Database | MySQL |
| Web Server | Apache / Nginx |
| PHP | 8.2+ |

---

## Project Structure

The front-end theme follows the RJ Museum theme layout:

```text
theme/rjmuseum/
├── layouts/
│   └── app.blade.php               # Main layout (header, footer, meta)
├── widgets/
│   ├── header.blade.php            # Dark glass header with language switcher
│   ├── footer.blade.php            # Footer with social media icons
│   ├── scroll-showcase.blade.php   # Scroll-driven sculpture showcase
│   ├── map.blade.php               # Leaflet map with list panel
│   └── sculpture-card.blade.php    # Sculpture card
├── pages/
│   ├── home.blade.php              # Home page
│   ├── sculptures.blade.php        # Sculpture catalog (with filters)
│   ├── sculpture.blade.php         # Sculpture detail page
│   ├── panorama.blade.php          # Full-screen 360° page
│   ├── about.blade.php             # About page
│   ├── contact.blade.php           # Contact page
│   ├── _dynamic.blade.php          # Dynamic page renderer
│   └── templates/                  # Custom template pages
├── css/
│   └── app.css                     # Custom styles (glass, showcase)
└── js/
    └── app.js                      # Showcase, language switcher, ripple effect
```

---

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/mr-ruhid/sculpture-museum.git
cd sculpture-museum

# 2. Install dependencies
composer install

# 3. Prepare the environment file
cp .env.example .env
php artisan key:generate

# 4. Set up the database
php artisan migrate

# 5. Create the storage link
php artisan storage:link

# 6. Start the development server
php artisan serve
```

---

## Links

- **GitHub Profile:** [github.com/mr-ruhid](https://github.com/mr-ruhid)
- **Theme Source:** [resources/views/theme/rjmuseum](https://github.com/mr-ruhid/sculpture-museum/tree/main/resources/views/theme/rjmuseum)
- **RJ CMS Lite:** [github.com/mr-ruhid](https://github.com/mr-ruhid)

---

## Author

**Ruhid Javadov**

- GitHub: [@mr-ruhid](https://github.com/mr-ruhid)
- Project: Azerbaijan Sculptures, Georgia

---

## License

This project is built as a derivative of **RJ CMS Lite**. For usage terms, please refer to the RJ CMS documentation.

---

## Support the Project

If this project has been useful to you, consider supporting its continued development and maintenance.

<div align="center">

<a href="https://kofe.al/@ruhidjavadoff">
  <img src="https://kofe.al/assets/images/kofeal-logo.svg" height="36" alt="Support on Kofe.al" style="background-color:#ffffff; padding:6px; border-radius:6px;">
</a>
&nbsp;&nbsp;
<a href="https://www.paypal.com/paypalme/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Donate-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="Donate via PayPal" height="36">
</a>

</div>

<br>

| Method | Details |
|---|---|
| Kofe.al | [@ruhidjavadoff](https://kofe.al/@ruhidjavadoff) |
| Çayvoy | [ruhid4715](https://cayvoy.com/donate/ruhid4715) |
| PayPal | `ruhidjavadoff@gmail.com` |
| Crypto (USDT — BNB Smart Chain) | `0x9a4AD41762D6B07B8C266b312Cf0dBe31FAd890c` |

---

<div align="center">

**Azerbaijan Sculptures** · Built with RJ CMS Lite · © 2026

</div>
