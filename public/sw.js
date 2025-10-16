const CACHE_NAME = 'laserlink-v2';
const urlsToCache = [
  '/',
  '/produtos',
  '/favoritos'
];

// Install
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // Usar addAll com tratamento de erro
        return cache.addAll(urlsToCache).catch(err => {
          // Ignorar erros de cache
          // Continuar mesmo se alguns arquivos falharem
          return Promise.resolve();
        });
      })
  );
  // Ativar imediatamente
  self.skipWaiting();
});

// Fetch
self.addEventListener('fetch', event => {
  // Ignorar chrome-extension:// e outras URLs não-HTTP
  if (!event.request.url.startsWith('http')) {
    return;
  }
  
  // NÃO cachear rotas de admin, API, login ou autenticação
  if (event.request.url.includes('/admin') || 
      event.request.url.includes('/api/') ||
      event.request.url.includes('/login') ||
      event.request.url.includes('/logout') ||
      event.request.url.includes('/register') ||
      event.request.method !== 'GET') {
    return event.respondWith(fetch(event.request));
  }
  
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit - retornar resposta
        if (response) {
          return response;
        }

        return fetch(event.request).then(
          fetchResponse => {
            // Verificar se recebemos uma resposta válida
            if(!fetchResponse || fetchResponse.status !== 200 || fetchResponse.type !== 'basic') {
              return fetchResponse;
            }

            // Clonar a resposta
            const responseToCache = fetchResponse.clone();

            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              })
              .catch(err => {
                // Ignorar erros de cache
              });

            return fetchResponse;
          }
        ).catch(err => {
          // Propagar erro
          throw err;
        });
      })
  );
});

// Activate
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

