<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container grid-list-xl fluid class="pa-4">
			<v-layout wrap class="pl-5 pr-5">

				<v-flex xs12 class="text-center text-uppercase pt-5 mb-4">
					<h2 class="headline font-weight-black">{{ $t('t_choose_default_theme') }}</h2>
					<span class="caption grey--text darken-1">{{ $t('t_choose_default_theme_para') }}</span>
				</v-flex>

				<!-- Themes -->
				<v-flex xs6 v-for="(theme, name) in themes" :key="name">
					<div class="small-card-item m-card" style="height: 150px;">
					    <div class="small-card-item-preview">
					    	<v-img
					          	:src="$homeApi('public/images/themes/' + name + '.png')"
					          	height="80"
					        ></v-img>
					        <div class="small-card-item-overlay" v-if="activated !== name && $owgate.allow($auth.user, 'edit', 'themes')">
					        	<a class="small-card-item-control" @click.prevent="activate(name)">
					        		<i class="mdi mdi-brush" style="color: rgb(64, 128, 255); cursor: pointer;"></i>
					        	</a>
					        </div>
					    </div>
					    <div class="small-card-item-info">
					    	<span class="small-card-item-name link-info">{{ name }}</span>
					        <span class="small-card-item-date green--text" v-if="activated == name">{{ $t('t_active_theme') }}</span>
					    </div>
					</div>
				</v-flex>

				<!-- Request new -->
				<v-flex xs6>
					<v-tooltip top>
						<template v-slot:activator="{ on }">
							<div v-on="on" class="small-card-item" style="height: 150px; background-color: transparent; text-align: center; border: 2px dashed rgb(218, 218, 220); box-shadow: none;">
								<nuxt-link to="/administrator/themes/request" style="display: block; height: 100%; width: 100%;">
									<i class="mdi mdi-palette" style="color: rgb(209, 209, 212); font-size: 45px; padding-top: 40px; display: block;"></i>
								</nuxt-link>
							</div>
						</template>
						<span>{{ $t('t_request_theme') }}</span>
					</v-tooltip>
				</v-flex>

			</v-layout>
		</v-container>

	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'access', 'themes')) {
				redirect('/administrator')
			}

			let response = await $axios.post('administrator/themes')
			return {
				activated: response.data.default,
				themes: response.data.themes
			}
		},

		data: function() {
			return {
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_themes'),
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
			activate(theme) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'edit', 'themes')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/themes/options/activate', {
					theme: theme
				})
				.then(response => {
					this.activated = theme
					this.$toasted.show(this.$t('t_toasted_theme_activated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
				.catch(error => {
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

<style>
	.small-card-items {
	     display: -webkit-box;
	     display: -ms-flexbox;
	     display: flex;
	     -ms-flex-wrap: wrap;
	     flex-wrap: wrap;
	     padding: 25px 3px 10px 20px;
	}
	 
	.small-card-item {
	     width: 100%;
	     margin-bottom: 27px;
	     background-color: #FFF;
	     box-shadow: 6px 11px 41px -28px #a99de7;
	     -webkit-box-shadow: 6px 11px 41px -28px #a99de7;
	     -moz-box-shadow: 6px 11px 41px -28px #a99de7;
	     -ms-box-shadow: 6px 11px 41px -28px #a99de7;
	     border-radius: 0.625rem;
	     border: none;
	}
	 
	.small-card-item.is-active .small-card-item-overlay {
	     display: -webkit-box;
	     display: -ms-flexbox;
	     display: flex;
	}
	 
	 .small-card-item.is-selected {
	     -webkit-box-shadow: inset 0 1px 0 0 #0094f2, inset -1px 0 0 #0094f2, inset 0 -1px 0 0 #0094f2, inset 1px 0 0 #0094f2;
	     box-shadow: inset 0 1px 0 0 #0094f2, inset -1px 0 0 #0094f2, inset 0 -1px 0 0 #0094f2, inset 1px 0 0 #0094f2;
	 }
	 
	 .small-card-item.is-selected .small-card-item-info {
	     border-color: transparent;
	 }
	 
	 .small-card-item-preview {
	     position: relative;
	     max-height: 80px;
	     overflow: hidden;
	     height: 80px;
	 }

	.small-card-item-preview .v-image__image--cover {
		border-radius: 8px 8px 0px 0 !important;
	}
	 
	 .small-card-item-preview img {
	     border-top-left-radius: 0.625rem;
	     border-top-right-radius: 0.625rem;
	 }
	 
	 .small-card-item-preview:hover .small-card-item-overlay {
	     display: -webkit-box;
	     display: -ms-flexbox;
	     display: flex;
	 }
	 
	 .small-card-item-info {
	     padding: 15px 18px;
	     border-top: none;
	     font-size: 15px;
	 }
	 
	 .small-card-item-name {
	     display: block;
	     font-size: 14px;
	     font-weight: 500;
	     text-transform: uppercase;
	     color: #000 !important;
	     font-family: 'Roboto', 'Noto Kufi Arabic', sans-serif;
	     letter-spacing: 1px;
	 }
	 
	 .small-card-item-date {
	     color: #939daa;
	     font-family: 'Hind', 'Noto Kufi Arabic', sans-serif;
	     font-size: 10px;
	     text-transform: uppercase;
	     letter-spacing: 2px;
	 }
	
	.v-application--is-rtl .small-card-item-date {
		letter-spacing: 0
	}
	 
	 .small-card-item-overlay {
	     position: absolute;
	     display: none;
	     top: 0;
	     left: 0;
	     width: 100%;
	     height: 100%;
	     -webkit-box-align: center;
	     -ms-flex-align: center;
	     align-items: center;
	     -webkit-box-pack: center;
	     -ms-flex-pack: center;
	     justify-content: center;
	     font-size: 44px;
	     color: #fff;
	     background-color: rgba(0, 148, 242, 0.5);
	     border-top-left-radius: 0.625rem;
	     border-top-right-radius: 0.625rem;
	 }
	 
	 .small-card-item-control {
	     opacity: .85;
	     background-color: #FFF;
	     border-radius: 3px;
	     color: #4080ff;
	     padding: 2px 10px;
	     font-size: 28px;
	     display: block;
	 }
	 
	 .small-card-item-control:hover {
	     opacity: 1;
	 }
	 
	 .small-card-item-control:last-child {
	     margin-right: 0;
	 }
	 
	 .p-documents__load-more {
	     padding: 8px 35px;
	     margin-bottom: 10px;
	 }
	 
	 @media only screen and (max-width: 997px) {
	     .small-card-item {
	         width: calc(33.33% - 17px);
	     }
	 }
	 
	@media only screen and (max-width: 639px) {
	     .small-card-item {
	         width: 100%;
	     }
	}
</style>