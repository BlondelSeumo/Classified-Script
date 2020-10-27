<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<v-card class="m-card" flat>
				<v-card-title primary-title class="pa-4">
		          <div>
		            <div class="title">{{ $t('t_watermark_settings') }}</div>
		            <span class="card-description">{{ $t('t_watermark_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Enable Watermark -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enabled ? 'has-danger' : '']">
								<v-switch v-model="form.enabled" :true-value="1" :false-value="0" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_watermark') }}</span>
							</label>
							<small class="red--text" v-if="errors.enabled">{{ errors.enabled[0] }}</small>
						</v-flex>

						<!-- Watermark -->
						<v-flex xs12>
							<upload-btn uniqueId @file-update="watermark" block accept="image/*" :title="$t('t_upload_watermark')" :color="$vuetify.theme.dark ? 'grey darken-4' : 'grey lighten-3'" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Watermark position -->
						<v-flex xs12>
							<v-select
								v-model="form.position"
								:items="positions"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_watermark_position')"
								:placeholder="$t('t_choose_watermark_position')"
								:rules="errors.position"
								:error="errors.position ? true : false"
								dense
								></v-select>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
						</v-flex>

					</v-layout>

				</v-container>

			</v-card>
		</v-container>

	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'watermark', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/watermark')
			return {
				form: {
					position: response.data.position,
					enabled : response.data.isActive
				}
			}
		},

		data: function() {
			return {
				positions: [
		          	{ id: 'top-left', name: this.$t('t_top_left')},
		          	{ id: 'top', name: this.$t('t_top')},
		          	{ id: 'top-right', name: this.$t('t_top_right')},
		          	{ id: 'left', name: this.$t('t_left')},
		          	{ id: 'right', name: this.$t('t_right')},
		          	{ id: 'center', name: this.$t('t_center')},
		          	{ id: 'bottom-left', name: this.$t('t_bottom_left')},
		          	{ id: 'bottom', name: this.$t('t_bottom')},
		          	{ id: 'bottom-right', name: this.$t('t_bottom_right')}
		        ],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_watermark_settings'),
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
			update: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'watermark', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				let data     = new FormData()
				data.append('watermark', this.form.watermark)
				data.append('position', this.form.position)
				data.append('enabled', this.form.enabled)
				this.$axios
				.post('administrator/settings/watermark',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_watermark_settings_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
				.catch(error => {
					this.errors   = error.response.data.errors
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			watermark: function(file) {
				this.form.watermark = file
			}
		}
	}
</script>