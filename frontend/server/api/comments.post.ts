import axios from 'axios';

export default defineEventHandler(async (event): Promise<any> => {
  const config = useRuntimeConfig();

  try {
    const headers = getHeaders(event);
    const body = await readBody(event); // Use readBody to correctly parse the body

    const { data } = await axios.post(
      `${config.apiBaseUrl}/api/comments`,
      body, // Pass the body correctly
      {
        headers: {
          Authorization: headers.authorization || '', // Forward the Authorization token if available
        },
      }
    );

    return data;
  } catch (error: any) {
    console.error('Error posting data:', error.message);
    throw createError({
      statusCode: error.response?.status || 500,
      message: error.response?.data?.message || 'Internal Server Error',
    });
  }
});