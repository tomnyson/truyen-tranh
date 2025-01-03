import { defineEventHandler, getQuery } from 'h3'

interface PostResponse {
  data: any[]
}

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig()

  const query = getQuery(event)

  console.log('query', query)
  const data = await $fetch<PostResponse>(`${config.apiBaseUrl}/api/guest/posts`, {
    params: query
  })
  console.log('data', data)

  return data.data
})