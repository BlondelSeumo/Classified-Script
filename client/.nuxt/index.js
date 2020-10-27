import Vue from 'vue'
import Meta from 'vue-meta'
import ClientOnly from 'vue-client-only'
import NoSsr from 'vue-no-ssr'
import { createRouter } from './router.js'
import NuxtChild from './components/nuxt-child.js'
import NuxtError from './components/nuxt-error.vue'
import Nuxt from './components/nuxt.js'
import App from './App.js'
import { setContext, getLocation, getRouteData, normalizeError } from './utils'
import { createStore } from './store.js'

/* Plugins */

import nuxt_plugin_recaptcha_7934dbe3 from 'nuxt_plugin_recaptcha_7934dbe3' // Source: .\\recaptcha.js (mode: 'all')
import nuxt_plugin_libplugin78fb7d41_aa8ce49e from 'nuxt_plugin_libplugin78fb7d41_aa8ce49e' // Source: .\\lib.plugin.78fb7d41.js (mode: 'all')
import nuxt_plugin_toast_32bd17ba from 'nuxt_plugin_toast_32bd17ba' // Source: .\\toast.js (mode: 'client')
import nuxt_plugin_axios_1a712774 from 'nuxt_plugin_axios_1a712774' // Source: .\\axios.js (mode: 'all')
import nuxt_plugin_router_2fff5a8a from 'nuxt_plugin_router_2fff5a8a' // Source: .\\router.js (mode: 'all')
import nuxt_plugin_user_6caa611e from 'nuxt_plugin_user_6caa611e' // Source: ..\\plugins\\mixins\\user (mode: 'all')
import nuxt_plugin_i18n_56ca5e75 from 'nuxt_plugin_i18n_56ca5e75' // Source: ..\\plugins\\i18n (mode: 'all')
import nuxt_plugin_axios_fb9c9a02 from 'nuxt_plugin_axios_fb9c9a02' // Source: ..\\plugins\\axios (mode: 'all')
import nuxt_plugin_vuetify_0e1f10d7 from 'nuxt_plugin_vuetify_0e1f10d7' // Source: ..\\plugins\\vuetify (mode: 'all')
import nuxt_plugin_helpers_0e9b0ece from 'nuxt_plugin_helpers_0e9b0ece' // Source: ..\\plugins\\helpers (mode: 'all')
import nuxt_plugin_qrcode_420a23b6 from 'nuxt_plugin_qrcode_420a23b6' // Source: ..\\plugins\\qrcode (mode: 'all')
import nuxt_plugin_gates_fb0818de from 'nuxt_plugin_gates_fb0818de' // Source: ..\\plugins\\gates (mode: 'all')
import nuxt_plugin_bootstrap_0f900877 from 'nuxt_plugin_bootstrap_0f900877' // Source: ..\\plugins\\bootstrap (mode: 'all')
import nuxt_plugin_amCharts_4dac9117 from 'nuxt_plugin_amCharts_4dac9117' // Source: ..\\plugins\\amCharts (mode: 'client')
import nuxt_plugin_ckeditor_251af56a from 'nuxt_plugin_ckeditor_251af56a' // Source: ..\\plugins\\ckeditor (mode: 'client')
import nuxt_plugin_swiper_3a1c5924 from 'nuxt_plugin_swiper_3a1c5924' // Source: ..\\plugins\\swiper (mode: 'client')
import nuxt_plugin_sticky_3a714642 from 'nuxt_plugin_sticky_3a714642' // Source: ..\\plugins\\sticky (mode: 'client')
import nuxt_plugin_stripe_3a68e97a from 'nuxt_plugin_stripe_3a68e97a' // Source: ..\\plugins\\stripe (mode: 'client')
import nuxt_plugin_noSSR_3401224a from 'nuxt_plugin_noSSR_3401224a' // Source: ..\\plugins\\noSSR (mode: 'client')
import nuxt_plugin_echo_6a7fda9b from 'nuxt_plugin_echo_6a7fda9b' // Source: ..\\plugins\\echo (mode: 'client')
import nuxt_plugin_plugin_7a3ab023 from 'nuxt_plugin_plugin_7a3ab023' // Source: .\\auth\\plugin.js (mode: 'all')
import nuxt_plugin_auth_7f7561ce from 'nuxt_plugin_auth_7f7561ce' // Source: ..\\plugins\\auth.js (mode: 'all')
import nuxt_plugin_echo_3cf1899f from 'nuxt_plugin_echo_3cf1899f' // Source: .\\echo.js (mode: 'client')

