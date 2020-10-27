const middleware = {}

middleware['administrator'] = require('..\\middleware\\administrator.js')
middleware['administrator'] = middleware['administrator'].default || middleware['administrator']

middleware['auth'] = require('..\\middleware\\auth.js')
middleware['auth'] = middleware['auth'].default || middleware['auth']

middleware['check-auth'] = require('..\\middleware\\check-auth.js')
middleware['check-auth'] = middleware['check-auth'].default || middleware['check-auth']

middleware['guest'] = require('..\\middleware\\guest.js')
middleware['guest'] = middleware['guest'].default || middleware['guest']

middleware['locale'] = require('..\\middleware\\locale.js')
middleware['locale'] = middleware['locale'].default || middleware['locale']

middleware['manager'] = require('..\\middleware\\manager.js')
middleware['manager'] = middleware['manager'].default || middleware['manager']

middleware['mobile'] = require('..\\middleware\\mobile.js')
middleware['mobile'] = middleware['mobile'].default || middleware['mobile']

middleware['moderator'] = require('..\\middleware\\moderator.js')
middleware['moderator'] = middleware['moderator'].default || middleware['moderator']

export default middleware
