import Echo from 'laravel-echo'

function getHeaders ({ app }) {
  const headers = {}

  return headers
}

export default (ctx, inject) => {
  const options = {"authModule":false,"connectOnLogin":true,"disconnectOnLogout":true}
  options.auth = options.auth || {}
  options.auth.headers = {
    ...options.auth.headers,
    ...getHeaders(ctx)
  }

  const echo = new Echo(options)

  ctx.$echo = echo
  inject('echo', echo)
}
