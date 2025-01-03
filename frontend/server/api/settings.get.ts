export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig()
  const data: { data: any } = await $fetch(`${config.apiBaseUrl}/api/guest/settings`)
  console.log('settings', data)
  return data
})