const CACHE_NAME = 'swimrun-pwa-v1';
const urlsToCache = [
  './',
  './index.php',
  './simulator.php',
  './livebuchungen.php',
  './vendor/twbs/bootstrap/dist/css/bootstrap.min.css',
  './vendor/components/jquery/jquery.min.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // We use catch to avoid stopping the whole installation if one file fails to cache
        return Promise.allSettled(
          urlsToCache.map(url => cache.add(url))
        );
      })
  );
});

// Network-first strategy for an API-heavy app, falling back to cache
self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;
  
  event.respondWith(
    fetch(event.request).then(response => {
      // Return fresh response and update cache in background
      let responseClone = response.clone();
      caches.open(CACHE_NAME).then(cache => {
        cache.put(event.request, responseClone);
      });
      return response;
    }).catch(() => {
      // If network fails, try cache
      return caches.match(event.request);
    })
  );
});
