// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2025-05-15',
    devtools: { enabled: true },
    modules: ['@nuxtjs/tailwindcss', '@pinia/nuxt'],
    runtimeConfig: {
        public: {
            apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://127.0.0.1:8000/api'
        }
    }
})