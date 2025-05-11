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

self.addEventListener('fetch', (event) => {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
  
        // Ensure redirects are followed manually
        const fetchRequest = event.request.clone();
  
        return fetch(fetchRequest, { redirect: 'follow' })
          .then((response) => {
            if (
              !response ||
              response.status === 0 ||
              response.type === 'opaqueredirect'
            ) {
              throw new Error('Invalid or redirected response');
            }
            return response;
          })
          .catch((error) => {
            console.error('Fetch failed:', error);
            // Optionally: return cached offline.html here
            return caches.match('/offline.html');
          });
      })
    );
  });
  
