const CACHE_NAME = 'followcrom-v3';
const PRECACHE_ASSETS = [
  '/',
  '/index.html',
  '/css/main.css',
  '/offline.html'
  // Add more static assets as needed
];

// Install event – Pre-cache critical assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS);
    }).catch((err) => {
      console.error('Error during installation:', err);
    })
  );
});

// Activate event – Cleanup old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            console.log(`Deleting old cache: ${key}`);
            return caches.delete(key);
          }
        })
      )
    )
  );
  event.waitUntil(self.clients.claim());
});

// Fetch event – Cache-first with network fallback + redirect fix + offline fallback
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        console.log('Serving from cache:', event.request.url);
        return cachedResponse;
      }

      return fetch(event.request).then((networkResponse) => {
        // Follow redirects safely
        if (networkResponse && networkResponse.redirected) {
          return fetch(networkResponse.url);
        }

        // Clone and cache the response if it's valid
        if (
          networkResponse &&
          networkResponse.status === 200 &&
          networkResponse.type === 'basic'
        ) {
          const responseClone = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseClone);
          });
        }

        return networkResponse;
      });
    }).catch((err) => {
      console.warn('Fetch failed; returning offline fallback:', err);

      // Return offline fallback for navigations (e.g., full page requests)
      if (event.request.mode === 'navigate') {
        return caches.match('/offline.html');
      }

      // Could also return fallback images or other resource types if desired
    })
  );
});
