import Auth from './auth'

import './middleware'

// Active schemes
import scheme_3e2123be from './schemes/local.js'
import scheme_6ce4fcca from './schemes/oauth2.js'

export default function (ctx, inject) {
  // Options
  const options = {"resetOnError":true,"scopeKey":"scope","rewriteRedirects":true,"fullPathRedirect":false,"watchLoggedIn":true,"redirect":{"login":"/auth/login","logout":"/","home":"/","callback":"/auth/login"},"vuex":{"namespace":"auth"},"cookie":{"prefix":"everest.","options":{"path":"/","maxAge":604800}},"localStorage":{"prefix":"auth."},"token":{"prefix":"_token."},"refresh_token":{"prefix":"_refresh_token."},"defaultStrategy":"local"}

  // Create a new Auth instance
  const $auth = new Auth(ctx, options)

  // Register strategies
  // local
  $auth.registerStrategy('local', new scheme_3e2123be($auth, {"endpoints":{"login":{"url":"/auth/login","method":"post","propertyName":"access_token"},"logout":{"url":"/auth/logout","method":"post"},"user":{"url":"/auth/user","method":"get","propertyName":false}},"tokenRequired":true,"tokenType":"bearer","_name":"local"}))

  // facebook
  $auth.registerStrategy('facebook', new scheme_6ce4fcca($auth, {"client_id":"","userinfo_endpoint":"https://graph.facebook.com/v2.12/me?fields=about,name,picture{url},email","redirect_uri":"http://m.domain.com/auth/facebook/callback","token_key":"access_token","response_type":"token","scope":["public_profile","email"],"_name":"facebook","authorization_endpoint":"https://facebook.com/v2.12/dialog/oauth"}))

  // google
  $auth.registerStrategy('google', new scheme_6ce4fcca($auth, {"client_id":"","redirect_uri":"http://m.domain.com/auth/google/callback","_name":"google","authorization_endpoint":"https://accounts.google.com/o/oauth2/auth","userinfo_endpoint":"https://www.googleapis.com/oauth2/v3/userinfo","scope":["openid","profile","email"]}))

  // Inject it to nuxt context as $auth
  inject('auth', $auth)
  ctx.$auth = $auth

  // Initialize auth
  return $auth.init().catch(error => {
    if (process.client) {
      console.error('[ERROR] [AUTH]', error)
    }
  })
}
