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
    const post = await $fetch(`${config.apiBaseUrl}/api/guest/posts/${id}`);

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