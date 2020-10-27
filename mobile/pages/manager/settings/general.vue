<template>
    <v-app id="inspire">
		<v-container>
			<v-card class="m-card" flat>
				<v-card-title primary-title class="pa-4">
		          <div>
		            <div class="title">{{ $t('t_general_settings') }}</div>
		            <span class="card-description">{{ $t('t_change_dashboard_settings') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					
				</v-container>

			</v-card>
		</v-container>
	</v-app>
</template>

<script>
    export default {
        layout: 'default/manager',

        middleware: 'auth',
        
        head() {
			return {
				title: this.$t('t_general_settings'),
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

