self.addEventListener('install', event => {
  event.waitUntil(caches.open('librepress-v1').then(cache => cache.addAll(['/', '/blog'])));
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;
  event.respondWith(
    caches.match(event.request).then(response => response || fetch(event.request))
  );
});

