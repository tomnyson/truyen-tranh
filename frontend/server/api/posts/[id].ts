export default defineEventHandler(async (event) => {
  const { id } = event.context.params;
  if (!id) {
    return {
      statusCode: 400,
      body: { error: 'Post ID is required' },
    };
  }

  // Access runtime config
  const config = useRuntimeConfig();

  try {
    // Fetch the post from the external API
    const headers = getHeaders(event);
    const requestHeaders: HeadersInit = {};
    if (headers.authorization) {
      requestHeaders.Authorization = headers.authorization;
    }
    const post = await $fetch(`${config.apiBaseUrl}/api/guest/posts/${id}`,{
      method: 'GET',
      headers: requestHeaders
    });

    if (!post) {
      return {
        statusCode: 404,
        body: { error: 'Post not found' },
      };
    }

    // Return the post data
    return post;
  } catch (error) {
    // Handle API errors
    return {
      statusCode: 500,
      body: { error: 'An error occurred while fetching the post' },
    };
  }
});