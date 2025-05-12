const CACHE_NAME = 'followcrom-v4';
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
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        console.log('Found in cache:', event.request.url);
        return cachedResponse; // Return from cache if found
      }

      // If not found in cache, fetch from network
      return fetch(event.request)
        .catch(() => {
          console.error('Network request failed, serving offline page...');
          return caches.match('/offline.html'); // Fallback to offline page if network fails
        });
    }).catch((err) => {
      console.error('Error fetching resource:', err);
      return fetch(event.request); // Fallback to network if cache fails
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

  
