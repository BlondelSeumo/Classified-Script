export const state = () => ({
    notifications: {
    	deals: null,
    	comments: null,
    	reports: null,
    	shops: null,
    	users: null
    }

})

// mutations
export const mutations = {
  	SET_NOTIFICATIONS (state, data) {
		state.notifications.deals    = data.deals
		state.notifications.reports  = data.reports
		state.notifications.comments = data.comments
		state.notifications.shops    = data.shops
		state.notifications.users    = data.users
  	}
}