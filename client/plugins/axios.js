import Vue from 'vue'

export default function ({ app, $axios, redirect }) {

    // Set current locale in header request
    $axios.setHeader('locale', app.i18n.locale);
    $axios.setHeader("Set-Cookie", "HttpOnly;Secure;SameSite=Strict");
    $axios.setHeader("Access-Control-Allow-Origin", "*");

  	$axios.onRequest(config => {
  	})

  	$axios.onError(error => {

      // Check for response status
      if (error.response.status) {
        let code = error.response.status

        switch (code) {

          // Not found
          case 404: 
            redirect('/404')
            break;

          // Not found
          case 400:
            //redirect('/')
            break;
          
          // Unauthorized
          case 401: 
            redirect('/auth/login')
            break;

          // Forbidden
          case 403: 
            redirect('/')
            break;

          // Server error
          case 500:
            redirect('/500')
            break;

          // Down
          case 503:
            redirect('/maintenance')
            break;
        
          default:
            break;
        }
      }
      
  	})
}