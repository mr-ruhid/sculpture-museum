@if ($sculptures->count())
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-3">
                    {{ __('frontend.map') }}
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight">
                    {{ __('frontend.map_title') }}
                </h2>
                <p class="text-lg text-slate-500 mt-4 max-w-2xl">
                    {{ __('frontend.map_subtitle') }}
                </p>
            </div>
        </div>

        <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-100">
            <div id="sculpture-map" class="w-full h-[600px]"></div>
        </div>

    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .custom-marker {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        width: 36px;
        height: 36px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-marker::after {
        content: '';
        width: 10px;
        height: 10px;
        background: #fff;
        border-radius: 50%;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 16px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 20px 50px -10px rgba(0,0,0,0.3);
    }
    .leaflet-popup-content {
        margin: 0;
        width: 280px !important;
    }
    .leaflet-popup-tip {
        box-shadow: none;
    }
    .leaflet-popup-close-button {
        color: #fff !important;
        font-size: 20px !important;
        padding: 6px 10px 0 0 !important;
        z-index: 10;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }
    .map-popup-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .map-popup-noimage {
        width: 100%;
        height: 160px;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        display: flex;
        align-items: center;
        justify-content: center;
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
    .map-popup-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #0f172a;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 999px;
        text-decoration: none;
        transition: background .2s;
    }
    .map-popup-btn:hover {
        background: #6366f1;
    }
    .map-panorama {
        width: 100%;
        height: 160px;
        border: 0;
        display: block;
    }
    .map-panorama-wrap {
        position: relative;
        background: #000;
        height: 160px;
    }
    .map-panorama-toggle {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255,255,255,0.9);
        color: #0f172a;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 999px;
        cursor: pointer;
        border: 0;
        z-index: 2;
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

    const map = L.map('sculpture-map', {
        scrollWheelZoom: false,
    }).setView([40.4093, 49.8671], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);

    const bounds = [];

    const markerIcon = L.divIcon({
        className: '',
        html: '<div class="custom-marker"></div>',
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -36],
    });

    data.forEach(function (item) {
        if (!item.lat || !item.lng) return;

        const marker = L.marker([item.lat, item.lng], { icon: markerIcon }).addTo(map);
        bounds.push([item.lat, item.lng]);

        let media = '';
        if (item.panorama) {
            media = '<div class="map-panorama-wrap">' +
                        '<iframe class="map-panorama" src="' + extractSrc(item.panorama) + '" loading="lazy" allowfullscreen></iframe>' +
                    '</div>';
        } else if (item.image) {
            media = '<img class="map-popup-image" src="' + item.image + '" alt="">';
        } else {
            media = '<div class="map-popup-noimage">' +
                        '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/></svg>' +
                    '</div>';
        }

        const html =
            media +
            '<div class="map-popup-body">' +
                '<div class="map-popup-title">' + escapeHtml(item.title) + '</div>' +
                (item.city ? '<div class="map-popup-city">' + escapeHtml(item.city) + '</div>' : '') +
                '<a href="' + item.url + '" class="map-popup-btn">' +
                    '{{ __("frontend.view_details") }}' +
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>' +
                '</a>' +
            '</div>';

        marker.bindPopup(html, { maxWidth: 280, minWidth: 280 });
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

    function extractSrc(embed) {
        const match = embed.match(/src=["']([^"']+)["']/);
        return match ? match[1] : '';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }
});
</script>
@endpush
@endif