// Component: <ClientOnly>
Vue.component(ClientOnly.name, ClientOnly)

// TODO: Remove in Nuxt 3: <NoSsr>
Vue.component(NoSsr.name, {
  ...NoSsr,
  render (h, ctx) {
    if (process.client && !NoSsr._warned) {
      NoSsr._warned = true

      console.warn(`<no-ssr> has been deprecated and will be removed in Nuxt 3, please use <client-only> instead`)
    }
    return NoSsr.render(h, ctx)
  }
})

// Component: <NuxtChild>
Vue.component(NuxtChild.name, NuxtChild)
Vue.component('NChild', NuxtChild)

// Component NuxtLink is imported in server.js or client.js

// Component: <Nuxt>`
Vue.component(Nuxt.name, Nuxt)

Vue.use(Meta, {"keyName":"head","attribute":"data-n-head","ssrAttribute":"data-n-head-ssr","tagIDKeyName":"hid"})

const defaultTransition = {"name":"page","mode":"out-in","appear":false,"appearClass":"appear","appearActiveClass":"appear-active","appearToClass":"appear-to"}

async function createApp (ssrContext) {
  const router = await createRouter(ssrContext)

  const store = createStore(ssrContext)
  // Add this.$router into store actions/mutations
  store.$router = router

  // Fix SSR caveat https://github.com/nuxt/nuxt.js/issues/3757#issuecomment-414689141
  const registerModule = store.registerModule
  store.registerModule = (path, rawModule, options) => registerModule.call(store, path, rawModule, Object.assign({ preserveState: process.client }, options))

  // Create Root instance

  // here we inject the router and store to all child components,
  // making them available everywhere as `this.$router` and `this.$store`.
  const app = {
    store,
    router,
    nuxt: {
      defaultTransition,
      transitions: [ defaultTransition ],
      setTransitions (transitions) {
        if (!Array.isArray(transitions)) {
          transitions = [ transitions ]
        }
        transitions = transitions.map((transition) => {
          if (!transition) {
            transition = defaultTransition
          } else if (typeof transition === 'string') {
            transition = Object.assign({}, defaultTransition, { name: transition })
          } else {
            transition = Object.assign({}, defaultTransition, transition)
          }
          return transition
        })
        this.$options.nuxt.transitions = transitions
        return transitions
      },

      err: null,
      dateErr: null,
      error (err) {
        err = err || null
        app.context._errored = Boolean(err)
        err = err ? normalizeError(err) : null
        const nuxt = this.nuxt || this.$options.nuxt
        nuxt.dateErr = Date.now()
        nuxt.err = err
        // Used in src/server.js
        if (ssrContext) {
          ssrContext.nuxt.error = err
        }
        return err
      }
    },
    ...App
  }

  // Make app available into store via this.app
  store.app = app

  const next = ssrContext ? ssrContext.next : location => app.router.push(location)
  // Resolve route
  let route
  if (ssrContext) {
    route = router.resolve(ssrContext.url).route
  } else {
    const path = getLocation(router.options.base)
    route = router.resolve(path).route
  }

  // Set context to app.context
  await setContext(app, {
    store,
    route,
    next,
    error: app.nuxt.error.bind(app),
    payload: ssrContext ? ssrContext.payload : undefined,
    req: ssrContext ? ssrContext.req : undefined,
    res: ssrContext ? ssrContext.res : undefined,
    beforeRenderFns: ssrContext ? ssrContext.beforeRenderFns : undefined,
    ssrContext
  })

  const inject = function (key, value) {
    if (!key) {
      throw new Error('inject(key, value) has no key provided')
    }
    if (value === undefined) {
      throw new Error('inject(key, value) has no value provided')
    }

    key = '$' + key
    // Add into app
    app[key] = value

    // Add into store
    store[key] = app[key]

    // Check if plugin not already installed
    const installKey = '__nuxt_' + key + '_installed__'
    if (Vue[installKey]) {
      return
    }
    Vue[installKey] = true
    // Call Vue.use() to install the plugin into vm
    Vue.use(() => {
      if (!Vue.prototype.hasOwnProperty(key)) {
        Object.defineProperty(Vue.prototype, key, {
          get () {
            return this.$root.$options[key]
          }
        })
      }
    })
  }

  if (process.client) {
    // Replace store state before plugins execution
    if (window.__NUXT__ && window.__NUXT__.state) {
      store.replaceState(window.__NUXT__.state)
    }
  }

  // Plugin execution

  if (typeof nuxt_plugin_recaptcha_7934dbe3 === 'function') {
    await nuxt_plugin_recaptcha_7934dbe3(app.context, inject)
  }

  if (typeof nuxt_plugin_libplugin78fb7d41_aa8ce49e === 'function') {
    await nuxt_plugin_libplugin78fb7d41_aa8ce49e(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_toast_32bd17ba === 'function') {
    await nuxt_plugin_toast_32bd17ba(app.context, inject)
  }

  if (typeof nuxt_plugin_axios_1a712774 === 'function') {
    await nuxt_plugin_axios_1a712774(app.context, inject)
  }

  if (typeof nuxt_plugin_router_2fff5a8a === 'function') {
    await nuxt_plugin_router_2fff5a8a(app.context, inject)
  }

  if (typeof nuxt_plugin_user_6caa611e === 'function') {
    await nuxt_plugin_user_6caa611e(app.context, inject)
  }

  if (typeof nuxt_plugin_i18n_56ca5e75 === 'function') {
    await nuxt_plugin_i18n_56ca5e75(app.context, inject)
  }

  if (typeof nuxt_plugin_axios_fb9c9a02 === 'function') {
    await nuxt_plugin_axios_fb9c9a02(app.context, inject)
  }

  if (typeof nuxt_plugin_vuetify_0e1f10d7 === 'function') {
    await nuxt_plugin_vuetify_0e1f10d7(app.context, inject)
  }

  if (typeof nuxt_plugin_helpers_0e9b0ece === 'function') {
    await nuxt_plugin_helpers_0e9b0ece(app.context, inject)
  }

  if (typeof nuxt_plugin_qrcode_420a23b6 === 'function') {
    await nuxt_plugin_qrcode_420a23b6(app.context, inject)
  }

  if (typeof nuxt_plugin_gates_fb0818de === 'function') {
    await nuxt_plugin_gates_fb0818de(app.context, inject)
  }

  if (typeof nuxt_plugin_bootstrap_0f900877 === 'function') {
    await nuxt_plugin_bootstrap_0f900877(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_amCharts_4dac9117 === 'function') {
    await nuxt_plugin_amCharts_4dac9117(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_ckeditor_251af56a === 'function') {
    await nuxt_plugin_ckeditor_251af56a(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_swiper_3a1c5924 === 'function') {
    await nuxt_plugin_swiper_3a1c5924(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_sticky_3a714642 === 'function') {
    await nuxt_plugin_sticky_3a714642(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_stripe_3a68e97a === 'function') {
    await nuxt_plugin_stripe_3a68e97a(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_noSSR_3401224a === 'function') {
    await nuxt_plugin_noSSR_3401224a(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_echo_6a7fda9b === 'function') {
    await nuxt_plugin_echo_6a7fda9b(app.context, inject)
  }

  if (typeof nuxt_plugin_plugin_7a3ab023 === 'function') {
    await nuxt_plugin_plugin_7a3ab023(app.context, inject)
  }

  if (typeof nuxt_plugin_auth_7f7561ce === 'function') {
    await nuxt_plugin_auth_7f7561ce(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_echo_3cf1899f === 'function') {
    await nuxt_plugin_echo_3cf1899f(app.context, inject)
  }

  // If server-side, wait for async component to be resolved first
  if (process.server && ssrContext && ssrContext.url) {
    await new Promise((resolve, reject) => {
      router.push(ssrContext.url, resolve, () => {
        // navigated to a different route in router guard
        const unregister = router.afterEach(async (to, from, next) => {
          ssrContext.url = to.fullPath
          app.context.route = await getRouteData(to)
          app.context.params = to.params || {}
          app.context.query = to.query || {}
          unregister()
          resolve()
        })
      })
    })
  }

  return {
    store,
    app,
    router
  }
}

export { createApp, NuxtError }
