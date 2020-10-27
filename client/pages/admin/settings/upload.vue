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
		            <div class="title">{{ $t('t_uploading_settings') }}</div>
		            <span class="card-description">{{ $t('t_upload_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Max Single Image Size -->
						<v-flex xs12>
							<v-text-field
								v-model="form.max_image_size"
								color="grey lighten-1"
								:label="$t('t_max_single_image_size')"
								:placeholder="$t('t_enter_max_single_image_size')"
								:rules="errors.max_image_size"
								:error="errors.max_image_size ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Max total images size -->
						<v-flex xs12>
							<v-text-field
								v-model="form.max_total_images_size"
								color="grey lighten-1"
								:label="$t('t_max_total_image_size')"
								:placeholder="$t('t_enter_max_total_image_size')"
								:rules="errors.max_total_images_size"
								:error="errors.max_total_images_size ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Enable Image compression -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.compression ? 'has-danger' : '']">
								<v-switch v-model="form.compression" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_compressor') }}</span>
							</label>
							<small class="red--text" v-if="errors.compression">{{ errors.compression[0] }}</small>
						</v-flex>

						<!-- Default storage -->
						<v-flex xs12>
							<v-select
								v-model="form.storage"
								:items="clouds"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_storage')"
								:placeholder="$t('t_choose_default_storage')"
								:rules="errors.storage"
								:error="errors.storage ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'upload', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/upload')
			return {
				form: {
					max_image_size       : response.data.singleImageSize,
					max_total_images_size: response.data.multipleImageSize,
					compression          : response.data.isCompression,
					storage              : response.data.storage,
				}
			}
		},

		data: function() {
			return {
				clouds: [
		          	//{ id: 'amazon', name: 'Amazon S3 Cloud' },
		          	{ id: 'local', name: this.$t('t_local_storage') }
		        ],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_uploading_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'upload', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/upload', {
					max_image_size: this.form.max_image_size,
					max_total_images_size: this.form.max_total_images_size,
					compression: this.form.compression,
					storage: this.form.storage
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_uploading_settings_updated'), {
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
			}
		}
	}
</script>