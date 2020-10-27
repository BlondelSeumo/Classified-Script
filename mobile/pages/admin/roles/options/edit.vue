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
		            <div class="title">{{ $t('t_edit_role') }}</div>
		            <span class="card-description">{{ $t('t_edit_role_para') }}</span>
		          </div>
		        </v-card-title>
	
				<!-- Owner -->
				<v-container grid-list-xl fluid class="pa-4" v-if="role.group === 'owner'">
					<v-layout wrap>

						<!-- Role name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.owner.name"
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
								<v-switch v-model="form.owner.access_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_access_packages_plans') }}</span>
								<small class="form-text" v-if="errors.access_plans">{{ errors.access_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_plans ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_plans v-model="form.owner.create_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_create_plans') }}</span>
								<small class="form-text" v-if="errors.create_plans">{{ errors.create_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_plans ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_plans v-model="form.owner.edit_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_edit_plans') }}</span>
								<small class="form-text" v-if="errors.edit_plans">{{ errors.edit_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Plans -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_plans ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_plans v-model="form.owner.delete_plans" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_plans') }}</span>
								<small class="form-text" v-if="errors.delete_plans">{{ errors.delete_plans[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Currencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_currencies ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_currencies') }}</span>
								<small class="form-text" v-if="errors.access_currencies">{{ errors.access_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Currrencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_currencies ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_currencies v-model="form.owner.create_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_create_currencies') }}</span>
								<small class="form-text" v-if="errors.create_currencies">{{ errors.create_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Currrencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_currencies ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_currencies v-model="form.owner.edit_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_currencies') }}</span>
								<small class="form-text" v-if="errors.edit_currencies">{{ errors.edit_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Currrencies -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_currencies ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_currencies v-model="form.owner.delete_currencies" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_currencies') }}</span>
								<small class="form-text" v-if="errors.delete_currencies">{{ errors.delete_currencies[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Geolocation -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_geolocation ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_geo') }}</span>
								<small class="form-text" v-if="errors.access_geolocation">{{ errors.access_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Create (Countries/States/Cities) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_geolocation ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_geolocation v-model="form.owner.create_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_create_geo') }}</span>
								<small class="form-text" v-if="errors.create_geolocation">{{ errors.create_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit (Countries/States/Cities) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_geolocation ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_geolocation v-model="form.owner.edit_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_geo') }}</span>
								<small class="form-text" v-if="errors.edit_geolocation">{{ errors.edit_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete (Countries/States/Cities) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_geolocation ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_geolocation v-model="form.owner.delete_geolocation" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_geo') }}</span>
								<small class="form-text" v-if="errors.delete_geolocation">{{ errors.delete_geolocation[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_roles ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_roles') }}</span>
								<small class="form-text" v-if="errors.access_roles">{{ errors.access_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_roles ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_roles v-model="form.owner.create_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_create_roles') }}</span>
								<small class="form-text" v-if="errors.create_roles">{{ errors.create_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_roles ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_roles v-model="form.owner.edit_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_roles') }}</span>
								<small class="form-text" v-if="errors.edit_roles">{{ errors.edit_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Roles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_roles ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_roles v-model="form.owner.delete_roles" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_delete_roles') }}</span>
								<small class="form-text" v-if="errors.delete_roles">{{ errors.delete_roles[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Themes -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_themes ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_themes" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_themes') }}</span>
								<small class="form-text" v-if="errors.access_themes">{{ errors.access_themes[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Themes -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_themes ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_themes v-model="form.owner.edit_themes" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_edit_themes') }}</span>
								<small class="form-text" v-if="errors.edit_themes">{{ errors.edit_themes[0] }}</small>
							</label>
						</v-flex>

						<!-- Request Themes -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.request_themes ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_themes v-model="form.owner.request_themes" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_request_themes') }}</span>
								<small class="form-text" v-if="errors.request_themes">{{ errors.request_themes[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_settings ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings') }}</span>
								<small class="form-text" v-if="errors.access_settings">{{ errors.access_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access General Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_general_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_general_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_general') }}</span>
								<small class="form-text" v-if="errors.access_general_settings">{{ errors.access_general_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Authentication Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_auth_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_auth_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_authentication') }}</span>
								<small class="form-text" v-if="errors.access_auth_settings">{{ errors.access_auth_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access SMTP Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_smtp_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_smtp_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_smtp') }}</span>
								<small class="form-text" v-if="errors.access_smtp_settings">{{ errors.access_smtp_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Security Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_security_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_security_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_security') }}</span>
								<small class="form-text" v-if="errors.access_security_settings">{{ errors.access_security_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Geolocation Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_geo_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_geo_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_geolocation') }}</span>
								<small class="form-text" v-if="errors.access_geo_settings">{{ errors.access_geo_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access SEO Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_seo_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_seo_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_seo') }}</span>
								<small class="form-text" v-if="errors.access_seo_settings">{{ errors.access_seo_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Uploading Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_uploading_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_uploading_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_uploading') }}</span>
								<small class="form-text" v-if="errors.access_uploading_settings">{{ errors.access_uploading_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Payment Gateways Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_payment_gateways_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_payment_gateways_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_payments') }}</span>
								<small class="form-text" v-if="errors.access_payment_gateways_settings">{{ errors.access_payment_gateways_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Social Media Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_social_media_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_social_media_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_social') }}</span>
								<small class="form-text" v-if="errors.access_social_media_settings">{{ errors.access_social_media_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Watermark Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_watermark_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_watermark_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_watermark') }}</span>
								<small class="form-text" v-if="errors.access_watermark_settings">{{ errors.access_watermark_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Footer Settings -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_footer_settings ? 'has-danger' : '']">
								<v-switch :disabled=!form.owner.access_settings v-model="form.owner.access_footer_settings" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_settings_footer') }}</span>
								<small class="form-text" v-if="errors.access_footer_settings">{{ errors.access_footer_settings[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Users Subscriptions -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_users_subscriptions ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_users_subscriptions" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_users_subscriptions') }}</span>
								<small class="form-text" v-if="errors.access_users_subscriptions">{{ errors.access_users_subscriptions[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Payment Gateways -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_payment_gateways ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_payment_gateways" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_payments_gateways') }}</span>
								<small class="form-text" v-if="errors.access_payment_gateways">{{ errors.access_payment_gateways[0] }}</small>
							</label>
						</v-flex>

						<!-- Access SMS Services -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_sms_services ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_sms_services" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_sms') }}</span>
								<small class="form-text" v-if="errors.access_sms_services">{{ errors.access_sms_services[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Clouds Services -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_clouds ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_clouds" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_clouds') }}</span>
								<small class="form-text" v-if="errors.access_clouds">{{ errors.access_clouds[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Advertisements -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_advertisements ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_advertisements" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_advertisements') }}</span>
								<small class="form-text" v-if="errors.access_advertisements">{{ errors.access_advertisements[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Support -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_support ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_support" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_support') }}</span>
								<small class="form-text" v-if="errors.access_support">{{ errors.access_support[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Maintenance -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_maintenance ? 'has-danger' : '']">
								<v-switch v-model="form.owner.access_maintenance" class="ma-0 form-group form-control" color="red"></v-switch>
								<span>{{ $t('t_role_access_maintenance') }}</span>
								<small class="form-text" v-if="errors.access_maintenance">{{ errors.access_maintenance[0] }}</small>
							</label>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
						</v-flex>

					</v-layout>
				</v-container>

				<!-- Administrator -->
				<v-container grid-list-xl fluid class="pa-4" v-if="role.group === 'administrator'">
					<v-layout wrap>

						<!-- Role name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.administrator.name"
								color="grey lighten-1"
								:label="$t('t_role_name')"
								:placeholder="$t('t_enter_role_name')"
								counter="60"
								maxlength="60"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Access Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_categories ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_categories') }}</span>
								<small class="form-text" v-if="errors.access_categories">{{ errors.access_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_categories ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_categories v-model="form.administrator.create_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_categories') }}</span>
								<small class="form-text" v-if="errors.create_categories">{{ errors.create_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_categories ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_categories v-model="form.administrator.edit_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_categories') }}</span>
								<small class="form-text" v-if="errors.edit_categories">{{ errors.edit_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_categories ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_categories v-model="form.administrator.delete_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_categories') }}</span>
								<small class="form-text" v-if="errors.delete_categories">{{ errors.delete_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Custom fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_custom_fields ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_fields') }}</span>
								<small class="form-text" v-if="errors.access_custom_fields">{{ errors.access_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Custom Fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_custom_fields ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_custom_fields v-model="form.administrator.create_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_fields') }}</span>
								<small class="form-text" v-if="errors.create_custom_fields">{{ errors.create_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Custom Fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_custom_fields ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_custom_fields v-model="form.administrator.edit_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_fields') }}</span>
								<small class="form-text" v-if="errors.edit_custom_fields">{{ errors.edit_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Custom Fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_custom_fields ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_custom_fields v-model="form.administrator.delete_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_fields') }}</span>
								<small class="form-text" v-if="errors.delete_custom_fields">{{ errors.delete_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Blog -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_blog ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_blog" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_blog') }}</span>
								<small class="form-text" v-if="errors.access_blog">{{ errors.access_blog[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Articles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_articles ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_blog v-model="form.administrator.create_articles" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_articles') }}</span>
								<small class="form-text" v-if="errors.create_articles">{{ errors.create_articles[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Articles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_articles ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_blog v-model="form.administrator.edit_articles" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_articles') }}</span>
								<small class="form-text" v-if="errors.edit_articles">{{ errors.edit_articles[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Articles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_articles ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_blog v-model="form.administrator.delete_articles" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_articles') }}</span>
								<small class="form-text" v-if="errors.delete_articles">{{ errors.delete_articles[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_pages ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_pages') }}</span>
								<small class="form-text" v-if="errors.access_pages">{{ errors.access_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Create pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_pages ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_pages v-model="form.administrator.create_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_pages') }}</span>
								<small class="form-text" v-if="errors.create_pages">{{ errors.create_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_pages ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_pages v-model="form.administrator.edit_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_pages') }}</span>
								<small class="form-text" v-if="errors.edit_pages">{{ errors.edit_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_pages ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_pages v-model="form.administrator.delete_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_pages') }}</span>
								<small class="form-text" v-if="errors.delete_pages">{{ errors.delete_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Conversations -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_conversations ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_conversations" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_conversations') }}</span>
								<small class="form-text" v-if="errors.access_conversations">{{ errors.access_conversations[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Chat Section -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_chat ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_conversations v-model="form.administrator.access_chat" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_chat') }}</span>
								<small class="form-text" v-if="errors.access_chat">{{ errors.access_chat[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Users Messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_users_messages ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_conversations v-model="form.administrator.access_users_messages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_users_messages') }}</span>
								<small class="form-text" v-if="errors.access_users_messages">{{ errors.access_users_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Admin Messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_admin_messages ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_conversations v-model="form.administrator.access_admin_messages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_admin_messages') }}</span>
								<small class="form-text" v-if="errors.access_admin_messages">{{ errors.access_admin_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_users ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_users') }}</span>
								<small class="form-text" v-if="errors.access_users">{{ errors.access_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_users v-model="form.administrator.approve_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_users') }}</span>
								<small class="form-text" v-if="errors.approve_users">{{ errors.approve_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_users v-model="form.administrator.create_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_users') }}</span>
								<small class="form-text" v-if="errors.create_users">{{ errors.create_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_users v-model="form.administrator.edit_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_users') }}</span>
								<small class="form-text" v-if="errors.edit_users">{{ errors.edit_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_users v-model="form.administrator.delete_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_users') }}</span>
								<small class="form-text" v-if="errors.delete_users">{{ errors.delete_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_classifieds ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_deals') }}</span>
								<small class="form-text" v-if="errors.access_classifieds">{{ errors.access_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_classifieds v-model="form.administrator.approve_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_deals') }}</span>
								<small class="form-text" v-if="errors.approve_classifieds">{{ errors.approve_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_classifieds v-model="form.administrator.edit_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_deals') }}</span>
								<small class="form-text" v-if="errors.edit_classifieds">{{ errors.edit_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_classifieds v-model="form.administrator.delete_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_deals') }}</span>
								<small class="form-text" v-if="errors.delete_classifieds">{{ errors.delete_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- See Classifieds Statistics -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.classifieds_stats ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_classifieds v-model="form.administrator.classifieds_stats" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_deals_stats') }}</span>
								<small class="form-text" v-if="errors.classifieds_stats">{{ errors.classifieds_stats[0] }}</small>
							</label>
						</v-flex>

						<!-- Access shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_stores ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_shops') }}</span>
								<small class="form-text" v-if="errors.access_stores">{{ errors.access_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_stores v-model="form.administrator.approve_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_shops') }}</span>
								<small class="form-text" v-if="errors.approve_stores">{{ errors.approve_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_stores v-model="form.administrator.edit_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_shops') }}</span>
								<small class="form-text" v-if="errors.edit_stores">{{ errors.edit_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_stores v-model="form.administrator.delete_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_shops') }}</span>
								<small class="form-text" v-if="errors.delete_stores">{{ errors.delete_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_comments ? 'has-danger' : '']">
								<v-switch v-model="form.administrator.access_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_comments') }}</span>
								<small class="form-text" v-if="errors.access_comments">{{ errors.access_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_comments v-model="form.administrator.approve_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_comments') }}</span>
								<small class="form-text" v-if="errors.approve_comments">{{ errors.approve_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_comments v-model="form.administrator.edit_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_comments') }}</span>
								<small class="form-text" v-if="errors.edit_comments">{{ errors.edit_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_comments v-model="form.administrator.delete_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_comments') }}</span>
								<small class="form-text" v-if="errors.delete_comments">{{ errors.delete_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- See reported comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.reported_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.administrator.access_comments v-model="form.administrator.reported_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_reported_comments') }}</span>
								<small class="form-text" v-if="errors.reported_comments">{{ errors.reported_comments[0] }}</small>
							</label>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
						</v-flex>

					</v-layout>
				</v-container>

				<!-- Moderator -->
				<v-container grid-list-xl fluid class="pa-4" v-if="role.group === 'moderator'">
					<v-layout wrap>

						<!-- Role name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.moderator.name"
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
								<v-switch v-model="form.moderator.access_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_access_deals') }}</span>
								<small class="form-text" v-if="errors.access_classifieds">{{ errors.access_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_classifieds v-model="form.moderator.approve_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_approve_deals') }}</span>
								<small class="form-text" v-if="errors.approve_classifieds">{{ errors.approve_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_classifieds v-model="form.moderator.edit_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_edit_deals') }}</span>
								<small class="form-text" v-if="errors.edit_classifieds">{{ errors.edit_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_classifieds v-model="form.moderator.delete_classifieds" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_delete_deals') }}</span>
								<small class="form-text" v-if="errors.delete_classifieds">{{ errors.delete_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- See Classifieds Statistics -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.classifieds_stats ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_classifieds v-model="form.moderator.classifieds_stats" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_deals_stats') }}</span>
								<small class="form-text" v-if="errors.classifieds_stats">{{ errors.classifieds_stats[0] }}</small>
							</label>
						</v-flex>

						<!-- Access shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_stores ? 'has-danger' : '']">
								<v-switch v-model="form.moderator.access_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_access_shops') }}</span>
								<small class="form-text" v-if="errors.access_stores">{{ errors.access_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_stores v-model="form.moderator.approve_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_approve_shops') }}</span>
								<small class="form-text" v-if="errors.approve_stores">{{ errors.approve_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_stores v-model="form.moderator.edit_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_edit_shops') }}</span>
								<small class="form-text" v-if="errors.edit_stores">{{ errors.edit_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_stores v-model="form.moderator.delete_stores" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_delete_shops') }}</span>
								<small class="form-text" v-if="errors.delete_stores">{{ errors.delete_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_comments ? 'has-danger' : '']">
								<v-switch v-model="form.moderator.access_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_access_comments') }}</span>
								<small class="form-text" v-if="errors.access_comments">{{ errors.access_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_comments v-model="form.moderator.approve_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_approve_comments') }}</span>
								<small class="form-text" v-if="errors.approve_comments">{{ errors.approve_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_comments v-model="form.moderator.edit_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_edit_comments') }}</span>
								<small class="form-text" v-if="errors.edit_comments">{{ errors.edit_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_comments v-model="form.moderator.delete_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_delete_comments') }}</span>
								<small class="form-text" v-if="errors.delete_comments">{{ errors.delete_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- See reported comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.reported_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.moderator.access_comments v-model="form.moderator.reported_comments" class="ma-0 form-group form-control" color="orange"></v-switch>
								<span>{{ $t('t_role_reported_comments') }}</span>
								<small class="form-text" v-if="errors.reported_comments">{{ errors.reported_comments[0] }}</small>
							</label>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
						</v-flex>

					</v-layout>
				</v-container>

				<!-- Member -->
				<v-container grid-list-xl fluid class="pa-4" v-if="role.group === 'member'">
					<v-layout wrap>

						<!-- Role name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.member.name"
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
								<v-switch v-model="form.member.write_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_write_comments') }}</span>
								<small class="form-text" v-if="errors.write_comments">{{ errors.write_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.member.write_comments v-model="form.member.edit_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_edit_comments') }}</span>
								<small class="form-text" v-if="errors.edit_comments">{{ errors.edit_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.member.write_comments v-model="form.member.delete_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_delete_comments') }}</span>
								<small class="form-text" v-if="errors.delete_comments">{{ errors.delete_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Send messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.send_messages ? 'has-danger' : '']">
								<v-switch v-model="form.member.send_messages" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_send_messages') }}</span>
								<small class="form-text" v-if="errors.send_messages">{{ errors.send_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- receive messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.receive_messages ? 'has-danger' : '']">
								<v-switch :disabled="(form.member.send_messages == 0 && form.member.receive_messages == 0) ? true : false" v-model="form.member.receive_messages" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_receive_messages') }}</span>
								<small class="form-text" v-if="errors.receive_messages">{{ errors.receive_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_messages ? 'has-danger' : '']">
								<v-switch :disabled="(form.member.send_messages == 0 && form.member.receive_messages == 0) ? true : false" v-model="form.member.delete_messages" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_delete_messages') }}</span>
								<small class="form-text" v-if="errors.delete_messages">{{ errors.delete_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Report Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.report_classifieds ? 'has-danger' : '']">
								<v-switch v-model="form.member.report_classifieds" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_report_deals') }}</span>
								<small class="form-text" v-if="errors.report_classifieds">{{ errors.report_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Report Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.report_comments ? 'has-danger' : '']">
								<v-switch v-model="form.member.report_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_report_comments') }}</span>
								<small class="form-text" v-if="errors.report_comments">{{ errors.report_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Make offers -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.make_offers ? 'has-danger' : '']">
								<v-switch v-model="form.member.make_offers" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_make_offers') }}</span>
								<small class="form-text" v-if="errors.make_offers">{{ errors.make_offers[0] }}</small>
							</label>
						</v-flex>

						<!-- Receive offers -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.receive_offers ? 'has-danger' : '']">
								<v-switch v-model="form.member.receive_offers" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_receive_offers') }}</span>
								<small class="form-text" v-if="errors.receive_offers">{{ errors.receive_offers[0] }}</small>
							</label>
						</v-flex>

						<!-- Save search -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.save_search ? 'has-danger' : '']">
								<v-switch v-model="form.member.save_search" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_save_search') }}</span>
								<small class="form-text" v-if="errors.save_search">{{ errors.save_search[0] }}</small>
							</label>
						</v-flex>

						<!-- Follow shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.follow_stores ? 'has-danger' : '']">
								<v-switch v-model="form.member.follow_stores" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_follow_shops') }}</span>
								<small class="form-text" v-if="errors.follow_stores">{{ errors.follow_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Contact shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.contact_stores ? 'has-danger' : '']">
								<v-switch v-model="form.member.contact_stores" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_contact_shops') }}</span>
								<small class="form-text" v-if="errors.contact_stores">{{ errors.contact_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- See advertisements -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.see_advertisements ? 'has-danger' : '']">
								<v-switch v-model="form.member.see_advertisements" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_see_advertisements') }}</span>
								<small class="form-text" v-if="errors.see_advertisements">{{ errors.see_advertisements[0] }}</small>
							</label>
						</v-flex>

						<!-- See classifieds statistics -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.see_classifieds_stats ? 'has-danger' : '']">
								<v-switch v-model="form.member.see_classifieds_stats" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_role_deals_stats') }}</span>
								<small class="form-text" v-if="errors.see_classifieds_stats">{{ errors.see_classifieds_stats[0] }}</small>
							</label>
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

		async asyncData({ app, $axios, params, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'edit', 'roles')) {
				redirect('/administrator')
			}

			let response = await $axios.get(`administrator/roles/options/edit/${params.id}`)
			if (response.data.group === 'owner') {
				let permissions = JSON.parse(response.data.permissions)
				return {
					form: {
						owner: {
							name                             : response.data.name,
							access_plans                     : permissions.access_plans,
							create_plans                     : permissions.create_plans,
							edit_plans                       : permissions.edit_plans,
							delete_plans                     : permissions.delete_plans,
							access_currencies                : permissions.access_currencies,
							create_currencies                : permissions.create_currencies,
							edit_currencies                  : permissions.edit_currencies,
							delete_currencies                : permissions.delete_currencies,
							access_geolocation               : permissions.access_geolocation,
							create_geolocation               : permissions.create_geolocation,
							edit_geolocation                 : permissions.edit_geolocation,
							delete_geolocation               : permissions.delete_geolocation,
							access_roles                     : permissions.access_roles,
							create_roles                     : permissions.create_roles,
							edit_roles                       : permissions.edit_roles,
							delete_roles                     : permissions.delete_roles,
							access_themes                    : permissions.access_themes,
							edit_themes                      : permissions.edit_themes,
							request_themes                   : permissions.request_themes,
							access_settings                  : permissions.access_settings,
							access_general_settings          : permissions.access_general_settings,
							access_auth_settings             : permissions.access_auth_settings,
							access_smtp_settings             : permissions.access_smtp_settings,
							access_security_settings         : permissions.access_security_settings,
							access_geo_settings              : permissions.access_geo_settings,
							access_seo_settings              : permissions.access_seo_settings,
							access_uploading_settings        : permissions.access_uploading_settings,
							access_payment_gateways_settings : permissions.access_payment_gateways_settings,
							access_social_media_settings     : permissions.access_social_media_settings,
							access_watermark_settings        : permissions.access_watermark_settings,
							access_footer_settings           : permissions.access_footer_settings,
							access_users_subscriptions       : permissions.access_users_subscriptions,
							access_payment_gateways          : permissions.access_payment_gateways,
							access_sms_services              : permissions.access_sms_services,
							access_clouds                    : permissions.access_clouds,
							access_advertisements            : permissions.access_advertisements,
							access_support                   : permissions.access_support,
							access_maintenance               : permissions.access_maintenance,
						},
						administrator: null,
						moderator: null,
						member: null
					},
					role: response.data
				}
			}else if (response.data.group === 'administrator') {
				let permissions = JSON.parse(response.data.permissions)
				return {
					form: {
						owner: null,
						administrator: {
							name                  : response.data.name,
							access_categories     : permissions.access_categories,
							create_categories     : permissions.create_categories,
							edit_categories       : permissions.edit_categories,
							delete_categories     : permissions.delete_categories,
							access_custom_fields  : permissions.access_custom_fields,
							create_custom_fields  : permissions.create_custom_fields,
							edit_custom_fields    : permissions.edit_custom_fields,
							delete_custom_fields  : permissions.delete_custom_fields,
							access_blog           : permissions.access_blog,
							create_articles       : permissions.create_articles,
							edit_articles         : permissions.edit_articles,
							delete_articles       : permissions.delete_articles,
							access_pages          : permissions.access_pages,
							create_pages          : permissions.create_pages,
							edit_pages            : permissions.edit_pages,
							delete_pages          : permissions.delete_pages,
							access_conversations  : permissions.access_conversations,
							access_chat           : permissions.access_chat,
							access_users_messages : permissions.access_users_messages,
							access_admin_messages : permissions.access_admin_messages,
							access_users          : permissions.access_users,
							approve_users         : permissions.approve_users,
							edit_users            : permissions.edit_users,
							delete_users          : permissions.delete_users,
							create_users          : permissions.create_users,
							access_classifieds    : permissions.access_classifieds,
							approve_classifieds   : permissions.approve_classifieds,
							edit_classifieds      : permissions.edit_classifieds,
							delete_classifieds    : permissions.delete_classifieds,
							classifieds_stats     : permissions.classifieds_stats,
							access_stores         : permissions.access_stores,
							approve_stores        : permissions.approve_stores,
							edit_stores           : permissions.edit_stores,
							delete_stores         : permissions.delete_stores,
							access_comments       : permissions.access_comments,
							approve_comments      : permissions.approve_comments,
							edit_comments         : permissions.edit_comments,
							delete_comments       : permissions.delete_comments,
							reported_comments     : permissions.reported_comments
						},
						moderator: null,
						member: null
					},
					role: response.data
				}
			}else if (response.data.group === 'moderator') {
				let permissions = JSON.parse(response.data.permissions)
				return {
					form: {
						owner: null,
						moderator: {
							name                : response.data.name,
							access_classifieds  : permissions.access_classifieds,
							approve_classifieds : permissions.approve_classifieds,
							edit_classifieds    : permissions.edit_classifieds,
							delete_classifieds  : permissions.delete_classifieds,
							classifieds_stats   : permissions.classifieds_stats,
							access_stores       : permissions.access_stores,
							approve_stores      : permissions.approve_stores,
							edit_stores         : permissions.edit_stores,
							delete_stores       : permissions.delete_stores,
							access_comments     : permissions.access_comments,
							approve_comments    : permissions.approve_comments,
							edit_comments       : permissions.edit_comments,
							delete_comments     : permissions.delete_comments,
							reported_comments   : permissions.reported_comments
						},
						administrator: null,
						member: null
					},
					role: response.data
				}
			}else if (response.data.group === 'member') {
				let permissions = JSON.parse(response.data.permissions)
				return {
					form: {
						owner: null,
						member: {
							name                  : response.data.name,
							write_comments        : permissions.write_comments,
							edit_comments         : permissions.edit_comments,
							delete_comments       : permissions.delete_comments,
							send_messages         : permissions.send_messages,
							receive_messages      : permissions.receive_messages,
							delete_messages       : permissions.delete_messages,
							report_classifieds    : permissions.report_classifieds,
							report_comments       : permissions.report_comments,
							make_offers           : permissions.make_offers,
							receive_offers        : permissions.receive_offers,
							save_search           : permissions.save_search,
							follow_stores         : permissions.follow_stores,
							contact_stores        : permissions.contact_stores,
							see_advertisements    : permissions.see_advertisements,
							see_classifieds_stats : permissions.see_classifieds_stats
						},
						administrator: null,
						moderator: null
					},
					role: response.data
				}
			}
		},

		data: function() {
			return {
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_edit_role'),
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
				if (!this.$owgate.allow(this.$auth.user, 'edit', 'roles')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				// Owner data
				if (this.role.group === 'owner') {
					var data = {
						id: this.role.unique_id,
						name: this.form.owner.name,
						access_plans: this.form.owner.access_plans,
						create_plans: this.form.owner.create_plans,
						edit_plans: this.form.owner.edit_plans,
						delete_plans: this.form.owner.delete_plans,
						access_currencies: this.form.owner.access_currencies,
						create_currencies: this.form.owner.create_currencies,
						edit_currencies: this.form.owner.edit_currencies,
						delete_currencies: this.form.owner.delete_currencies,
						access_geolocation: this.form.owner.access_geolocation,
						create_geolocation: this.form.owner.create_geolocation,
						edit_geolocation: this.form.owner.edit_geolocation,
						delete_geolocation: this.form.owner.delete_geolocation,
						access_roles: this.form.owner.access_roles,
						create_roles: this.form.owner.create_roles,
						edit_roles: this.form.owner.edit_roles,
						delete_roles: this.form.owner.delete_roles,
						access_themes: this.form.owner.access_themes,
						edit_themes: this.form.owner.edit_themes,
						request_themes: this.form.owner.request_themes,
						access_settings: this.form.owner.access_settings,
						access_general_settings: this.form.owner.access_general_settings,
						access_auth_settings: this.form.owner.access_auth_settings,
						access_smtp_settings: this.form.owner.access_smtp_settings,
						access_security_settings: this.form.owner.access_security_settings,
						access_geo_settings: this.form.owner.access_geo_settings,
						access_seo_settings: this.form.owner.access_seo_settings,
						access_uploading_settings: this.form.owner.access_uploading_settings,
						access_payment_gateways_settings: this.form.owner.access_payment_gateways_settings,
						access_social_media_settings: this.form.owner.access_social_media_settings,
						access_watermark_settings: this.form.owner.access_watermark_settings,
						access_footer_settings: this.form.owner.access_footer_settings,
						access_users_subscriptions: this.form.owner.access_users_subscriptions,
						access_payment_gateways: this.form.owner.access_payment_gateways,
						access_sms_services: this.form.owner.access_sms_services,
						access_clouds: this.form.owner.access_clouds,
						access_advertisements: this.form.owner.access_advertisements,
						access_support: this.form.owner.access_support,
						access_maintenance: this.form.owner.access_maintenance
					}
				}
				// Administrator data
				if (this.role.group === 'administrator') {
					var data = {
						id: this.role.unique_id,
						name: this.form.administrator.name,
						access_categories: this.form.administrator.access_categories,
						create_categories: this.form.administrator.create_categories,
						edit_categories: this.form.administrator.edit_categories,
						delete_categories: this.form.administrator.delete_categories,
						access_custom_fields: this.form.administrator.access_custom_fields,
						create_custom_fields: this.form.administrator.create_custom_fields,
						edit_custom_fields: this.form.administrator.edit_custom_fields,
						delete_custom_fields: this.form.administrator.delete_custom_fields,
						access_blog: this.form.administrator.access_blog,
						create_articles: this.form.administrator.create_articles,
						edit_articles: this.form.administrator.edit_articles,
						delete_articles: this.form.administrator.delete_articles,
						access_pages: this.form.administrator.access_pages,
						create_pages: this.form.administrator.create_pages,
						edit_pages: this.form.administrator.edit_pages,
						delete_pages: this.form.administrator.delete_pages,
						access_conversations: this.form.administrator.access_conversations,
						access_chat: this.form.administrator.access_chat,
						access_users_messages: this.form.administrator.access_users_messages,
						access_admin_messages: this.form.administrator.access_admin_messages,
						access_users: this.form.administrator.access_users,
						approve_users: this.form.administrator.approve_users,
						edit_users: this.form.administrator.edit_users,
						delete_users: this.form.administrator.delete_users,
						create_users: this.form.administrator.create_users,
						access_classifieds: this.form.administrator.access_classifieds,
						approve_classifieds: this.form.administrator.approve_classifieds,
						edit_classifieds: this.form.administrator.edit_classifieds,
						delete_classifieds: this.form.administrator.delete_classifieds,
						classifieds_stats: this.form.administrator.classifieds_stats,
						access_stores: this.form.administrator.access_stores,
						approve_stores: this.form.administrator.approve_stores,
						edit_stores: this.form.administrator.edit_stores,
						delete_stores: this.form.administrator.delete_stores,
						access_comments: this.form.administrator.access_comments,
						approve_comments: this.form.administrator.approve_comments,
						edit_comments: this.form.administrator.edit_comments,
						delete_comments: this.form.administrator.delete_comments,
						reported_comments: this.form.administrator.reported_comments
					}
				}
				// Moderator data
				if (this.role.group === 'moderator') {
					var data = {
						id: this.role.unique_id,
						name: this.form.moderator.name,
						access_classifieds: this.form.moderator.access_classifieds,
						approve_classifieds: this.form.moderator.approve_classifieds,
						edit_classifieds: this.form.moderator.edit_classifieds,
						delete_classifieds: this.form.moderator.delete_classifieds,
						classifieds_stats: this.form.moderator.classifieds_stats,
						access_stores: this.form.moderator.access_stores,
						approve_stores: this.form.moderator.approve_stores,
						edit_stores: this.form.moderator.edit_stores,
						delete_stores: this.form.moderator.delete_stores,
						access_comments: this.form.moderator.access_comments,
						approve_comments: this.form.moderator.approve_comments,
						edit_comments: this.form.moderator.edit_comments,
						delete_comments: this.form.moderator.delete_comments,
						reported_comments: this.form.moderator.reported_comments
					}
				}
				// Member data
				if (this.role.group === 'member') {
					var data = {
						id: this.role.unique_id,
						name: this.form.member.name,
						write_comments: this.form.member.write_comments,
						edit_comments: this.form.member.edit_comments,
						delete_comments: this.form.member.delete_comments,
						send_messages: this.form.member.send_messages,
						receive_messages: this.form.member.receive_messages,
						delete_messages: this.form.member.delete_messages,
						report_classifieds: this.form.member.report_classifieds,
						report_comments: this.form.member.report_comments,
						make_offers: this.form.member.make_offers,
						receive_offers: this.form.member.receive_offers,
						save_search: this.form.member.save_search,
						follow_stores: this.form.member.follow_stores,
						contact_stores: this.form.member.contact_stores,
						see_advertisements: this.form.member.see_advertisements,
						see_classifieds_stats: this.form.member.see_classifieds_stats
					}
				}
				this.$axios
				.post('administrator/roles/options/edit', data)
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_role_updated'), {
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