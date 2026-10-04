# Azərbaycan Heykəlləri — Gürcüstan

[![Version](https://img.shields.io/badge/version-1.1.5-blue.svg)](https://github.com/mr-ruhid/sculpture-museum)
[![License](https://img.shields.io/badge/license-Proprietary-red.svg)](https://github.com/mr-ruhid/sculpture-museum/blob/main/LICENSE.md)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)

> Abidələr, heykəllər və tarixi obyektlərin rəqəmsal kataloqlaşdırılması üçün çoxdilli veb platforma və admin panel.

---

## Layihə Haqqında

**Azərbaycan Heykəlləri** — Azərbaycan ərazisindəki heykəllərin, abidələrin və tarixi obyektlərin rəqəmsal kataloqlaşdırılması üçün hazırlanmış müstəqil veb platformadır. Layihə [RJ CMS Lite](https://ruhidjavadoff.blogspot.com/2021/03/rj-cms-lite.html) ekosisteminin törəməsidir və frontend-də **RJ Museum Theme**-dən istifadə edir.

Platforma iki əsas hissədən ibarətdir:

- **Public veb-sayt**: heykəllərin kataloqu, interaktiv xəritə, scroll-driven showcase, qalereya və 360° panorama
- **Admin panel**: məzmun, dillər, media, SEO və sayt ayarlarının idarə edilməsi üçün interfeys

---

## Əsas Xüsusiyyətlər

### Public Veb-Sayt

- **İnteraktiv xəritə**: Leaflet + OpenStreetMap (API açarı tələb etmir)
- **Scroll-driven showcase**: ana səhifədə ən son dərc edilmiş heykəllərin təqdimatı
- **Lightbox qalereya**: tam ekran şəkil baxışı
- **360° panorama**: Google Maps embed dəstəyi
- **Qısa URL sistemi**: QR kodlar üçün sabit linklər (`/q/{code}`)
- **Tam responsiv**: mobil, tablet və desktop üçün optimallaşdırılmış

### Admin Panel

- Çoxdilli məzmun idarəsi
- Şəkil və qalereya yükləmə (WebP çevrilməsi ilə)
- 360° panorama idarəsi
- SEO parametrləri
- Sayt ayarları
- Dinamik dil idarəsi
- İki faktorlu autentifikasiya (2FA)
- Keş idarəsi
- QR qısa link idarəsi (bloklama və statistika)

### Çoxdilli Dəstək

- **4 dil:** Azərbaycan, İngilis, Rus, Gürcü
- URL prefiksləri (`/az/...`, `/en/...`)
- `Accept-Language` header ilə avtomatik dil seçimi
- Admin panel yalnız Azərbaycan dilində (hardcoded)

### Qısa URL-lər və QR Kodlar

QR kodlar üçün nəzərdə tutulmuş sabit linklər:

- `/{domain}/q/{code}` ünvanı `/{locale}/sculptures/{slug}` ünvanına 301 yönləndirilir
- Brauzer dilinə uyğun avtomatik dil seçimi
- Admin paneldə idarəetmə (bloklama, statistika)

---

## Texnologiyalar

| Kateqoriya | Texnologiya |
|---|---|
| **Backend** | Laravel 12.x |
| **Frontend** | Blade + Tailwind CSS (CDN), vanilla JavaScript |
| **Xəritə** | Leaflet + OpenStreetMap |
| **Şəkillər** | Intervention/Image (WebP) |
| **Baza** | MySQL |
| **Veb Server** | Apache / Nginx |
| **PHP** | 8.2+ |

---

## Layihə Strukturu

### Frontend Tema: `resources/views/theme/rjmuseum/`

```text
theme/rjmuseum/
├── layouts/
│   └── app.blade.php               # Əsas layout (header, footer, meta)
├── widgets/
│   ├── header.blade.php            # Tünd glass header + dil seçimi
│   ├── footer.blade.php            # Sosial media ikonları ilə footer
│   ├── scroll-showcase.blade.php   # Scroll-driven heykəl showcase
│   ├── map.blade.php               # Leaflet xəritə + siyahı paneli
│   └── sculpture-card.blade.php    # Heykəl kartı
├── pages/
│   ├── home.blade.php              # Ana səhifə
│   ├── sculptures.blade.php        # Heykəllər kataloqu (filtr)
│   ├── sculpture.blade.php         # Heykəl detal səhifəsi
│   ├── panorama.blade.php          # 360° tam ekran səhifə
│   ├── about.blade.php             # Haqqında
│   ├── contact.blade.php           # Əlaqə
│   ├── _dynamic.blade.php          # Dinamik səhifə render
│   └── templates/                  # Xüsusi template səhifələri
├── css/
│   └── app.css                     # Xüsusi stillər (glass, showcase)
└── js/
    └── app.js                      # Showcase, lang switcher, ripple
```

**Tam kod:** [resources/views/theme/rjmuseum](https://github.com/mr-ruhid/sculpture-museum/tree/main/resources/views/theme/rjmuseum)

### Admin Panel: `resources/views/admin/`

```text
admin/
├── layouts/
│   └── app.blade.php               # Admin layout (header, navigasiya)
├── auth/
│   ├── login.blade.php             # Giriş səhifəsi
│   └── twofactor.blade.php         # 2FA təsdiq səhifəsi
├── sculptures/
│   ├── index.blade.php             # Heykəllər siyahısı (drag & drop sıralama)
│   ├── create.blade.php            # Yeni heykəl
│   └── edit.blade.php              # Redaktə
├── pages/
│   ├── index.blade.php             # Səhifələr
│   ├── create.blade.php            # Yeni səhifə
│   └── edit.blade.php              # Redaktə
├── settings/
│   ├── index.blade.php             # Ayarlar hub
│   ├── general.blade.php           # Ümumi
│   ├── contact.blade.php           # Əlaqə
│   ├── social.blade.php            # Sosial
│   ├── seo.blade.php               # SEO
│   ├── homepage.blade.php          # Ana səhifə
│   ├── smtp.blade.php              # SMTP
│   └── about.blade.php             # Haqqında
├── short-urls/
│   └── index.blade.php             # Qısa URL-lər (yarat, redaktə, sil)
├── security/
│   └── blocked-ips.blade.php       # Bloklanmış IP-lər
├── languages/
│   └── index.blade.php             # Dillər
├── profile/
│   └── index.blade.php             # Profil (şifrə, 2FA)
└── cache/
    └── index.blade.php             # Keş idarəsi
```

**Tam kod:** [resources/views/admin](https://github.com/mr-ruhid/sculpture-museum/tree/main/resources/views/admin)

---

## Ekran Görüntüləri

| # | Şəkil |
|---|---|
| 01 | [Ana səhifə — showcase](https://github.com/mr-ruhid/sculpture-museum/blob/main/public/gallery/01.png) |
| 02 | [Xəritə — interaktiv](https://github.com/mr-ruhid/sculpture-museum/blob/main/public/gallery/02.png) |
| 03 | [Heykəl detal səhifəsi](https://github.com/mr-ruhid/sculpture-museum/blob/main/public/gallery/03.png) |
| 04 | [Admin panel — heykəllər](https://github.com/mr-ruhid/sculpture-museum/blob/main/public/gallery/04.png) |
| 05 | [Admin panel — ayarlar](https://github.com/mr-ruhid/sculpture-museum/blob/main/public/gallery/05.png) |

---

## Quraşdırma

```bash
# 1. Reponu klonlayın
git clone https://github.com/mr-ruhid/sculpture-museum.git
cd sculpture-museum

# 2. Asılılıqları quraşdırın
composer install

# 3. Mühit faylını hazırlayın
cp .env.example .env
php artisan key:generate

# 4. .env faylında baza məlumatlarını qeyd edin, sonra miqrasiyaları işə salın
php artisan migrate

# 5. Storage symlink yaradın
php artisan storage:link

# 6. Tətbiqi işə salın
php artisan serve
```

Tətbiq `http://127.0.0.1:8000` ünvanında əlçatan olacaq.

---

## RJ CMS Sənədləşməsi

RJ CMS ekosistemi haqqında ətraflı məlumat:

- [RJ CMS Sistemləri](https://github.com/mr-ruhid)
- [RJ CMS Törəmə Saytlar](https://github.com/mr-ruhid)
- [RJ CMS Lite](https://ruhidjavadoff.blogspot.com/2021/03/rj-cms-lite.html)
- [RJ Theme](https://github.com/mr-ruhid)
- [RJ AI Agent — Agsaggal AI](https://github.com/mr-ruhid)

---

## Lisenziya

Bu layihə RJ CMS Lite törəməsidir və xüsusi müəllif lisenziyası ilə qorunur. İstifadə şərtləri üçün [LICENSE.md](LICENSE.md) faylına baxın.

**Xülasə:**

- **İcazə verilir:** kodu görmək, oxumaq və şəxsi, qeyri-kommersiya məqsədləri ilə öyrənmək
- **İcazə verilmir:** yazılı icazə olmadan kopyalamaq, yaymaq və istehsalatda (production) istifadə etmək
- **İcazə verilmir:** dəyişdirmək və ya törəmə əsər yaratmaq (şəxsi öyrənmə istisna olmaqla)
- **Qeyd:** kommersiya və ya istehsalat istifadəsi üçün müəllif ilə əlaqə saxlamaq lazımdır

---

## Dəstək

Bu layihə sizə faydalı olubsa, onun davamlı inkişafına dəstək verə bilərsiniz.

<div align="center">

<a href="https://kofe.al/@ruhidjavadoff">
  <img src="https://kofe.al/assets/images/kofeal-logo.svg" height="36" alt="Kofe.al ilə dəstək" style="background-color:#ffffff; padding:6px; border-radius:6px;">
</a>
&nbsp;&nbsp;
<a href="https://www.paypal.com/paypalme/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Donate-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="PayPal ilə dəstək" height="36">
</a>

</div>

<br>

| Üsul | Məlumat |
|---|---|
| Kofe.al | [@ruhidjavadoff](https://kofe.al/@ruhidjavadoff) |
| Çayvoy | [ruhid4715](https://cayvoy.com/donate/ruhid4715) |
| PayPal | `ruhidjavadoff@gmail.com` |
| Crypto (USDT — BNB Smart Chain) | `0x9a4AD41762D6B07B8C266b312Cf0dBe31FAd890c` |

---

## Müəllif

**Ruhid Javadov**

- GitHub: [@mr-ruhid](https://github.com/mr-ruhid)
- Layihə: [sculpture-museum](https://github.com/mr-ruhid/sculpture-museum)

---

<div align="center">

**Azərbaycan Heykəlləri** · RJ CMS Lite ilə hazırlanıb · © 2026

</div>
