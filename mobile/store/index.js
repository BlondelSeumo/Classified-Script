import Cookies from 'js-cookie'
import { cookieFromRequest } from '~/utils'

export const actions = {
  	async nuxtServerInit ({ commit }, { req }) {

		const token = cookieFromRequest(req, 'everest._token.local')
		if (token) {
			await this.$auth.fetchUser()
		}

    	const locale  = cookieFromRequest(req, 'locale')
		if (locale) {
			commit('lang/SET_LOCALE', { locale })
		}

		const response = await this.$axios.post('ajax/fetch/settings')

		commit('settings/SET_SETTINGS', response.data)
  	},

  	async nuxtClientInit ({ commit }) {
		  
		const token = Cookies.get('everest._token.local')
		if (token) {
			await this.$auth.fetchUser()
		}

		const locale = Cookies.get('locale')
		if (locale) {
			commit('lang/SET_LOCALE', { locale })
		}
  	}
}