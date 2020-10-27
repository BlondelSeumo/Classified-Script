export const getters = {
    check (state) {
        return state.loggedIn;
    },

    user (state) {
        return state.user
    }
};

export const state = () => ({
    busy: false,
    loggedIn: false,
    strategy: "local",
    user: {} 
})