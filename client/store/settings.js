export const state = () => ({
	logo: {
		white: null,
		transparent: null
	},
	favicon: null,
	title: null,
	description: null,
	separator: null,
	home: {
		wallpaper: null,
		text: null
	},
	footer: {
        firstColumn: null,
        secondColumn: null,
        thirdColumn: null,
        fourthColumn: null,
        config: null
    },
    rtl: false

})

// mutations
export const mutations = {
  	SET_SETTINGS (state, data) {
		state.logo.white          = data.whiteLogo
		state.logo.transparent    = data.transLogo
		state.favicon             = data.favicon
		state.title               = data.title
		state.separator           = data.separator
		state.description         = data.description
		state.rtl                 = data.rtl
		state.home.wallpaper      = data.home.wallpaper
		state.home.text           = data.home.text
		state.footer.firstColumn  = data.firstColumn
		state.footer.secondColumn = data.secondColumn
		state.footer.thirdColumn  = data.thirdColumn
		state.footer.fourthColumn = data.fourthColumn
		state.footer.config       = data.config
  	},

  	SET_RTL (state, data) {
  		state.rtl = true
  	}
}

// actions
export const actions = {
  	setRTL ({ commit }, { data }) {
    	commit('SET_RTL', { data })
  	},

  	setNotifications ({ commit }, { data }) {
    	commit('SET_NOTIFICATIONS', { data })
  	}
}