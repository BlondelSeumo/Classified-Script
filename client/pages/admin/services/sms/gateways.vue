<template>
	<v-app id="inspire">
		<v-container grid-list-xl fluid class="pa-4">
				<v-layout wrap>

					<v-flex xs2 v-for="gateway in gateways" :key="gateway.name">
						<v-card class="m-card px-2 pt-3 pb-2 text-center cursor-pointer" flat @click="open(gateway.url)">
							<v-avatar
					          	size="80px"
					          	color="transparent"
					        	>
					          	<img :src="$homeApi('public/images/services/sms/' + gateway.icon)" :alt="gateway.name">
					        </v-avatar>
					        <p class="text-uppercase font-weight-black caption mt-3">{{ gateway.name }}</p>
						</v-card>
					</v-flex>

				</v-layout>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		asyncData({ app, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'sms', 'services')) {
				redirect('/administrator')
			}
		},

		data: function() {
			return {
				gateways: [
					{name: this.$t('t_nexmo'), icon: "nexmo.png", url: "nexmo"}
				]
			}
		},

		head() {
			return {
				title: this.$t('t_sms'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},

		methods: {
			open: function(gateway) {
				this.$router.push('/administrator/services/sms/' + gateway)
			}
		}
	}
</script>