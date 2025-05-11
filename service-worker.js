const CACHE_NAME = 'v2'; // Use a variable for the cache name
const resourcesToCache = [
  '/',
  '/index.html',
  '/css/main.css',
  // Add other resources you want to cache
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(resourcesToCache);
    }).catch((err) => {
      console.error('Error during service worker installation:', err);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        console.log('Found in cache:', event.request.url);
        return cachedResponse; // Return the cached response if found
      }
      // If not found in cache, fetch from network
      return fetch(event.request);
    }).catch((err) => {
      console.error('Error fetching resource:', err);
      return fetch(event.request); // Fallback to network if cache fails
    })
  );
});

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
