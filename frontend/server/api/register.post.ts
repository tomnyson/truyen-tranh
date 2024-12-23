import axios from 'axios';

export default defineEventHandler(async (event): Promise<any> => {
  const config = useRuntimeConfig();
  const body = await readBody(event);

  try {
    // Make the POST request using axios
    const { data } = await axios.post(`${config.apiBaseUrl}/api/register`, body, {
      headers: {
        'Content-Type': 'application/json',
      },
    });
    console.log('Login request successful:', data);

    // Return only the response data
    return data;
  } catch (error: any) {
    console.error('Login request failed:', error.message);

    // Throw a custom error for the Nuxt error handler
    throw createError({
      statusCode: error.response?.status || 500,
      message: error.response?.data?.message || 'Internal Server Error',
    });
  }
});