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
		            <div class="title">{{ $t('t_create_member_role') }}</div>
		            <span class="card-description">{{ $t('t_create_member_role_para') }}</span>
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

						<!-- Write comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.write_comments ? 'has-danger' : '']">
								<v-switch v-model="form.write_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_write_comments') }}</span>
								<small class="form-text" v-if="errors.write_comments">{{ errors.write_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.write_comments v-model="form.edit_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_edit_comments') }}</span>
								<small class="form-text" v-if="errors.edit_comments">{{ errors.edit_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.write_comments v-model="form.delete_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_delete_comments') }}</span>
								<small class="form-text" v-if="errors.delete_comments">{{ errors.delete_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Send messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.send_messages ? 'has-danger' : '']">
								<v-switch v-model="form.send_messages" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_send_messages') }}</span>
								<small class="form-text" v-if="errors.send_messages">{{ errors.send_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- receive messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.receive_messages ? 'has-danger' : '']">
								<v-switch :disabled="(form.send_messages == 0 && form.receive_messages == 0) ? true : false" v-model="form.receive_messages" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_receive_messages') }}</span>
								<small class="form-text" v-if="errors.receive_messages">{{ errors.receive_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_messages ? 'has-danger' : '']">
								<v-switch :disabled="(form.send_messages == 0 && form.receive_messages == 0) ? true : false" v-model="form.delete_messages" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_delete_messages') }}</span>
								<small class="form-text" v-if="errors.delete_messages">{{ errors.delete_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Report Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.report_classifieds ? 'has-danger' : '']">
								<v-switch v-model="form.report_classifieds" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_report_deals') }}</span>
								<small class="form-text" v-if="errors.report_classifieds">{{ errors.report_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Report Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.report_comments ? 'has-danger' : '']">
								<v-switch v-model="form.report_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_report_comments') }}</span>
								<small class="form-text" v-if="errors.report_comments">{{ errors.report_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Make offers -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.make_offers ? 'has-danger' : '']">
								<v-switch v-model="form.make_offers" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_make_offers') }}</span>
								<small class="form-text" v-if="errors.make_offers">{{ errors.make_offers[0] }}</small>
							</label>
						</v-flex>

						<!-- Receive offers -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.receive_offers ? 'has-danger' : '']">
								<v-switch v-model="form.receive_offers" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_receive_offers') }}</span>
								<small class="form-text" v-if="errors.receive_offers">{{ errors.receive_offers[0] }}</small>
							</label>
						</v-flex>

						<!-- Save search -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.save_search ? 'has-danger' : '']">
								<v-switch v-model="form.save_search" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_save_search') }}</span>
								<small class="form-text" v-if="errors.save_search">{{ errors.save_search[0] }}</small>
							</label>
						</v-flex>

						<!-- Follow shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.follow_stores ? 'has-danger' : '']">
								<v-switch v-model="form.follow_stores" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_follow_shops') }}</span>
								<small class="form-text" v-if="errors.follow_stores">{{ errors.follow_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Contact shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.contact_stores ? 'has-danger' : '']">
								<v-switch v-model="form.contact_stores" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_contact_shops') }}</span>
								<small class="form-text" v-if="errors.contact_stores">{{ errors.contact_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- See advertisements -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.see_advertisements ? 'has-danger' : '']">
								<v-switch v-model="form.see_advertisements" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_see_advertisements') }}</span>
								<small class="form-text" v-if="errors.see_advertisements">{{ errors.see_advertisements[0] }}</small>
							</label>
						</v-flex>

						<!-- See classifieds statistics -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.see_classifieds_stats ? 'has-danger' : '']">
								<v-switch v-model="form.see_classifieds_stats" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_deals_stats') }}</span>
								<small class="form-text" v-if="errors.see_classifieds_stats">{{ errors.see_classifieds_stats[0] }}</small>
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
					write_comments: false,
					edit_comments: false,
					delete_comments: false,
					send_messages: false,
					receive_messages: false,
					delete_messages: false,
					report_classifieds: false,
					report_comments: false,
					make_offers: false,
					receive_offers: false,
					save_search: false,
					follow_stores: false,
					contact_stores: false,
					see_advertisements: false,
					see_classifieds_stats: false
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_member_role'),
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
				.post('administrator/roles/options/create/member', {
					name: this.form.name,
					write_comments: this.form.write_comments,
					edit_comments: this.form.edit_comments,
					delete_comments: this.form.delete_comments,
					send_messages: this.form.send_messages,
					receive_messages: this.form.receive_messages,
					delete_messages: this.form.delete_messages,
					report_classifieds: this.form.report_classifieds,
					report_comments: this.form.report_comments,
					make_offers: this.form.make_offers,
					receive_offers: this.form.receive_offers,
					save_search: this.form.save_search,
					follow_stores: this.form.follow_stores,
					contact_stores: this.form.contact_stores,
					see_advertisements: this.form.see_advertisements,
					see_classifieds_stats: this.form.see_classifieds_stats
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						write_comments: false,
						edit_comments: false,
						delete_comments: false,
						send_messages: false,
						receive_messages: false,
						delete_messages: false,
						report_classifieds: false,
						report_comments: false,
						make_offers: false,
						receive_offers: false,
						save_search: false,
						follow_stores: false,
						contact_stores: false,
						see_advertisements: false,
						see_classifieds_stats: false
					}
					this.$toasted.show(this.$t('t_toasted_member_role_created'), {
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