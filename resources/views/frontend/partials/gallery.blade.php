{{--
    Product photo gallery: thumbnails, hover zoom on desktop, swipe on phones, full-screen view.
    Expects $photos (paths under public/, main photo first) and $alt.
--}}
<div class="pg" data-gallery>
    @if(count($photos) > 1)
    <div class="pg-thumbs" role="tablist" aria-label="Product photos">
        @foreach($photos as $i => $photo)
        <button type="button" class="pg-thumb {{ $i === 0 ? 'is-active' : '' }}" data-gallery-thumb="{{ $i }}"
            role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Photo {{ $i + 1 }} of {{ count($photos) }}">
            <img src="{{ asset($photo) }}" alt="" loading="lazy">
        </button>
        @endforeach
    </div>
    @endif

    <div class="pg-stage" data-gallery-stage>
        @forelse($photos as $i => $photo)
        <img src="{{ asset($photo) }}" alt="{{ $alt }}{{ $i ? ' — photo '.($i + 1) : '' }}"
            class="pg-photo {{ $i === 0 ? 'is-active' : '' }}" data-gallery-photo="{{ $i }}"
            loading="{{ $i === 0 ? 'eager' : 'lazy' }}" draggable="false">
        @empty
        <div class="pg-empty">No photo yet</div>
        @endforelse

        @if(count($photos) > 1)
        <button type="button" class="pg-arrow pg-prev" data-gallery-step="-1" aria-label="Previous photo">&#8249;</button>
        <button type="button" class="pg-arrow pg-next" data-gallery-step="1" aria-label="Next photo">&#8250;</button>
        <span class="pg-count"><span data-gallery-current>1</span> / {{ count($photos) }}</span>
        @endif
        @if(count($photos))
        <span class="pg-hint">Hover to zoom · click to enlarge</span>
        @endif
    </div>

    <div class="pg-lightbox" data-gallery-lightbox hidden>
        <button type="button" class="pg-close" data-gallery-close aria-label="Close">&times;</button>
        <img src="" alt="{{ $alt }}" data-gallery-full>
    </div>
</div>

