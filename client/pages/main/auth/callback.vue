<template>
	<v-app id="inspire">
		<v-dialog v-model="loading" persistent width="300">
            <v-card color="black" dark>
                <v-card-text>
                    Please stand by
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                        ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
	</v-app>
</template>

<script>
	export default {
		data() {
			return {
				loading: true
			}
		},

		methods: {
			async register(provider, token, path = null) {
				let response = this.$axios.post(`auth/${provider}`, {token: token})
								   .then((response => {
								   		this.$auth.setUserToken(response.data)
								   }))
			},

			async google (path) {
				let response = await this.$axios.post(path.replace('/callback', ''))
									     .then(response => {
									     	this.$auth.setUserToken(response.data)
									     })
			}
		},

		mounted() {
			switch (this.$route.params.provider) {
			  	case 'facebook':
			    	var token = window.location.href.split("access_token=")[1];
					this.register(this.$route.params.provider, token.split("&")[0])
			    break;
			  	case 'twitter':
			  		this.register(this.$route.params.provider, this.$route.query.oauth_token, this.$route.query.oauth_verifier)
			    break;
			  	case 'google':
			  		this.google(this.$route.fullPath)
			    break;
			  	default:
			}
		}
	}
</script>