self.CACHE_NAME = 'librepress-v2';

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(caches.open(self.CACHE_NAME).then(cache => cache.addAll(['/', '/blog'])));
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys()
      .then(keys => Promise.all(keys.filter(key => key !== self.CACHE_NAME).map(key => caches.delete(key))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;
  const url = new URL(event.request.url);
  if (url.pathname.startsWith('/manage') || url.pathname.startsWith('/livewire') || url.pathname.startsWith('/js/filament') || url.pathname.startsWith('/css/filament') || url.pathname.startsWith('/fonts/filament')) return;
  event.respondWith(
    caches.match(event.request).then(response => response || fetch(event.request))
  );
});
