@if ($sculptures->count())
<section class="relative">
    <div id="sculpture-map" class="w-full h-screen"></div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    #sculpture-map {
        background: #0f172a;
    }
    .custom-marker {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.5);
        transition: transform .3s ease, box-shadow .3s ease;
        overflow: hidden;
        padding: 7px;
    }
    .custom-marker:hover {
        transform: scale(1.15);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.7);
    }
    .custom-marker svg {
        width: 100%;
        height: 100%;
        display: block;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 16px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 20px 50px -10px rgba(0,0,0,0.3);
    }
    .leaflet-popup-content {
        margin: 0;
        width: 300px !important;
    }
    .leaflet-popup-close-button {
        color: #fff !important;
        font-size: 22px !important;
        padding: 8px 12px 0 0 !important;
        z-index: 10;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }
    .map-popup-media {
        position: relative;
        width: 100%;
        height: 170px;
        background: #000;
        overflow: hidden;
    }
    .map-popup-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .map-popup-noimage {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .map-popup-noimage svg {
        width: 60px;
        height: 60px;
        color: #475569;
    }
    .map-popup-panorama-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: rgba(99, 102, 241, 0.95);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .05em;
        backdrop-filter: blur(4px);
    }
    .map-popup-body {
        padding: 14px 16px;
    }
    .map-popup-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 15px;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .map-popup-city {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 12px;
    }
    .map-popup-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .map-popup-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #0f172a;
        color: #fff !important;
        font-size: 11px;
        font-weight: 700;
        padding: 9px 14px;
        border-radius: 999px;
        text-decoration: none;
        transition: background .2s, transform .2s;
        white-space: nowrap;
        line-height: 1;
    }
    .map-popup-btn svg {
        flex-shrink: 0;
    }
    .map-popup-btn:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }
    .map-popup-btn.btn-panorama {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }
    .map-popup-btn.btn-panorama:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }
    .leaflet-control-attribution {
        font-size: 10px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapEl = document.getElementById('sculpture-map');
    if (!mapEl) return;

    const data = @json($sculptures);
    const locale = '{{ app()->getLocale() }}';

    const statueSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" width="100%" height="100%">' +
        '<g fill="#ffffff">' +
            '<path d="M352 400 L448 400 L442 688 L358 688 Z"/>' +
            '<path d="M380 230 C330 240 300 300 306 410 L320 495 L360 512 L440 512 L480 495 L494 410 C500 300 470 240 420 230 Z"/>' +
            '<path d="M375 230 L425 230 L400 290 Z" fill="none" stroke="#6366f1" stroke-width="6"/>' +
            '<rect x="375" y="200" width="50" height="40"/>' +
            '<path d="M375 230 L400 250 L425 230 Z" fill="none" stroke="#6366f1" stroke-width="6"/>' +
            '<circle cx="400" cy="165" r="45"/>' +
            '<circle cx="352" cy="165" r="8"/>' +
            '<circle cx="448" cy="165" r="8"/>' +
            '<path d="M355 160 C355 120 380 110 405 110 C435 110 450 130 450 155 C435 155 430 145 420 140 C410 135 390 145 375 145 C365 145 360 155 355 160 Z"/>' +
            '<rect x="266" y="688" width="268" height="32"/>' +
            '<rect x="286" y="720" width="228" height="96"/>' +
            '<rect x="320" y="746" width="160" height="44" fill="none" stroke="#6366f1" stroke-width="6"/>' +
            '<path d="M250 816 C250 790 270 776 296 776 L504 776 C530 776 550 790 550 816 Z"/>' +
            '<rect x="226" y="816" width="348" height="40"/>' +
        '</g>' +
    '</svg>';

    const map = L.map('sculpture-map', {
        scrollWheelZoom: false,
        zoomControl: true,
    }).setView([40.4093, 49.8671], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);

    const bounds = [];

    const markerIcon = L.divIcon({
        className: '',
        html: '<div class="custom-marker">' + statueSvg + '</div>',
        iconSize: [48, 48],
        iconAnchor: [24, 24],
        popupAnchor: [0, -24],
    });

    data.forEach(function (item) {
        if (!item.lat || !item.lng) return;

        const marker = L.marker([item.lat, item.lng], { icon: markerIcon }).addTo(map);
        bounds.push([item.lat, item.lng]);

        let media = '';
        let panoBtn = '';

        if (item.panorama) {
            media = (item.image
                        ? '<img class="map-popup-image" src="' + item.image + '" alt="">'
                        : '<div class="map-popup-noimage">' +
                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/></svg>' +
                          '</div>')
                    + '<div class="map-popup-panorama-badge">' +
                        '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' +
                        '</svg>' +
                        '360°' +
                      '</div>';

            panoBtn = '<a href="/' + locale + '/sculptures/' + item.slug + '/360" class="map-popup-btn btn-panorama">' +
                        '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>' +
                        '</svg>' +
                        '360°' +
                      '</a>';
        } else if (item.image) {
            media = '<img class="map-popup-image" src="' + item.image + '" alt="">';
        } else {
            media = '<div class="map-popup-noimage">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>' +
                        '</svg>' +
                    '</div>';
        }

        const html =
            '<div class="map-popup-media">' + media + '</div>' +
            '<div class="map-popup-body">' +
                '<div class="map-popup-title">' + escapeHtml(item.title) + '</div>' +
                (item.city ? '<div class="map-popup-city">' + escapeHtml(item.city) + '</div>' : '') +
                '<div class="map-popup-buttons">' +
                    '<a href="' + item.url + '" class="map-popup-btn">' +
                        '{{ __("frontend.view_details") }}' +
                        '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>' +
                    '</a>' +
                    panoBtn +
                '</div>' +
            '</div>';

        marker.bindPopup(html, { maxWidth: 300, minWidth: 300 });
    });

    if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [60, 60] });
    } else if (bounds.length === 1) {
        map.setView(bounds[0], 14);
    }

    mapEl.addEventListener('mouseenter', function () {
        map.scrollWheelZoom.enable();
    });
    mapEl.addEventListener('mouseleave', function () {
        map.scrollWheelZoom.disable();
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }
});
</script>
@endpush
@endif
