import Vue from 'vue'

import {
  getMatchedComponentsInstances,
  promisify,
  globalHandleError
} from './utils'

import NuxtLoading from './components/nuxt-loading.vue'

import '..\\node_modules\\swiper\\dist\\css\\swiper.css'

import '..\\assets\\sass\\app.scss'

import _553d3dcb from '..\\layouts\\default\\admin.vue'
import _c0a6e886 from '..\\layouts\\default\\main.vue'
import _709b9b29 from '..\\layouts\\default\\manager.vue'
import _5ce04181 from '..\\layouts\\default\\moderator.vue'
import _6f6c098b from './layouts/default.vue'

const layouts = { "_default/admin": _553d3dcb,"_default/main": _c0a6e886,"_default/manager": _709b9b29,"_default/moderator": _5ce04181,"_default": _6f6c098b }

export default {
  head: {"title":"EVEREST","titleTemplate":"%s - EVEREST","meta":[{"charset":"utf-8"},{"name":"viewport","content":"width=device-width, user-scalable=no"}],"link":[{"rel":"stylesheet","href":"https:\u002F\u002Ffonts.googleapis.com\u002Fcss?family=Roboto:100,300,400,500,700,900|Hind:400,500,600,700|Material+Icons"},{"rel":"stylesheet","href":"https:\u002F\u002Ffonts.googleapis.com\u002Fcss?family=Open+Sans:300,400,600,700,800"},{"rel":"stylesheet","href":"https:\u002F\u002Ffonts.googleapis.com\u002Fcss?family=Poppins:200,300,400,500,600,700,800,900"},{"rel":"stylesheet","href":"https:\u002F\u002Ffonts.googleapis.com\u002Fcss?family=Oswald:300,400,500"},{"rel":"stylesheet","href":"https:\u002F\u002Ffonts.googleapis.com\u002Fcss?family=Source+Sans+Pro:300,400,600,700"},{"rel":"stylesheet","type":"text\u002Fcss","href":"\u002F\u002Ffonts.googleapis.com\u002Fearlyaccess\u002Fnotokufiarabic.css"},{"rel":"dns-prefetch","href":"\u002F\u002Fajax.googleapis.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fapis.google.com"},{"rel":"dns-prefetch","href":"\u002F\u002Ffonts.googleapis.com"},{"rel":"dns-prefetch","href":"\u002F\u002Ffonts.gstatic.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fwww.google-analytics.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fwww.googletagmanager.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fwww.googletagservices.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fadservice.google.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fpagead2.googlesyndication.com"},{"rel":"dns-prefetch","href":"\u002F\u002Ftpc.googlesyndication.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fconnect.facebook.net"},{"rel":"dns-prefetch","href":"\u002F\u002Fplatform.twitter.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fplatform.linkedin.com"},{"rel":"dns-prefetch","href":"\u002F\u002Fad.doubleclick.net"},{"rel":"dns-prefetch","href":"\u002F\u002Fgoogleads.g.doubleclick.net"},{"rel":"dns-prefetch","href":"\u002F\u002Fstats.g.doubleclick.net"},{"rel":"dns-prefetch","href":"\u002F\u002Fcm.g.doubleclick.net"}],"style":[],"script":[]},

  render (h, props) {
    const loadingEl = h('NuxtLoading', { ref: 'loading' })

    const layoutEl = h(this.layout || 'nuxt')
    const templateEl = h('div', {
      domProps: {
        id: '__layout'
      },
      key: this.layoutName
    }, [ layoutEl ])

    const transitionEl = h('transition', {
      props: {
        name: 'layout',
        mode: 'out-in'
      },
      on: {
        beforeEnter (el) {
          // Ensure to trigger scroll event after calling scrollBehavior
          window.$nuxt.$nextTick(() => {
            window.$nuxt.$emit('triggerScroll')
          })
        }
      }
    }, [ templateEl ])

    return h('div', {
      domProps: {
        id: '__nuxt'
      }
    }, [
      loadingEl,

      transitionEl
    ])
  },

  data: () => ({
    isOnline: true,

    layout: null,
    layoutName: ''
  }),

  beforeCreate () {
    Vue.util.defineReactive(this, 'nuxt', this.$options.nuxt)
  },
  created () {
    // Add this.$nuxt in child instances
    Vue.prototype.$nuxt = this
    // add to window so we can listen when ready
    if (process.client) {
      window.$nuxt = this

      this.refreshOnlineStatus()
      // Setup the listeners
      window.addEventListener('online', this.refreshOnlineStatus)
      window.addEventListener('offline', this.refreshOnlineStatus)
    }
    // Add $nuxt.error()
    this.error = this.nuxt.error
    // Add $nuxt.context
    this.context = this.$options.context
  },

  mounted () {
    this.$loading = this.$refs.loading
  },
  watch: {
    'nuxt.err': 'errorChanged'
  },

  computed: {
    isOffline () {
      return !this.isOnline
    }
  },

  methods: {
    refreshOnlineStatus () {
      if (process.client) {
        if (typeof window.navigator.onLine === 'undefined') {
          // If the browser doesn't support connection status reports
          // assume that we are online because most apps' only react
          // when they now that the connection has been interrupted
          this.isOnline = true
        } else {
          this.isOnline = window.navigator.onLine
        }
      }
    },

    async refresh () {
      const pages = getMatchedComponentsInstances(this.$route)

      if (!pages.length) {
        return
      }
      this.$loading.start()

      const promises = pages.map((page) => {
        const p = []

        if (page.$options.fetch) {
          p.push(promisify(page.$options.fetch, this.context))
        }

        if (page.$options.asyncData) {
          p.push(
            promisify(page.$options.asyncData, this.context)
              .then((newData) => {
                for (const key in newData) {
                  Vue.set(page.$data, key, newData[key])
                }
              })
          )
        }

        return Promise.all(p)
      })
      try {
        await Promise.all(promises)
      } catch (error) {
        this.$loading.fail()
        globalHandleError(error)
        this.error(error)
      }
      this.$loading.finish()
    },

    errorChanged () {
      if (this.nuxt.err && this.$loading) {
        if (this.$loading.fail) {
          this.$loading.fail()
        }
        if (this.$loading.finish) {
          this.$loading.finish()
        }
      }
    },

    setLayout (layout) {
      if (!layout || !layouts['_' + layout]) {
        layout = 'default'
      }
      this.layoutName = layout
      this.layout = layouts['_' + layout]
      return this.layout
    },
    loadLayout (layout) {
      if (!layout || !layouts['_' + layout]) {
        layout = 'default'
      }
      return Promise.resolve(layouts['_' + layout])
    }
  },

  components: {
    NuxtLoading
  }
}
