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
		            	<div class="title">{{ $t('t_create_moderator_role') }}</div>
			            <span class="card-description">{{ $t('t_create_moderator_role_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Role name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_role_name')"
								:placeholder="$t('t_enter_role_name')"
								counter="60"
								maxlength="60"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Access Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_classifieds ? 'has-danger' : '']">
								<v-switch v-model="form.access_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_access_deals') }}</span>
								<small class="form-text" v-if="errors.access_classifieds">{{ errors.access_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Classifieds -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.approve_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.approve_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_approve_deals') }}</span>
								<small class="form-text" v-if="errors.approve_classifieds">{{ errors.approve_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Classifieds -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.edit_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.edit_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_edit_deals') }}</span>
								<small class="form-text" v-if="errors.edit_classifieds">{{ errors.edit_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Classifieds -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.delete_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.delete_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_delete_deals') }}</span>
								<small class="form-text" v-if="errors.delete_classifieds">{{ errors.delete_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- See Classifieds Statistics -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.classifieds_stats ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.classifieds_stats" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_deals_stats') }}</span>
								<small class="form-text" v-if="errors.classifieds_stats">{{ errors.classifieds_stats[0] }}</small>
							</label>
						</v-flex>

						<!-- Access shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_stores ? 'has-danger' : '']">
								<v-switch v-model="form.access_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_access_shops') }}</span>
								<small class="form-text" v-if="errors.access_stores">{{ errors.access_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve shops -->
						<v-flex xs4>
							<label class="form-group has-float-label" :class="[errors.approve_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_stores v-model="form.approve_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_approve_shops') }}</span>
								<small class="form-text" v-if="errors.approve_stores">{{ errors.approve_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit shops -->
						<v-flex xs4>
							<label class="form-group has-float-label" :class="[errors.edit_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_stores v-model="form.edit_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_edit_shops') }}</span>
								<small class="form-text" v-if="errors.edit_stores">{{ errors.edit_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete shops -->
						<v-flex xs4>
							<label class="form-group has-float-label" :class="[errors.delete_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_stores v-model="form.delete_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_delete_shops') }}</span>
								<small class="form-text" v-if="errors.delete_stores">{{ errors.delete_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_comments ? 'has-danger' : '']">
								<v-switch v-model="form.access_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_access_comments') }}</span>
								<small class="form-text" v-if="errors.access_comments">{{ errors.access_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Comments -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.approve_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.approve_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_approve_comments') }}</span>
								<small class="form-text" v-if="errors.approve_comments">{{ errors.approve_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Comments -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.edit_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.edit_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_edit_comments') }}</span>
								<small class="form-text" v-if="errors.edit_comments">{{ errors.edit_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Comments -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.delete_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.delete_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_delete_comments') }}</span>
								<small class="form-text" v-if="errors.delete_comments">{{ errors.delete_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- See reported comments -->
						<v-flex xs3>
							<label class="form-group has-float-label" :class="[errors.reported_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.reported_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_reported_comments') }}</span>
								<small class="form-text" v-if="errors.reported_comments">{{ errors.reported_comments[0] }}</small>
							</label>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="create" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_create') }}</v-btn>
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

		asyncData({ app, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'create', 'roles')) {
				redirect('/administrator')
			}
		},

		data: function() {
			return {
				form: {
					name: '',
					access_classifieds: false,
					approve_classifieds: false,
					edit_classifieds: false,
					delete_classifieds: false,
					classifieds_stats: false,
					access_stores: false,
					approve_stores: false,
					edit_stores: false,
					delete_stores: false,
					access_comments: false,
					approve_comments: false,
					edit_comments: false,
					delete_comments: false,
					reported_comments: false
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_moderator_role'),
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
			create: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'create', 'roles')) {
					this.$router.push('/administrator')	
					return
				}
				
				this.loading = true
				
				this.$axios
				.post('administrator/roles/options/create/moderator', {
					name: this.form.name,
					access_classifieds: this.form.access_classifieds,
					approve_classifieds: this.form.approve_classifieds,
					edit_classifieds: this.form.edit_classifieds,
					delete_classifieds: this.form.delete_classifieds,
					classifieds_stats: this.form.classifieds_stats,
					access_stores: this.form.access_stores,
					approve_stores: this.form.approve_stores,
					edit_stores: this.form.edit_stores,
					delete_stores: this.form.delete_stores,
					access_comments: this.form.access_comments,
					approve_comments: this.form.approve_comments,
					edit_comments: this.form.edit_comments,
					delete_comments: this.form.delete_comments,
					reported_comments: this.form.reported_comments
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						access_classifieds: false,
						approve_classifieds: false,
						edit_classifieds: false,
						delete_classifieds: false,
						classifieds_stats: false,
						access_stores: false,
						approve_stores: false,
						edit_stores: false,
						delete_stores: false,
						access_comments: false,
						approve_comments: false,
						edit_comments: false,
						delete_comments: false,
						reported_comments: false
					}
					this.$toasted.show(this.$t('t_toasted_moderator_role_created'), {
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