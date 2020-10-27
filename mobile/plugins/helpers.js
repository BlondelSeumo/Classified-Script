import Vue from 'vue'
import moment from 'moment'
import getSymbolFromCurrency from 'currency-symbol-map'

export default ({ app, store }) => {

    moment.locale(store.state.lang.locale)

    // Disable production tips
    Vue.config.productionTip = false

    /**
     * Generate links
     * @param  {String} to [description]
     * @return {[type]}    [description]
     */
    Vue.prototype.$home = (to = null) => {
    	let index = process.env.clientUrl
        if (to === null) {
            return index
        }else{
            return index + "/" + to
        }
    }

    /**
     * Generate links
     * @param  {String} to [description]
     * @return {[type]}    [description]
     */
    Vue.prototype.$homeApi = (to = null) => {
    	let index = process.env.serverUrl
        if (to === null) {
            return index
        }else{
            return index + "/" + to
        }
    }

    /**
     * Check if current user admin
     * @param  {[type]} to [description]
     * @return {[type]}    [description]
     */
    Vue.prototype.$isAdmin = () => {
        if (app.$auth.loggedIn && (app.$auth.user.role.group === 'administrator' || app.$auth.user.role.group === 'owner')) {
            return true
        }else{
            return false
        }
    }

    /**
     * Check if current user moderator
     * @param  {[type]} to [description]
     * @return {[type]}    [description]
     */
    Vue.prototype.$isModerator = () => {
        if (app.$auth.loggedIn && (app.$auth.user.role.group === 'moderator')) {
            return true
        }else{
            return false
        }
    }

    /**
     * Check if current user manager
     * @param  {[type]} to [description]
     * @return {[type]}    [description]
     */
    Vue.prototype.$isManager = () => {
        if (app.$auth.loggedIn && (app.$auth.user.subscription !== null && !app.$auth.user.subscription.isExpired)) {
            return true
        }else{
            return false
        }
    }


    /**
     * Get greeting time
     * @return {[type]} [description]
     */
    Vue.prototype.$getGreetingTime = (username) => {
        let currentTime = moment()
        if (!currentTime || !currentTime.isValid()) { return; }

        const splitAfternoon = 12; // 24hr time to split the afternoon
        const splitEvening = 17; // 24hr time to split the evening
        const currentHour = parseFloat(currentTime.format('HH'));

        if (currentHour >= splitAfternoon && currentHour <= splitEvening) {
            // Between 12 PM and 5PM
            return app.i18n.t('t_good_afternoon', {username: username});
        } else if (currentHour >= splitEvening) {
            // Between 5PM and Midnight
            return app.i18n.t('t_good_evening', {username: username});
        }
        // Between dawn and noon
        return app.i18n.t('t_good_morning', {username: username});
    }

    /**
     * Get currency symbol
     * @param  {[type]} currency [description]
     * @param  {[type]} locale   [description]
     * @return {[type]}          [description]
     */
    Vue.prototype.$currencySymbol = (currency, locale) => {
        return getSymbolFromCurrency(currency)
    }

    /**
     * Get price format
     * @param  {[type]} price    [description]
     * @param  {[type]} currency [description]
     * @param  {[type]} locale   [description]
     * @return {[type]}          [description]
     */
    Vue.prototype.$getCurrency = (price, currency, locale) => {
        try {
            return new Intl.NumberFormat(locale, { style: 'currency', currency: currency }).format(price) 
        } catch (e) {
            console.log(e)
        }
    }


    /**
     * Get time ago from timestamp
     * @param  {[type]} timestamp [description]
     * @return {[type]}           [description]
     */
    Vue.prototype.$ago = (timestamp) => {
        moment.locale(store.state.lang.locale)
        return moment.utc(timestamp).fromNow();
    }


    /**
     * Get date string
     * @param  {[type]} timestamp [description]
     * @return {[type]}           [description]
     */
    Vue.prototype.$dateString = (timestamp) => {
        moment.locale(store.state.lang.locale)
        let expiry = moment(timestamp)
        let now    = moment().utc()
        let days   =  expiry.diff(now,'days')
        return new Intl.NumberFormat('en-US').format(days) + " " + app.i18n.t('t_days')
    }


    /**
     * Get year month day
     * @param  {[type]} timestamp [description]
     * @return {[type]}           [description]
     */
    Vue.prototype.$dateYMD = (timestamp) => {
        moment.locale(store.state.lang.locale)
        return moment.utc(timestamp).format('MMMM Do YYYY, h:mm a');
    }


    /**
     * Get date since
     * @param  {[type]} timestamp [description]
     * @return {[type]}           [description]
     */
    Vue.prototype.$dateSince = (timestamp) => {
        moment.locale(store.state.lang.locale)
        return moment.utc(timestamp).format('MMMM YYYY');
    }


    /**
     * Return chat time
     * @param  {[type]} timestamp [description]
     * @return {[type]}           [description]
     */
    Vue.prototype.$chatTime = (timestamp) => {
        moment.locale(store.state.lang.locale)
        return moment.utc(timestamp).format('hh:mm A');
    }


    /**
     * Number format
     * @param  {[type]} timestamp [description]
     * @return {[type]}           [description]
     */
    Vue.prototype.$numberFormat = (number) => {
        return new Intl.NumberFormat('en-US', { maximumSignificantDigits: 3 }).format(number)
    }

    Vue.prototype.$striphtml = (value) => {
        if (typeof document !== 'undefined') {
            var div       = document.createElement("div");
            div.innerHTML = value;
            var text      = div.textContent || div.innerText || "";
            return text;
        }else{
            return value
        }
    }

    Vue.prototype.$getYoutubeId = (url) => {
        let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        let match = url.match(regExp);
        return (match&&match[7].length==11)? match[7] : false;
    }

    Vue.prototype.$description = (description, length = 120) => {
        if (description) {
            let text   = description.substring(0, length)
            let result = text.replace(/<\/?[^>]+(>|$)/g, "");
            return result + "..."
        }else{
            return null
        }
    }
    
}