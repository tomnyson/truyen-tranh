export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig() // Access runtime config
  const data: { data: any } = await $fetch(`${config.apiBaseUrl}/api/guest/tags`)
  return data.data
})