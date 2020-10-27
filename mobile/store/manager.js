export const state = () => ({
    shops_left: null,
    deals_left: null,
})

// mutations
export const mutations = {
  	SET_SHOPS_LEFT (state, data) {
		state.shops_left = data
    },

    SET_DEALS_LEFT (state, data) {
		state.deals_left = data
    },
}

// actions
export const actions = {
    setShopsLeft ({ commit }, number) {
        commit('SET_SHOPS_LEFT', number)
    },
    setDealsLeft ({ commit }, number) {
        commit('SET_DEALS_LEFT', number)
    }
  }