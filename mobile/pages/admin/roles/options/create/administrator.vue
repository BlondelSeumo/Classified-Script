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
		            	<div class="title">{{ $t('t_create_administrator_role') }}</div>
		            	<span class="card-description">{{ $t('t_create_admin_role_para') }}</span>
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

						<!-- Access Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_categories ? 'has-danger' : '']">
								<v-switch v-model="form.access_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_categories') }}</span>
								<small class="form-text" v-if="errors.access_categories">{{ errors.access_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_categories ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_categories v-model="form.create_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_categories') }}</span>
								<small class="form-text" v-if="errors.create_categories">{{ errors.create_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_categories ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_categories v-model="form.edit_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_categories') }}</span>
								<small class="form-text" v-if="errors.edit_categories">{{ errors.edit_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Categories -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_categories ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_categories v-model="form.delete_categories" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_categories') }}</span>
								<small class="form-text" v-if="errors.delete_categories">{{ errors.delete_categories[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Custom fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_custom_fields ? 'has-danger' : '']">
								<v-switch v-model="form.access_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_fields') }}</span>
								<small class="form-text" v-if="errors.access_custom_fields">{{ errors.access_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Custom Fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_custom_fields ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_custom_fields v-model="form.create_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_fields') }}</span>
								<small class="form-text" v-if="errors.create_custom_fields">{{ errors.create_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Custom Fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_custom_fields ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_custom_fields v-model="form.edit_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_fields') }}</span>
								<small class="form-text" v-if="errors.edit_custom_fields">{{ errors.edit_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Custom Fields -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_custom_fields ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_custom_fields v-model="form.delete_custom_fields" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_fields') }}</span>
								<small class="form-text" v-if="errors.delete_custom_fields">{{ errors.delete_custom_fields[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Blog -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_blog ? 'has-danger' : '']">
								<v-switch v-model="form.access_blog" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_blog') }}</span>
								<small class="form-text" v-if="errors.access_blog">{{ errors.access_blog[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Articles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_articles ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_blog v-model="form.create_articles" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_articles') }}</span>
								<small class="form-text" v-if="errors.create_articles">{{ errors.create_articles[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Articles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_articles ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_blog v-model="form.edit_articles" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_articles') }}</span>
								<small class="form-text" v-if="errors.edit_articles">{{ errors.edit_articles[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Articles -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_articles ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_blog v-model="form.delete_articles" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_articles') }}</span>
								<small class="form-text" v-if="errors.delete_articles">{{ errors.delete_articles[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_pages ? 'has-danger' : '']">
								<v-switch v-model="form.access_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_pages') }}</span>
								<small class="form-text" v-if="errors.access_pages">{{ errors.access_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Create pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_pages ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_pages v-model="form.create_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_pages') }}</span>
								<small class="form-text" v-if="errors.create_pages">{{ errors.create_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_pages ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_pages v-model="form.edit_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_pages') }}</span>
								<small class="form-text" v-if="errors.edit_pages">{{ errors.edit_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete pages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_pages ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_pages v-model="form.delete_pages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_pages') }}</span>
								<small class="form-text" v-if="errors.delete_pages">{{ errors.delete_pages[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Conversations -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_conversations ? 'has-danger' : '']">
								<v-switch v-model="form.access_conversations" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_conversations') }}</span>
								<small class="form-text" v-if="errors.access_conversations">{{ errors.access_conversations[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Chat Section -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_chat ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_conversations v-model="form.access_chat" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_chat') }}</span>
								<small class="form-text" v-if="errors.access_chat">{{ errors.access_chat[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Users Messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_users_messages ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_conversations v-model="form.access_users_messages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_users_messages') }}</span>
								<small class="form-text" v-if="errors.access_users_messages">{{ errors.access_users_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Admin Messages -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_admin_messages ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_conversations v-model="form.access_admin_messages" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_admin_messages') }}</span>
								<small class="form-text" v-if="errors.access_admin_messages">{{ errors.access_admin_messages[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_users ? 'has-danger' : '']">
								<v-switch v-model="form.access_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_users') }}</span>
								<small class="form-text" v-if="errors.access_users">{{ errors.access_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_users v-model="form.approve_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_users') }}</span>
								<small class="form-text" v-if="errors.approve_users">{{ errors.approve_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Create Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.create_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_users v-model="form.create_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_create_users') }}</span>
								<small class="form-text" v-if="errors.create_users">{{ errors.create_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_users v-model="form.edit_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_users') }}</span>
								<small class="form-text" v-if="errors.edit_users">{{ errors.edit_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Users -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_users ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_users v-model="form.delete_users" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_users') }}</span>
								<small class="form-text" v-if="errors.delete_users">{{ errors.delete_users[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_classifieds ? 'has-danger' : '']">
								<v-switch v-model="form.access_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_deals') }}</span>
								<small class="form-text" v-if="errors.access_classifieds">{{ errors.access_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.approve_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_deals') }}</span>
								<small class="form-text" v-if="errors.approve_classifieds">{{ errors.approve_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.edit_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_deals') }}</span>
								<small class="form-text" v-if="errors.edit_classifieds">{{ errors.edit_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Classifieds -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_classifieds ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.delete_classifieds" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_deals') }}</span>
								<small class="form-text" v-if="errors.delete_classifieds">{{ errors.delete_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- See Classifieds Statistics -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.classifieds_stats ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_classifieds v-model="form.classifieds_stats" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_deals_stats') }}</span>
								<small class="form-text" v-if="errors.classifieds_stats">{{ errors.classifieds_stats[0] }}</small>
							</label>
						</v-flex>

						<!-- Access shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_stores ? 'has-danger' : '']">
								<v-switch v-model="form.access_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_shops') }}</span>
								<small class="form-text" v-if="errors.access_stores">{{ errors.access_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_stores v-model="form.approve_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_shops') }}</span>
								<small class="form-text" v-if="errors.approve_stores">{{ errors.approve_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_stores v-model="form.edit_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_shops') }}</span>
								<small class="form-text" v-if="errors.edit_stores">{{ errors.edit_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_stores ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_stores v-model="form.delete_stores" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_shops') }}</span>
								<small class="form-text" v-if="errors.delete_stores">{{ errors.delete_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Access Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.access_comments ? 'has-danger' : '']">
								<v-switch v-model="form.access_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_access_comments') }}</span>
								<small class="form-text" v-if="errors.access_comments">{{ errors.access_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Approve Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.approve_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.approve_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_approve_comments') }}</span>
								<small class="form-text" v-if="errors.approve_comments">{{ errors.approve_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Edit Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.edit_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.edit_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_edit_comments') }}</span>
								<small class="form-text" v-if="errors.edit_comments">{{ errors.edit_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Delete Comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.delete_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.delete_comments" class="ma-0 form-group form-control" color="green"></v-switch>
								<span>{{ $t('t_role_delete_comments') }}</span>
								<small class="form-text" v-if="errors.delete_comments">{{ errors.delete_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- See reported comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.reported_comments ? 'has-danger' : '']">
								<v-switch :disabled=!form.access_comments v-model="form.reported_comments" class="ma-0 form-group form-control" color="green"></v-switch>
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
					access_categories: false,
					create_categories: false,
					edit_categories: false,
					delete_categories: false,
					access_custom_fields: false,
					create_custom_fields: false,
					edit_custom_fields: false,
					delete_custom_fields: false,
					access_blog: false,
					create_articles: false,
					edit_articles: false,
					delete_articles: false,
					access_pages: false,
					create_pages: false,
					edit_pages: false,
					delete_pages: false,
					access_conversations: false,
					access_chat: false,
					access_users_messages: false,
					access_admin_messages: false,
					access_users: false,
					approve_users: false,
					edit_users: false,
					delete_users: false,
					create_users: false,
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
				title: this.$t('t_create_administrator_role'),
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
				.post('administrator/roles/options/create/administrator', {
					name: this.form.name,
					access_categories: this.form.access_categories,
					create_categories: this.form.create_categories,
					edit_categories: this.form.edit_categories,
					delete_categories: this.form.delete_categories,
					access_custom_fields: this.form.access_custom_fields,
					create_custom_fields: this.form.create_custom_fields,
					edit_custom_fields: this.form.edit_custom_fields,
					delete_custom_fields: this.form.delete_custom_fields,
					access_blog: this.form.access_blog,
					create_articles: this.form.create_articles,
					edit_articles: this.form.edit_articles,
					delete_articles: this.form.delete_articles,
					access_pages: this.form.access_pages,
					create_pages: this.form.create_pages,
					edit_pages: this.form.edit_pages,
					delete_pages: this.form.delete_pages,
					access_conversations: this.form.access_conversations,
					access_chat: this.form.access_chat,
					access_users_messages: this.form.access_users_messages,
					access_admin_messages: this.form.access_admin_messages,
					access_users: this.form.access_users,
					approve_users: this.form.approve_users,
					edit_users: this.form.edit_users,
					delete_users: this.form.delete_users,
					create_users: this.form.create_users,
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
						access_categories: false,
						create_categories: false,
						edit_categories: false,
						delete_categories: false,
						access_custom_fields: false,
						create_custom_fields: false,
						edit_custom_fields: false,
						delete_custom_fields: false,
						access_blog: false,
						create_articles: false,
						edit_articles: false,
						delete_articles: false,
						access_pages: false,
						create_pages: false,
						edit_pages: false,
						delete_pages: false,
						access_conversations: false,
						access_chat: false,
						access_users_messages: false,
						access_admin_messages: false,
						access_users: false,
						approve_users: false,
						edit_users: false,
						delete_users: false,
						create_users: false,
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
					this.$toasted.show(this.$t('t_toasted_admin_role_created'), {
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