// utils/getRoutes.js
const axios = require('axios');
//const appRoutes = require('../routes.json');

module.exports = async function getAppRoutes() {

    // Fetch blogPosts as object with languages as attributes and slugs as their values
    const sitemap = await axios.post(`${process.env.API_URL}/sitemap`)

    const routes  = []

    // Deals
    sitemap.data.deals.forEach(function(value, key) {
        routes.push(`/listing/${value.unique_slug}`)
    })

    // Users
    sitemap.data.shops.forEach(function(value, key) {
        routes.push(`/shop/${value.store}`)
    })

    // Return all available routes
    return routes;
    
};