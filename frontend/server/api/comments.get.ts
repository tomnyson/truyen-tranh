import { defineEventHandler, getQuery } from 'h3'

interface CommentResponse {
  data: any[]
}

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig()

  const query = getQuery(event)

  const queryString = new URLSearchParams(query as Record<string, string>).toString()

  const data = await $fetch<CommentResponse>(`${config.apiBaseUrl}/api/guest/comments?${queryString}`)
  return data
})