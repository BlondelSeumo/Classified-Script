<template>
	<v-app id="inspire">
		<v-container grid-list-xl fluid class="pa-5 text-center">
				<h2 class="font-weight-black text-uppercase">In version 2.1 you will be able to sell advertising spaces</h2>
				<h2 class="font-weight-black pt-3 text-uppercase">This feature will include ads statistics like Google Adsense platform</h2>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		head() {
			return {
				title: 'Coming soon',
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},
	}
</script>