<template>
	<v-app id="inspire">
		<!-- Loading -->
		<v-overlay v-model="loading.popup" opacity="0.8">
			<v-progress-circular indeterminate size="120" width="4" color="white">
				{{ $t('t_redirecting') }}
			</v-progress-circular>
		</v-overlay>
	</v-app>
</template>

<script>
	export default {
		async asyncData({ $axios, params }) {
			let response = await $axios.post(`a/${params.id}`)
			return {
				listing: response.data.listing,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
				}
			}
		},

		data () {
			return {
				loading: true
			}
		},

		head () {
			return {
	  			title: this.$t('t_redirecting'),
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		created () {
			this.$router.push(`/listing/${this.listing}`)
		}
	}
</script>