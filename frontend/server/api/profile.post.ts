import axios from 'axios';

export default defineEventHandler(async (event): Promise<any> => {
  const config = useRuntimeConfig();

  try {
    const headers = getHeaders(event);
    const body = await readBody(event);

    console.log('Incoming request headers:', headers);
    console.log('Incoming request body:', body);

    const { data } = await axios.post(
      `${config.apiBaseUrl}/api/profile`,
      body,
      {
        headers: {
          ...headers,
          Authorization: headers.authorization || '',
        },
      }
    );

    console.log('Response from backend API:', data);

    return data;
  } catch (error: any) {
    console.error('Error posting data to backend API:', {
      message: error.message,
      stack: error.stack,
      response: error.response?.data,
    });

    throw createError({
      statusCode: error.response?.status || 500,
      message: error.response?.data?.message || 'Internal Server Error',
    });
  }
});