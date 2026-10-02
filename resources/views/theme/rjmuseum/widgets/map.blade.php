@if ($sculptures->count())
<section class="relative">
    <div class="flex h-screen">

        <div class="flex-1 relative">
            <div id="sculpture-map" class="w-full h-full"></div>
        </div>

        <aside class="w-96 bg-white border-l border-slate-200 flex flex-col shadow-2xl z-10">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <div class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-1">
                    {{ __('frontend.map') }}
                </div>
                <h2 class="text-lg font-black text-slate-900">
                    {{ __('frontend.map_title') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $sculptures->count() }} {{ __('frontend.sculptures') }}
                </p>
            </div>

            <div id="map-list" class="flex-1 overflow-y-auto"></div>
        </aside>

    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    #sculpture-map {
        background: #0f172a;
    }
    .custom-marker {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.5);
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .custom-marker:hover {
        transform: scale(1.15);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.7);
    }
    .custom-marker svg {
        width: 22px;
        height: 22px;
        color: #ffffff;
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

    .map-list-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }
    .map-list-item:hover {
        background: #f8fafc;
    }
    .map-list-item.is-active {
        background: #eef2ff;
        border-left: 3px solid #6366f1;
        padding-left: 17px;
    }
    .map-list-thumb {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f1f5f9;
    }
    .map-list-thumb-empty {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .map-list-thumb-empty svg {
        width: 20px;
        height: 20px;
        color: #94a3b8;
    }
    .map-list-info {
        flex: 1;
        min-width: 0;
    }
    .map-list-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 2px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .map-list-city {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .map-list-city svg {
        width: 10px;
        height: 10px;
    }

    #map-list::-webkit-scrollbar {
        width: 6px;
    }
    #map-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
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
    const listEl = document.getElementById('map-list');

    const map = L.map('sculpture-map', {
        scrollWheelZoom: false,
        zoomControl: true,
    }).setView([40.4093, 49.8671], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);

    const bounds = [];
    const markers = {};

    const markerIcon = L.divIcon({
        className: '',
        html: '<div class="custom-marker">' +
            '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>' +
            '</svg>' +
        '</div>',
        iconSize: [44, 44],
        iconAnchor: [22, 22],
        popupAnchor: [0, -22],
    });

    data.forEach(function (item) {
        if (!item.lat || !item.lng) return;

        const marker = L.marker([item.lat, item.lng], { icon: markerIcon }).addTo(map);
        markers[item.id] = marker;
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

        marker.on('click', function () {
            setActiveItem(item.id);
        });
    });

    data.forEach(function (item) {
        if (!item.lat || !item.lng || !listEl) return;

        const el = document.createElement('div');
        el.className = 'map-list-item';
        el.dataset.id = item.id;

        const thumb = item.image
            ? '<img src="' + item.image + '" class="map-list-thumb" alt="">'
            : '<div class="map-list-thumb-empty">' +
                '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/>' +
                '</svg>' +
              '</div>';

        el.innerHTML =
            thumb +
            '<div class="map-list-info">' +
                '<div class="map-list-title">' + escapeHtml(item.title) + '</div>' +
                (item.city
                    ? '<div class="map-list-city">' +
                        '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' +
                        escapeHtml(item.city) +
                      '</div>'
                    : '') +
            '</div>';

        el.addEventListener('click', function () {
            focusOn(item);
        });

        listEl.appendChild(el);
    });

    function focusOn(item) {
        if (!markers[item.id]) return;

        map.flyTo([item.lat, item.lng], 15, { duration: 1.2 });
        setTimeout(function () {
            markers[item.id].openPopup();
            setActiveItem(item.id);
        }, 600);
    }

    function setActiveItem(id) {
        if (!listEl) return;

        document.querySelectorAll('.map-list-item').forEach(function (el) {
            el.classList.toggle('is-active', el.dataset.id == id);
        });

        const active = listEl.querySelector('.map-list-item.is-active');
        if (active) {
            active.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

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
