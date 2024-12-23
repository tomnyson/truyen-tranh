export default defineNuxtPlugin((nuxtApp) => {
    const config = useRuntimeConfig();
  
    // Create an API client using $fetch
    const api = $fetch.create({
      baseURL: config.public.apiBaseUrl,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
      onRequest({ options }) {
        // Access the authentication token from useAuth()
        if (process.client) {
          const { getSession } = useAuth();
          getSession().then((session) => {
            if (session?.accessToken) {
              options.headers = {
                ...options.headers,
                Authorization: `Bearer ${session.accessToken}`,
              };
            }
          });
        }
      },
    });
  
    // Provide the API client globally
    return {
      provide: {
        api,
      },
    };
  });