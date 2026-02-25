const CACHE_NAME = "ecommerce-v1";

self.addEventListener("install", function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            return cache.addAll([
                "/",
            ]);
        })
    );
});

self.addEventListener("fetch", function(event) {
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});