@once
<style>
    .pg { display: flex; gap: 12px; }
    .pg-thumbs { display: flex; flex-direction: column; gap: 8px; max-height: 520px; overflow-y: auto; flex-shrink: 0; scrollbar-width: thin; padding: 2px; }
    .pg-thumb { width: 68px; height: 68px; flex-shrink: 0; border: 2px solid #e5e7eb; border-radius: 10px; background: #fff; padding: 3px; cursor: pointer; overflow: hidden; transition: border-color .15s; }
    .pg-thumb:hover { border-color: #9ca3af; }
    .pg-thumb.is-active { border-color: #2563eb; }
    .pg-thumb img { width: 100%; height: 100%; object-fit: contain; }
    .pg-stage { position: relative; flex: 1; min-width: 0; aspect-ratio: 1 / 1; max-height: 520px; border: 1px solid #e5e7eb; border-radius: 14px; background: #fff; overflow: hidden; cursor: zoom-in; touch-action: pan-y; }
    .pg-photo { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; padding: 14px; opacity: 0; pointer-events: none; transition: opacity .2s, transform .12s ease-out; user-select: none; }
    .pg-photo.is-active { opacity: 1; }
    .pg-empty { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px; }
    .pg-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border-radius: 9999px; border: 1px solid #e5e7eb; background: rgba(255,255,255,.92); color: #111827; font-size: 22px; line-height: 1; cursor: pointer; opacity: 0; transition: opacity .15s; }
    .pg-stage:hover .pg-arrow, .pg-arrow:focus-visible { opacity: 1; }
    .pg-prev { left: 10px; } .pg-next { right: 10px; }
    .pg-count { position: absolute; right: 10px; bottom: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(17,24,39,.65); color: #fff; font-size: 11px; }
    .pg-hint { position: absolute; left: 10px; bottom: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(255,255,255,.9); color: #6b7280; font-size: 11px; }
    .pg-lightbox { position: fixed; inset: 0; z-index: 1000; background: rgba(0,0,0,.9); display: flex; align-items: center; justify-content: center; padding: 16px; cursor: zoom-out; }
    .pg-lightbox[hidden] { display: none; }
    .pg-lightbox img { max-width: 100%; max-height: 100%; object-fit: contain; background: #fff; border-radius: 8px; }
    .pg-close { position: absolute; top: 12px; right: 16px; width: 40px; height: 40px; border: 0; border-radius: 9999px; background: rgba(255,255,255,.15); color: #fff; font-size: 26px; line-height: 1; cursor: pointer; }
    @media (max-width: 1023px) {
        .pg { flex-direction: column-reverse; }
        .pg-thumbs { flex-direction: row; max-height: none; overflow-x: auto; overflow-y: hidden; }
        .pg-thumb { width: 60px; height: 60px; }
    }
    /* Touch screens cannot hover: always show the arrows, and say "tap" instead. */
    @media (hover: none) {
        .pg-arrow { opacity: 1; }
        .pg-hint { display: none; }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gallery]').forEach(function (root) {
        var stage    = root.querySelector('[data-gallery-stage]');
        var photos   = Array.prototype.slice.call(root.querySelectorAll('[data-gallery-photo]'));
        var thumbs   = Array.prototype.slice.call(root.querySelectorAll('[data-gallery-thumb]'));
        var counter  = root.querySelector('[data-gallery-current]');
        var lightbox = root.querySelector('[data-gallery-lightbox]');
        var full     = root.querySelector('[data-gallery-full]');
        var canHover = window.matchMedia('(hover: hover)').matches;
        var current  = 0;
        var ZOOM     = 2.2;

        if (!photos.length) return;

        function resetZoom() {
            photos.forEach(function (p) { p.style.transform = ''; p.style.transformOrigin = ''; });
        }

        function show(index) {
            current = (index + photos.length) % photos.length;
            resetZoom();
            photos.forEach(function (p, i) { p.classList.toggle('is-active', i === current); });
            thumbs.forEach(function (t, i) {
                t.classList.toggle('is-active', i === current);
                t.setAttribute('aria-selected', i === current ? 'true' : 'false');
            });
            if (thumbs[current]) thumbs[current].scrollIntoView({ block: 'nearest', inline: 'nearest' });
            if (counter) counter.textContent = current + 1;
            if (!lightbox.hidden) full.src = photos[current].src;
        }

        thumbs.forEach(function (thumb, i) {
            thumb.addEventListener('click', function () { show(i); });
            if (canHover) thumb.addEventListener('mouseenter', function () { show(i); });
        });

        root.querySelectorAll('[data-gallery-step]').forEach(function (arrow) {
            arrow.addEventListener('click', function (e) {
                e.stopPropagation();
                show(current + parseInt(arrow.getAttribute('data-gallery-step'), 10));
            });
        });

        // Hover zoom: magnify the photo around the cursor.
        if (canHover) {
            stage.addEventListener('mousemove', function (e) {
                if (e.target.closest('[data-gallery-step]')) { resetZoom(); return; }
                var box = stage.getBoundingClientRect();
                var x = ((e.clientX - box.left) / box.width) * 100;
                var y = ((e.clientY - box.top) / box.height) * 100;
                photos[current].style.transformOrigin = x + '% ' + y + '%';
                photos[current].style.transform = 'scale(' + ZOOM + ')';
            });
            stage.addEventListener('mouseleave', resetZoom);
        }

        // Swipe left / right on touch screens.
        var startX = null;
        stage.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
        stage.addEventListener('touchend', function (e) {
            if (startX === null) return;
            var moved = e.changedTouches[0].clientX - startX;
            startX = null;
            if (Math.abs(moved) > 40 && photos.length > 1) {
                show(current + (moved < 0 ? 1 : -1));
                stage.dataset.swiped = '1';
            }
        });

        // Click or tap opens the photo full screen (not when the touch was a swipe).
        stage.addEventListener('click', function () {
            if (stage.dataset.swiped) { delete stage.dataset.swiped; return; }
            full.src = photos[current].src;
            lightbox.hidden = false;
            document.body.style.overflow = 'hidden';
        });

        function close() {
            lightbox.hidden = true;
            document.body.style.overflow = '';
        }
        lightbox.addEventListener('click', close);

        document.addEventListener('keydown', function (e) {
            if (lightbox.hidden) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowRight') show(current + 1);
            if (e.key === 'ArrowLeft') show(current - 1);
        });
    });
});
</script>
@endonce
