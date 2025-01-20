import { JSDOM } from 'jsdom';
export const IMAGE_URL = () => {
  if (typeof process !== 'undefined' && process.env) {
    const publicApiBaseUrl = process.env.NUXT_PUBLIC_API_BASE_URL || 'http://localhost:8000';
    console.log('PUBLIC_API_BASE_URL:', process.env); // Log only the relevant variable
    return publicApiBaseUrl;
  }

  // Fallback if `process.env` is not defined (e.g., browser environment)
  console.warn('process.env is not available. Falling back to default URL.');
  return 'http://localhost:8000';
};

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


  export const getMedia = (domain: string, media: string): string => {
    const imageUrl = domain || IMAGE_URL();
    return `${imageUrl}/storage/${media}`;
  };

 export const sleep = (ms: number = 1000): Promise<void> => {
    return new Promise((resolve) => setTimeout(resolve, ms))
 }