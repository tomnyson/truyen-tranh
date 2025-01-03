import axios from 'axios';

export default defineEventHandler(async (event): Promise<any> => {
  const config = useRuntimeConfig();

  try {
    const headers = getHeaders(event);

    const { data } = await axios.get(`${config.apiBaseUrl}/api/current-user`, {
      headers: {
        Authorization: headers.authorization,
      },
    });


    return data;
  } catch (error: any) {
    console.error('Error fetching current user:', error.message);
    throw createError({
      statusCode: error.response?.status || 500,
      message: error.response?.data?.message || 'Internal Server Error',
    });
  }
});