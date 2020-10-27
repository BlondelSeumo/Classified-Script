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
		            <div class="title">{{ $t('t_create_owner_role') }}</div>
		            <span class="card-description">{{ $t('t_create_owner_role_para') }}</span>
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

						<!-- Access Packages Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_plans ? 'has-danger' : '']">
								<v-switch v-model="form.access_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_access_packages_plans') }}</span>
								<small class="form-text" v-if="errors.access_plans">{{ errors.access_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_plans ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_plans v-model="form.create_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_create_plans') }}</span>
								<small class="form-text" v-if="errors.create_plans">{{ errors.create_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_plans ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_plans v-model="form.edit_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_edit_plans') }}</span>
								<small class="form-text" v-if="errors.edit_plans">{{ errors.edit_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_plans ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_plans v-model="form.delete_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_plans') }}</span>
								<small class="form-text" v-if="errors.delete_plans">{{ errors.delete_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Currencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_currencies ? 'has-danger' : '']">
								<v-switch v-model="form.access_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_currencies') }}</span>
								<small class="form-text" v-if="errors.access_currencies">{{ errors.access_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Currrencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_currencies ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_currencies v-model="form.create_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_create_currencies') }}</span>
								<small class="form-text" v-if="errors.create_currencies">{{ errors.create_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Currrencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_currencies ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_currencies v-model="form.edit_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_currencies') }}</span>
								<small class="form-text" v-if="errors.edit_currencies">{{ errors.edit_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Currrencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_currencies ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_currencies v-model="form.delete_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_currencies') }}</span>
								<small class="form-text" v-if="errors.delete_currencies">{{ errors.delete_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Geolocation -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_geolocation ? 'has-danger' : '']">
								<v-switch v-model="form.access_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_geo') }}</span>
								<small class="form-text" v-if="errors.access_geolocation">{{ errors.access_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Create (Countries/States/Cities) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_geolocation ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_geolocation v-model="form.create_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_create_geo') }}</span>
								<small class="form-text" v-if="errors.create_geolocation">{{ errors.create_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit (Countries/States/Cities) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_geolocation ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_geolocation v-model="form.edit_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_geo') }}</span>
								<small class="form-text" v-if="errors.edit_geolocation">{{ errors.edit_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete (Countries/States/Cities) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_geolocation ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_geolocation v-model="form.delete_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_geo') }}</span>
								<small class="form-text" v-if="errors.delete_geolocation">{{ errors.delete_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_roles ? 'has-danger' : '']">
								<v-switch v-model="form.access_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_roles') }}</span>
								<small class="form-text" v-if="errors.access_roles">{{ errors.access_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_roles ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_roles v-model="form.create_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_create_roles') }}</span>
								<small class="form-text" v-if="errors.create_roles">{{ errors.create_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_roles ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_roles v-model="form.edit_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_roles') }}</span>
								<small class="form-text" v-if="errors.edit_roles">{{ errors.edit_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_roles ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_roles v-model="form.delete_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_roles') }}</span>
								<small class="form-text" v-if="errors.delete_roles">{{ errors.delete_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Themes -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_themes ? 'has-danger' : '']">
								<v-switch v-model="form.access_themes" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_themes') }}</span>
								<small class="form-text" v-if="errors.access_themes">{{ errors.access_themes[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Themes -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_themes ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_themes v-model="form.edit_themes" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_themes') }}</span>
								<small class="form-text" v-if="errors.edit_themes">{{ errors.edit_themes[0] }}</small>
							</label>
						</v-flex>

						<!-- Request Themes -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.request_themes ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_themes v-model="form.request_themes" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_request_themes') }}</span>
								<small class="form-text" v-if="errors.request_themes">{{ errors.request_themes[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_settings ? 'has-danger' : '']">
								<v-switch v-model="form.access_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings') }}</span>
								<small class="form-text" v-if="errors.access_settings">{{ errors.access_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access General Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_general_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_general_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_general') }}</span>
								<small class="form-text" v-if="errors.access_general_settings">{{ errors.access_general_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Authentication Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_auth_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_auth_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_authentication') }}</span>
								<small class="form-text" v-if="errors.access_auth_settings">{{ errors.access_auth_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access SMTP Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_smtp_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_smtp_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_smtp') }}</span>
								<small class="form-text" v-if="errors.access_smtp_settings">{{ errors.access_smtp_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Security Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_security_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_security_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_security') }}</span>
								<small class="form-text" v-if="errors.access_security_settings">{{ errors.access_security_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Geolocation Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_geo_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_geo_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_geolocation') }}</span>
								<small class="form-text" v-if="errors.access_geo_settings">{{ errors.access_geo_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access SEO Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_seo_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_seo_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_seo') }}</span>
								<small class="form-text" v-if="errors.access_seo_settings">{{ errors.access_seo_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Uploading Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_uploading_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_uploading_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_uploading') }}</span>
								<small class="form-text" v-if="errors.access_uploading_settings">{{ errors.access_uploading_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Payment Gateways Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_payment_gateways_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_payment_gateways_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_payments') }}</span>
								<small class="form-text" v-if="errors.access_payment_gateways_settings">{{ errors.access_payment_gateways_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Social Media Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_social_media_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_social_media_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_social') }}</span>
								<small class="form-text" v-if="errors.access_social_media_settings">{{ errors.access_social_media_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Watermark Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_watermark_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_watermark_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_watermark') }}</span>
								<small class="form-text" v-if="errors.access_watermark_settings">{{ errors.access_watermark_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Footer Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_footer_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_settings v-model="form.access_footer_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_footer') }}</span>
								<small class="form-text" v-if="errors.access_footer_settings">{{ errors.access_footer_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Users Subscriptions -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_users_subscriptions ? 'has-danger' : '']">
								<v-switch v-model="form.access_users_subscriptions" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_users_subscriptions') }}</span>
								<small class="form-text" v-if="errors.access_users_subscriptions">{{ errors.access_users_subscriptions[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Payment Gateways -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_payment_gateways ? 'has-danger' : '']">
								<v-switch v-model="form.access_payment_gateways" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_payments_gateways') }}</span>
								<small class="form-text" v-if="errors.access_payment_gateways">{{ errors.access_payment_gateways[0] }}</small>
							</label>
						</v-flex>

						<!-- Access SMS Services -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_sms_services ? 'has-danger' : '']">
								<v-switch v-model="form.access_sms_services" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_sms') }}</span>
								<small class="form-text" v-if="errors.access_sms_services">{{ errors.access_sms_services[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Clouds Services -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_clouds ? 'has-danger' : '']">
								<v-switch v-model="form.access_clouds" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_clouds') }}</span>
								<small class="form-text" v-if="errors.access_clouds">{{ errors.access_clouds[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Advertisements -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_advertisements ? 'has-danger' : '']">
								<v-switch v-model="form.access_advertisements" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_advertisements') }}</span>
								<small class="form-text" v-if="errors.access_advertisements">{{ errors.access_advertisements[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Support -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_support ? 'has-danger' : '']">
								<v-switch v-model="form.access_support" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_support') }}</span>
								<small class="form-text" v-if="errors.access_support">{{ errors.access_support[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Maintenance -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_maintenance ? 'has-danger' : '']">
								<v-switch v-model="form.access_maintenance" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_maintenance') }}</span>
								<small class="form-text" v-if="errors.access_maintenance">{{ errors.access_maintenance[0] }}</small>
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
					access_plans: false,
					create_plans: false,
					edit_plans: false,
					delete_plans: false,
					access_currencies: false,
					create_currencies: false,
					edit_currencies: false,
					delete_currencies: false,
					access_geolocation: false,
					create_geolocation: false,
					edit_geolocation: false,
					delete_geolocation: false,
					access_roles: false,
					create_roles: false,
					edit_roles: false,
					delete_roles: false,
					access_themes: false,
					edit_themes: false,
					request_themes: false,
					access_settings: false,
					access_general_settings: false,
					access_auth_settings: false,
					access_smtp_settings: false,
					access_security_settings: false,
					access_geo_settings: false,
					access_seo_settings: false,
					access_uploading_settings: false,
					access_payment_gateways_settings: false,
					access_social_media_settings: false,
					access_watermark_settings: false,
					access_footer_settings: false,
					access_users_subscriptions: false,
					access_payment_gateways: false,
					access_sms_services: false,
					access_clouds: false,
					access_advertisements: false,
					access_support: false,
					access_maintenance: false
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_owner_role'),
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
				.post('administrator/roles/options/create/owner', {
					name: this.form.name,
					access_plans: this.form.access_plans,
					create_plans: this.form.create_plans,
					edit_plans: this.form.edit_plans,
					delete_plans: this.form.delete_plans,
					access_currencies: this.form.access_currencies,
					create_currencies: this.form.create_currencies,
					edit_currencies: this.form.edit_currencies,
					delete_currencies: this.form.delete_currencies,
					access_geolocation: this.form.access_geolocation,
					create_geolocation: this.form.create_geolocation,
					edit_geolocation: this.form.edit_geolocation,
					delete_geolocation: this.form.delete_geolocation,
					access_roles: this.form.access_roles,
					create_roles: this.form.create_roles,
					edit_roles: this.form.edit_roles,
					delete_roles: this.form.delete_roles,
					access_themes: this.form.access_themes,
					edit_themes: this.form.edit_themes,
					request_themes: this.form.request_themes,
					access_settings: this.form.access_settings,
					access_general_settings: this.form.access_general_settings,
					access_auth_settings: this.form.access_auth_settings,
					access_smtp_settings: this.form.access_smtp_settings,
					access_security_settings: this.form.access_security_settings,
					access_geo_settings: this.form.access_geo_settings,
					access_seo_settings: this.form.access_seo_settings,
					access_uploading_settings: this.form.access_uploading_settings,
					access_payment_gateways_settings: this.form.access_payment_gateways_settings,
					access_social_media_settings: this.form.access_social_media_settings,
					access_watermark_settings: this.form.access_watermark_settings,
					access_footer_settings: this.form.access_footer_settings,
					access_users_subscriptions: this.form.access_users_subscriptions,
					access_payment_gateways: this.form.access_payment_gateways,
					access_sms_services: this.form.access_sms_services,
					access_clouds: this.form.access_clouds,
					access_advertisements: this.form.access_advertisements,
					access_support: this.form.access_support,
					access_maintenance: this.form.access_maintenance
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						access_plans: false,
						create_plans: false,
						edit_plans: false,
						delete_plans: false,
						access_currencies: false,
						create_currencies: false,
						edit_currencies: false,
						delete_currencies: false,
						access_geolocation: false,
						create_geolocation: false,
						edit_geolocation: false,
						delete_geolocation: false,
						access_roles: false,
						create_roles: false,
						edit_roles: false,
						delete_roles: false,
						access_themes: false,
						edit_themes: false,
						request_themes: false,
						access_settings: false,
						access_general_settings: false,
						access_auth_settings: false,
						access_smtp_settings: false,
						access_security_settings: false,
						access_geo_settings: false,
						access_seo_settings: false,
						access_uploading_settings: false,
						access_payment_gateways_settings: false,
						access_social_media_settings: false,
						access_watermark_settings: false,
						access_footer_settings: false,
						access_users_subscriptions: false,
						access_payment_gateways: false,
						access_sms_services: false,
						access_clouds: false,
						access_advertisements: false,
						access_support: false,
						access_maintenance: false
					}
					this.$toasted.show(this.$t('t_toasted_owner_role_created'), {
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