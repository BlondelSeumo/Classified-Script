import Echo from "laravel-echo"

const Cookie = process.client ? require('js-cookie') : undefined

export default ({ app, store, redirect }) => {

    const token = Cookie.get('everest._token.local')

    window.Pusher = require('pusher-js');

    window.Echo = new Echo({
        authEndpoint: `${process.env.apiUrl}/broadcasting/auth`,
        auth: {
            headers: {
                'Accept': 'application/json',
                Authorization: token
            },
        },
        broadcaster: 'pusher',
        key: '0bc3603efa88f77b85aa',
        cluster: 'us2',
        encrypted: true,
        devMode: true
    });
}