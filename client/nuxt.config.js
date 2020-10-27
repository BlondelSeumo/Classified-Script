require('dotenv').config()

const getAppRoutes = require('./utils/getRoutes.js');

module.exports = {
  srcDir: __dirname,

  env: {
    clientUrl: process.env.CLIENT_URL,
    mobileUrl: process.env.MOBILE_URL,
    apiUrl: process.env.API_URL,
    serverUrl: process.env.SERVER_URL,
    appName: process.env.APP_NAME || 'EVEREST',
    appLocale: process.env.APP_LOCALE || 'en',
    recaptchaKey: process.env.RECAPTCHA_KEY,
    googleAdsense: process.env.GOOGLE_ADSENSE,
    googleAuthKey: process.env.GOOGLE_AUTH_KEY,
    facebookAuthKey: process.env.FACEBOOK_AUTH_KEY,
  },

  head: {
    title: process.env.APP_NAME,
    titleTemplate: '%s - ' + process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, user-scalable=no' }
    ],
    link: [
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Hind:400,500,600,700|Material+Icons' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Oswald:300,400,500' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' },
      { rel: 'stylesheet', type: 'text/css', href: '//fonts.googleapis.com/earlyaccess/notokufiarabic.css' },
      { rel: 'dns-prefetch', href: '//ajax.googleapis.com' },
      { rel: 'dns-prefetch', href: '//apis.google.com' },
      { rel: 'dns-prefetch', href: '//fonts.googleapis.com' },
      { rel: 'dns-prefetch', href: '//fonts.gstatic.com' },
      { rel: 'dns-prefetch', href: '//www.google-analytics.com' },
      { rel: 'dns-prefetch', href: '//www.googletagmanager.com' },
      { rel: 'dns-prefetch', href: '//www.googletagservices.com' },
      { rel: 'dns-prefetch', href: '//adservice.google.com' },
      { rel: 'dns-prefetch', href: '//pagead2.googlesyndication.com' },
      { rel: 'dns-prefetch', href: '//tpc.googlesyndication.com' },
      { rel: 'dns-prefetch', href: '//connect.facebook.net' },
      { rel: 'dns-prefetch', href: '//platform.twitter.com' },
      { rel: 'dns-prefetch', href: '//platform.linkedin.com' },
      { rel: 'dns-prefetch', href: '//ad.doubleclick.net' },
      { rel: 'dns-prefetch', href: '//googleads.g.doubleclick.net' },
      { rel: 'dns-prefetch', href: '//stats.g.doubleclick.net' },
      { rel: 'dns-prefetch', href: '//cm.g.doubleclick.net' },
    ]
  },

  loading: { color: '#007bff' },

  router: {
    middleware: ['mobile', 'locale']
  },

  css: [
    'swiper/dist/css/swiper.css',
    { src: '~assets/sass/app.scss', lang: 'scss' }
  ],

  plugins: [
    '~plugins/mixins/user',
    '~plugins/i18n',
    '~plugins/axios',
    '~plugins/vuetify',
    '~plugins/helpers',
    '~plugins/qrcode',
    '~plugins/gates',
    '~plugins/bootstrap',
    { src: '~/plugins/amCharts', ssr: false },
    { src: '~/plugins/ckeditor', ssr: false },
    { src: '~/plugins/swiper', ssr: false },
    { src: '~/plugins/sticky', ssr: false },
    { src: '~/plugins/stripe', ssr: false },
    { src: '~/plugins/noSSR', ssr: false },
    { src: '~/plugins/echo', ssr: false },
  ],

  modules: [
    '@nuxtjs/router',
    '@nuxtjs/axios',
    '@nuxtjs/proxy',
    '@nuxtjs/auth',
    '@nuxtjs/toast',
    '@nuxtjs/sitemap',
    '@nuxtjs/device',
    '@nuxtjs/recaptcha',
    '@nuxtjs/laravel-echo',
    '@nuxtjs/google-adsense',
    ['@nuxtjs/html-minifier', { log: 'once', logHtml: true }]
  ],

  axios: {
    baseURL: process.env.API_URL
  },
  
  proxy: {
    '/api/': { target: process.env.API_URL, pathRewrite: {'^/api/': ''}, changeOrigin: true },
  },

  auth: {
    redirect: {
      login: '/auth/login',
      logout: '/',
      callback: '/auth/login',
      home: '/'
    },
    cookie: {
      prefix: 'everest.',
      options: {
        maxAge: 604800 // One week
      }
    },
    resetOnError: true,
    watchLoggedIn: true,
    plugins: [ '~/plugins/auth.js' ],
    strategies: {
      local: {
        endpoints: {
          login: { url: '/auth/login', method: 'post', propertyName: 'access_token' },
          logout: { url: '/auth/logout', method: 'post' },
          user: { url: '/auth/user', method: 'get', propertyName: false}
        },
        tokenRequired: true,
        tokenType: 'bearer'
      },
      facebook: {
        client_id: process.env.FACEBOOK_AUTH_KEY,
        userinfo_endpoint: 'https://graph.facebook.com/v2.12/me?fields=about,name,picture{url},email',
        redirect_uri: `${process.env.CLIENT_URL}/auth/facebook/callback`,
        token_key: 'access_token',
        response_type: 'token',
        scope: ['public_profile', 'email']
      },
      google: {
        client_id: process.env.GOOGLE_AUTH_KEY,
        redirect_uri: `${process.env.CLIENT_URL}/auth/google/callback`
      },
    }
  },

  'google-adsense': {
    id: process.env.GOOGLE_ADSENSE
  },

  toast: {
      position: "bottom-center",
      duration : 8000,
      className: "toastedNotification",
      theme: "toasted-primary"
  },

  recaptcha: {
    hideBadge: true,
    language: process.env.APP_LOCALE,
    siteKey: process.env.RECAPTCHA_KEY,
    version: 3
  },

  sitemap: {
    routes() {
      return getAppRoutes();
    },
    gzip: true,
    exclude: [
      '/administrator/**',
      '/moderator/**',
      '/manager/**',
    ],
    defaults: {
      changefreq: 'daily',
      priority: 1,
      lastmod: new Date(),
      lastmodrealtime: true
    }
  },
  
  build: {
    extractCSS: true
  }
}