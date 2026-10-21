const CACHE = 'hrflow-v1';
const SHELL = ['/manifest.webmanifest', '/icons/icon-192.png'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.addAll(SHELL)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

self.addEventListener('push', (event) => {
    let data = { title: 'HR Flow', body: 'Bạn có thông báo mới', url: '/employee/dashboard' };
    try {
        data = { ...data, ...(event.data ? event.data.json() : {}) };
    } catch (e) {}
    event.waitUntil(self.registration.showNotification(data.title, {
        body: data.body,
        icon: '/icons/icon-192.png',
        data: { url: data.url || '/employee/dashboard' }
    }));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/employee/dashboard';
    event.waitUntil(clients.openWindow(url));
});
