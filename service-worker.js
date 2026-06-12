const CACHE_NAME = 'followcrom-v8';
const resourcesToCache = [
  '/', // Cache the root, which will resolve to index.html
  '/css/main.css',
  '/offline.html', // If you have an offline page
];

// Install event - Precache resources
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(resourcesToCache);
      })
      .catch((err) => {
        console.error('Error during service worker installation:', err);
      })
  );
});

// Fetch event - Cache-first strategy with network fallback
self.addEventListener('fetch', (event) => {
  // Only handle same-origin GET requests; let the browser deal with the rest
  // (fonts, analytics, POSTs to the contact form, etc.)
  if (
    event.request.method !== 'GET' ||
    !event.request.url.startsWith(self.location.origin)
  ) {
    return;
  }

  // Pages: network-first, so a deploy is picked up on the next visit.
  // Falls back to cache, then offline.html.
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((networkResponse) => {
          const responseClone = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseClone);
          });
          return networkResponse;
        })
        .catch(() =>
          caches
            .match(event.request)
            .then((cached) => cached || caches.match('/offline.html'))
        )
    );
    return;
  }

  // Static assets (images, CSS, JS): cache-first
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        return cachedResponse; // Return from cache if found
      }

      // If not found in cache, fetch from network and cache the result
      return fetch(event.request)
        .then((networkResponse) => {
          if (networkResponse.ok) {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseClone);
            });
          }
          return networkResponse;
        })
        .catch(() => {
          // No offline.html here: an HTML response in place of an
          // image/CSS/JS file would render as a broken asset.
          return Response.error();
        });
    })
  );
});

// Activate event - Clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(
        keyList.map((key) => {
          if (key !== CACHE_NAME) {
            console.log(`Deleting old cache: ${key}`);
            return caches.delete(key); // Delete old caches
          }
        })
      );
    }).catch((err) => {
      console.error('Error cleaning old caches:', err);
    })
  );

  // Take control of the clients immediately
  event.waitUntil(self.clients.claim());
});

  
