<template>
	<v-app id="inspire">
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>
	</v-app>
</template>

<script>
	export default {
		data() {
			return {
				loading: true
			}
		},

		head() {
			return {
				title: 'Account activation',
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$store.state.settings.title}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},

		created() {
			this.$axios
			.post('auth/activation', { token: this.$route.query.token })
			.then(response => {
				if (response.data === 'expired') {
					this.$toasted.error("Oops! Activation code is expired. Please try again.", {
	                    icon: 'error',
	                    action: null
	                })
	                this.$router.push('/auth/activation/resend')
				}else if (response.data === 'activated') {
					this.$toasted.success("Thank you! Your account has been successfully activated.", {
	                    icon: 'done_all',
	                    action: null
	                })
	                this.$router.push('/auth/login')
				}
			})
			.catch(error => {
				this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
                    icon: 'error',
                    action: null
                })
                this.$router.push('/auth/login')
			})
		}
	}
</script>