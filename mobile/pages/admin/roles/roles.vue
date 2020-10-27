<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$owgate.allow($auth.user, 'delete', 'roles')">
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="error">mdi-alert-octagon-outline</v-icon>
					</div>
					<p class="pb-4">{{ $t('t_roles_delete_change') }}</p>

					<v-select
						v-model="newRole"
			          	:items="roles"
			          	item-text="name"
			          	item-value="unique_id"
			          	:label="$t('t_default_new_role')"
			          	:placeholder="$t('t_enter_default_new_role')"
						color="grey lighten-1"
			          	dense
			        ></v-select>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey lighten-1" text @click="cancel">
						{{ $t('t_cancel') }}
					</v-btn>
					<v-btn color="error" text @click="remove" :disabled="!newRole">
						{{ $t('t_delete_role') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- create new role -->
		<v-dialog v-model="dialogs.create" max-width="400" v-if="$owgate.allow($auth.user, 'create', 'roles')">
			<v-card tile>

				<v-toolbar flat dark :color="$vuetify.theme.dark ? '#5c5c5c' : 'primary'">
					<v-btn icon dark @click="dialogs.create = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title>{{ $t('t_choose_role_type') }}</v-toolbar-title>
				</v-toolbar>

				<v-card-text class="text-center py-5">

					<!-- Owner -->
					<v-btn block depressed :color="$vuetify.theme.dark ? '#5c5c5c' : '#bdc3c7'" class="mb-3" to="/administrator/roles/options/create/owner">
						{{ $t('t_create_owner_role') }}
					</v-btn>

					<!-- Administrator -->
					<v-btn block depressed :color="$vuetify.theme.dark ? '#5c5c5c' : '#bdc3c7'" class="mb-3" to="/administrator/roles/options/create/administrator">
						{{ $t('t_create_administrator_role') }}
					</v-btn>

					<!-- Moderator -->
					<v-btn block depressed :color="$vuetify.theme.dark ? '#5c5c5c' : '#bdc3c7'" class="mb-3" to="/administrator/roles/options/create/moderator">
						{{ $t('t_create_moderator_role') }}
					</v-btn>

					<!-- Member -->
					<v-btn block depressed :color="$vuetify.theme.dark ? '#5c5c5c' : '#bdc3c7'" to="/administrator/roles/options/create/member">
						{{ $t('t_create_member_role') }}
					</v-btn>

				</v-card-text>
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>

		<!-- Owner permissions -->
		<v-dialog v-model="ownerPermissionsDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable v-if="$owgate.allow($auth.user, 'access', 'roles')">
			<v-card tile>

				<v-toolbar flat color="red">
					<v-btn icon dark @click="ownerPermissionsDialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title class="white--text">{{ $t('t_owner_permissions') }}</v-toolbar-title>
				</v-toolbar>

				<v-card-text class="text-center py-5">
					
					<v-container grid-list-xl fluid class="pa-4">
						<v-layout wrap>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_plans" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_access_packages_plans') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_plans" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_create_plans') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_plans" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_edit_plans') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_plans" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_plans') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_currencies" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_currencies') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_currencies" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_currencies') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_currencies" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_currencies') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_currencies" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_currencies') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_geolocation" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_geo') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_geolocation" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_geo') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_geolocation" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_geo') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_geolocation" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_geo') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_roles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_roles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_roles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_roles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_roles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_roles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_roles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_roles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_themes" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_themes') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_themes" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_themes') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.request_themes" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_request_themes') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_general_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_general') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_auth_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_authentication') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_smtp_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_smtp') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_security_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_security') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_geo_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_geolocation') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_seo_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_seo') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_uploading_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_uploading') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_payment_gateways_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_payments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_social_media_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_social') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_watermark_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_watermark') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_footer_settings" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_settings_footer') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_users_subscriptions" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_users_subscriptions') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_payment_gateways" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_payments_gateways') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_sms_services" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_sms') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_clouds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_clouds') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_advertisements" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_advertisements') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_support" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_support') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_maintenance" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_maintenance') }}</span>
								</label>
							</v-flex>

						</v-layout>
					</v-container>

				</v-card-text>

				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>

		<!-- Administrator permissions -->
		<v-dialog v-model="administratorPermissionsDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable v-if="$owgate.allow($auth.user, 'access', 'roles')">
			<v-card tile>

				<v-toolbar flat color="green">
					<v-btn icon dark @click="administratorPermissionsDialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title class="white--text">{{ $t('t_administrator_permissions') }}</v-toolbar-title>
				</v-toolbar>

				<v-card-text class="text-center py-5">
					<v-container grid-list-xl fluid class="pa-4">
						<v-layout wrap>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_categories" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_categories') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_categories" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_categories') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_categories" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_categories') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_categories" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_categories') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_custom_fields" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_fields') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_custom_fields" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_fields') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_custom_fields" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_fields') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_custom_fields" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_fields') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_blog" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_blog') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_articles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_articles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_articles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_articles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_articles" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_articles') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_pages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_pages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_pages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_pages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_pages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_pages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_pages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_pages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_conversations" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_conversations') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_chat" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_chat') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_users_messages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_users_messages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_admin_messages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_admin_messages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_users" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_users') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.create_users" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_create_users') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_users" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_users') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_users" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_users') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_users" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_users') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.classifieds_stats" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_deals_stats') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.reported_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_reported_comments') }}</span>
								</label>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>

		<!-- Moderator permissions -->
		<v-dialog v-model="moderatorPermissionsDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable v-if="$owgate.allow($auth.user, 'access', 'roles')">
			<v-card tile>

				<v-toolbar flat color="orange">
					<v-btn icon dark @click="moderatorPermissionsDialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title class="white--text">{{ $t('t_moderator_permissions') }}</v-toolbar-title>
				</v-toolbar>

				<v-card-text class="text-center py-5">
					<v-container grid-list-xl fluid class="pa-4">
						<v-layout wrap>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.classifieds_stats" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_deals_stats') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.access_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_access_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.approve_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_approve_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.reported_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_reported_comments') }}</span>
								</label>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>

		<!-- Member permissions -->
		<v-dialog v-model="memberPermissionsDialog" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable v-if="$owgate.allow($auth.user, 'access', 'roles')">
			<v-card tile>

				<v-toolbar dark color="blue" flat>
					<v-btn icon dark @click="memberPermissionsDialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title class="white--text">{{ $t('t_member_permissions') }}</v-toolbar-title>
				</v-toolbar>
				<v-card-text class="text-center py-5">
					<v-container grid-list-xl fluid class="pa-4">
						<v-layout wrap>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.write_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_write_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.edit_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_edit_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.send_messages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_send_messages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.receive_messages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_receive_messages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.delete_messages" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_delete_messages') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.report_classifieds" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_report_deals') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.report_comments" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_report_comments') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.make_offers" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_make_offers') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.receive_offers" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_receive_offers') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.save_search" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_save_search') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.follow_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_follow_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.contact_stores" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_contact_shops') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.see_advertisements" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_see_advertisements') }}</span>
								</label>
							</v-flex>

							<v-flex xs12>
								<label class="form-group has-float-label">
									<v-checkbox v-model="permissions.see_classifieds_stats" class="ma-0 form-group form-control" disabled></v-checkbox>
									<span>{{ $t('t_role_deals_stats') }}</span>
								</label>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<div style="flex: 1 1 auto;"></div>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_roles') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Create -->
			        <v-tooltip top v-if="$owgate.allow($auth.user, 'create', 'roles')">
						<template v-slot:activator="{ on }">
							<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-4'" @click="dialogs.create = true">
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="roles"
					item-key="id"
      				hide-default-footer
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Group -->
					<template v-slot:item.group="{ item }">
						<v-btn v-if="item.group === 'owner'" text color="error">
							{{ $t('t_owner') }}
						</v-btn>
						<v-btn v-if="item.group === 'administrator'" text color="green accent-3">
							{{ $t('t_administrator') }}
						</v-btn>
						<v-btn v-if="item.group === 'moderator'" text color="warning">
							{{ $t('t_moderator') }}
						</v-btn>
						<v-btn v-if="item.group === 'member'" text color="info">
							{{ $t('t_member') }}
						</v-btn>
					</template>

					<!-- Role name -->
					<template v-slot:item.name="{ item }">{{ item.name }}</template>

					<!-- Permissions -->
					<template v-slot:item.permissions="{ item }">
						<v-tooltip top>
							<template v-slot:activator="{ on }">
								<v-btn icon text :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'" v-on="on" @click="showPermissions(item)">
									<v-icon>visibility</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_show_permissions') }}</span>
						</v-tooltip>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px" v-if="item.id !== 1">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Edit -->
								<v-list-item :to="'/administrator/roles/options/edit/' + item.unique_id" v-if="$owgate.allow($auth.user, 'edit', 'roles')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item @click="confirm(item, roles.indexOf(item))" v-if="$owgate.allow($auth.user, 'delete', 'roles')">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

							</v-list>
						</v-menu>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="rolesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
		      	</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'access', 'roles')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/roles')
			return {
				rolesData: response.data,
				roles: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_group'), value: 'group', sortable: false, align: 'center'},
		          	{ text: this.$t('t_role_name'), value: 'name', sortable: false, align: 'center'},
		          	{ text: this.$t('t_permissions'), value: 'permissions', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	create: false,
		        	delete: false,
		        },
		        permissions: {},
		        ownerPermissionsDialog: false,
		        administratorPermissionsDialog: false,
		        moderatorPermissionsDialog: false,
		        memberPermissionsDialog: false,
		        loading: false,
		        confirmDeleteDialog: false,
		        currentRole: '',
		        currentRoleIndex: '',
		        newRole: false
			}
		},

		head() {
			return {
				title: this.$t('t_roles'),
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
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('administrator/roles?page=' + page)
				.then(response => {
					this.selected  = []
					this.rolesData = response.data
					this.roles     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			confirm: function(role, index) {
				this.currentRole      = role
				this.currentRoleIndex = index
				this.dialogs.delete   = true
			},

			cancel: function() {
				this.currentRole      = ''
				this.currentRoleIndex = ''
				this.dialogs.delete   = false
			},

			remove: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'delete', 'roles')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				if (this.currentRole.unique_id === this.newRole) {
					this.$toasted.error(this.$t('t_select_different_role'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				}else{
					this.$axios
					.post('administrator/roles/options/delete', {
						role: this.currentRole.unique_id,
						next: this.newRole
					})
					.then(response => {
						this.roles.splice(this.currentRole, 1)
						this.$toasted.show(this.$t('t_toasted_role_deleted'), {
							icon: 'done_all',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
						this.currentRole      = ''
						this.currentRoleIndex = ''
						this.loading          = false
						this.dialogs.delete   = false
					})	
					.catch(error => {
						console.log(error)
						this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
						this.loading = false
					})
				}
			},

			showPermissions: function(role) {
				this.permissions = JSON.parse(role.permissions)
				if (role.group === 'owner') {this.ownerPermissionsDialog = true}
				if (role.group === 'administrator') {this.administratorPermissionsDialog = true}
				if (role.group === 'moderator') {this.moderatorPermissionsDialog = true}
				if (role.group === 'member') {this.memberPermissionsDialog = true}
			}
		}
  	}
</script>