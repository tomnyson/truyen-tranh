import { JSDOM } from 'jsdom';
export const IMAGE_URL = process.env.PUBLIC_API_BASE_URL || 'http://localhost:8000'
function stripHtml(html: string): string {
  if (typeof window !== 'undefined' && window.DOMParser) {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      return doc.body.textContent || '';
  } else {
      // Fallback: Remove tags using regex (less accurate)
      return html.replace(/<[^>]*>/g, '');
  }
}

export const truncatedContent = (content: string, maxLength: number = 100): string => {
  const plainText = stripHtml(content);
  if (plainText.length <= maxLength) return plainText;
  return plainText.slice(0, maxLength) + '...';
};
  export const formatDate = (dateStr: string): string => {
    const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' }
    const date = new Date(dateStr)
    return date.toLocaleDateString(undefined, options)
  }

 export const getMedia = (media: string): string => {

    console.log(IMAGE_URL)
    return `${IMAGE_URL}/storage/${media}`
 }

 export const sleep = (ms: number = 1000): Promise<void> => {
    return new Promise((resolve) => setTimeout(resolve, ms))
 }