@if ($sculptures->count())
<section class="relative" style="isolation: isolate;">
    <div class="flex h-screen relative">

        <div class="flex-1 relative" style="isolation: isolate;">
            <div id="sculpture-map" class="w-full h-full"></div>
        </div>

        {{-- Mobil üçün açma düyməsi --}}
        <button type="button" id="map-sidebar-toggle"
                class="lg:hidden absolute top-4 right-4 z-30 inline-flex items-center gap-2 pl-3 pr-4 py-2.5 rounded-full bg-slate-900/95 backdrop-blur text-white text-sm font-semibold shadow-2xl shadow-slate-900/30 border border-white/10 active:scale-95 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span id="map-sidebar-toggle-text">
                {{ __('frontend.map_title') }}
            </span>
            <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-indigo-500 text-white text-[11px] font-bold">
                {{ $sculptures->count() }}
            </span>
        </button>

        {{-- Mobil üçün qaralma --}}
        <div id="map-sidebar-backdrop"
             class="lg:hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 opacity-0 invisible transition-opacity duration-300"></div>

        {{-- Sidebar --}}
        <aside id="map-sidebar"
               class="fixed lg:static top-0 right-0 h-full w-[85%] max-w-sm lg:w-96 bg-white lg:border-l border-slate-200 flex flex-col shadow-2xl z-50 lg:z-10
                      translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out">

            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-start justify-between gap-4">
                <div>
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

                <button type="button" id="map-sidebar-close"
                        class="lg:hidden w-9 h-9 rounded-full bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 flex-shrink-0 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
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
        width: 26px;
        height: 26px;
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
        max-height: 480px;
        overflow-y: auto;
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

    .map-cluster-header {
        padding: 14px 16px;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .map-cluster-header-title {
        font-weight: 800;
        font-size: 14px;
    }
    .map-cluster-header-sub {
        font-size: 11px;
        color: rgba(255,255,255,.6);
        margin-top: 2px;
    }
    .map-cluster-list {
        max-height: 380px;
        overflow-y: auto;
    }
    .map-cluster-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: inherit;
        transition: background .2s;
    }
    .map-cluster-item:last-child {
        border-bottom: 0;
    }
    .map-cluster-item:hover {
        background: #f8fafc;
    }
    .map-cluster-item-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f1f5f9;
    }
    .map-cluster-item-info {
        flex: 1;
        min-width: 0;
    }
    .map-cluster-item-title {
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
    .map-cluster-item-meta {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .map-cluster-item-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 6px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 9px;
        font-weight: 700;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .map-cluster-item-arrow {
        flex-shrink: 0;
        color: #94a3b8;
        transition: transform .2s, color .2s;
    }
    .map-cluster-item:hover .map-cluster-item-arrow {
        color: #6366f1;
        transform: translateX(2px);
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

    /* Sidebar açıldıqda */
    #map-sidebar-backdrop.is-open {
        opacity: 1;
        visibility: visible;
    }
    #map-sidebar.is-open {
        transform: translateX(0) !important;
    }

    @media (max-width: 1023px) {
        .leaflet-popup-content {
            width: 260px !important;
        }
        .map-popup-media {
            height: 140px;
        }
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

    const sidebar = document.getElementById('map-sidebar');
    const sidebarToggle = document.getElementById('map-sidebar-toggle');
    const sidebarClose = document.getElementById('map-sidebar-close');
    const sidebarBackdrop = document.getElementById('map-sidebar-backdrop');

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('is-open');
        sidebarBackdrop.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('is-open');
        sidebarBackdrop.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
    if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });

    const map = L.map('sculpture-map', {
        scrollWheelZoom: false,
        zoomControl: true,
    }).setView([40.4093, 49.8671], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);

    const bounds = [];

    const statueSvg =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" fill="currentColor">' +
            '<path d="M352 400 L448 400 L442 688 L358 688 Z"/>' +
            '<path d="M380 230 C330 240 300 300 306 410 L320 495 L360 512 L440 512 L480 495 L494 410 C500 300 470 240 420 230 Z"/>' +
            '<rect x="375" y="200" width="50" height="40"/>' +
            '<circle cx="400" cy="165" r="45"/>' +
            '<circle cx="352" cy="165" r="8"/>' +
            '<circle cx="448" cy="165" r="8"/>' +
            '<path d="M355 160 C355 120 380 110 405 110 C435 110 450 130 450 155 C435 155 430 145 420 140 C410 135 390 145 375 145 C365 145 360 155 355 160 Z"/>' +
            '<rect x="266" y="688" width="268" height="32"/>' +
            '<rect x="286" y="720" width="228" height="96"/>' +
            '<path d="M250 816 C250 790 270 776 296 776 L504 776 C530 776 550 790 550 816 Z"/>' +
            '<rect x="226" y="816" width="348" height="40"/>' +
        '</svg>';

    const markerIcon = L.divIcon({
        className: '',
        html: '<div class="custom-marker">' + statueSvg + '</div>',
        iconSize: [44, 44],
        iconAnchor: [22, 22],
        popupAnchor: [0, -22],
    });

    function coordKey(lat, lng) {
        return lat.toFixed(5) + ',' + lng.toFixed(5);
    }

    const groups = {};
    data.forEach(function (item) {
        if (!item.lat || !item.lng) return;
        const key = coordKey(item.lat, item.lng);
        if (!groups[key]) {
            groups[key] = {
                key: key,
                lat: item.lat,
                lng: item.lng,
                items: [],
            };
        }
        groups[key].items.push(item);
    });

    const groupList = Object.values(groups);
    const markersByGroup = {};

    groupList.forEach(function (group) {
        const isCluster = group.items.length > 1;

        const marker = L.marker([group.lat, group.lng], { icon: markerIcon }).addTo(map);
        bounds.push([group.lat, group.lng]);

        const popupHtml = isCluster
            ? buildClusterPopup(group)
            : buildSinglePopup(group.items[0]);

        marker.bindPopup(popupHtml, { maxWidth: 320, minWidth: 260 });

        marker.on('click', function () {
            markActiveGroup(group.key);
        });

        markersByGroup[group.key] = marker;
        group.marker = marker;
    });

    function buildSinglePopup(item) {
        let media = '';
        let panoBtn = '';

        if (item.panorama) {
            media = (item.image
                        ? '<img class="map-popup-image" src="' + item.image + '" alt="">'
                        : '<div class="map-popup-noimage">' + statueSvg + '</div>')
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
            media = '<div class="map-popup-noimage">' + statueSvg + '</div>';
        }

        return '<div class="map-popup-media">' + media + '</div>' +
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
    }

    function buildClusterPopup(group) {
        let html =
            '<div class="map-cluster-header">' +
                '<div class="map-cluster-header-title">{{ __("frontend.map_cluster_title") }}</div>' +
                '<div class="map-cluster-header-sub">' + group.items.length + ' {{ __("frontend.sculptures") }}</div>' +
            '</div>' +
            '<div class="map-cluster-list">';

        group.items.forEach(function (item) {
            const thumb = item.image
                ? '<img src="' + item.image + '" class="map-cluster-item-thumb" alt="">'
                : '<div class="map-cluster-item-thumb" style="display:flex;align-items:center;justify-content:center;">' + statueSvg.replace('viewBox', 'style="width:22px;height:22px;color:#94a3b8" viewBox') + '</div>';

            const badges =
                (item.panorama ? '<span class="map-cluster-item-badge">360°</span>' : '') +
                (item.year ? '<span class="map-cluster-item-badge">' + item.year + '</span>' : '');

            html +=
                '<a href="' + item.url + '" class="map-cluster-item">' +
                    thumb +
                    '<div class="map-cluster-item-info">' +
                        '<div class="map-cluster-item-title">' + escapeHtml(item.title) + '</div>' +
                        '<div class="map-cluster-item-meta">' +
                            (item.city ? '<span>' + escapeHtml(item.city) + '</span>' : '') +
                            badges +
                        '</div>' +
                    '</div>' +
                    '<svg class="map-cluster-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>' +
                    '</svg>' +
                '</a>';
        });

        html += '</div>';

        return html;
    }

    // Sidebar — hər heykəl ayrı-ayrı
    data.forEach(function (item) {
        if (!item.lat || !item.lng || !listEl) return;

        const key = coordKey(item.lat, item.lng);

        const el = document.createElement('div');
        el.className = 'map-list-item';
        el.dataset.groupKey = key;
        el.dataset.itemId = item.id;

        const thumb = item.image
            ? '<img src="' + item.image + '" class="map-list-thumb" alt="">'
            : '<div class="map-list-thumb-empty">' + statueSvg + '</div>';

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
            focusOnItem(item, key);
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });

        listEl.appendChild(el);
    });

    function focusOnItem(item, key) {
        const marker = markersByGroup[key];
        if (!marker) return;

        map.flyTo([item.lat, item.lng], 15, { duration: 1.2 });
        setTimeout(function () {
            marker.openPopup();
            markActiveGroup(key, item.id);
        }, 600);
    }

    function markActiveGroup(key, itemId) {
        if (!listEl) return;

        document.querySelectorAll('.map-list-item').forEach(function (el) {
            const sameGroup = el.dataset.groupKey === key;
            const sameItem = el.dataset.itemId == itemId;
            el.classList.toggle('is-active', sameGroup && (!itemId || sameItem));
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
