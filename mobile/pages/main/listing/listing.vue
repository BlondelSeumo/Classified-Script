<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading.popup" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Bottom actions -->
		<client-only>
			<v-bottom-navigation grow v-model="action" :input-value="action" fixed app height="60px" class="deal-bottom-actions">
				<v-btn color="green accent-4" text :value="action" class="py-2 v-btn--active" v-if="listing.user.phone" :href="`tel:${listing.user.phone}`">
					<span class="text-uppercase font-weight-medium caption">{{ $t('t_phone') }}</span>
					<v-icon size="20px" class="pb-1">mdi-phone</v-icon>
				</v-btn>
				<v-btn color="blue lighten-1" text :value="action" class="py-2 v-btn--active" @click="dialog.contact = true" v-if="$auth.loggedIn && $auth.user.id !== listing.user.id && $megate.allow($auth.user, 'send', 'messages')">
					<span class="text-uppercase font-weight-medium caption">{{ $t('t_contact_me') }}</span>
					<v-icon size="20px" class="pb-1">mdi-message</v-icon>
				</v-btn>
				<v-btn color="#ff9800" text :value="action" class="py-2 v-btn--active" @click="dialog.share = true">
					<span class="text-uppercase font-weight-medium caption">{{ $t('t_share') }}</span>
					<v-icon size="20px" class="pb-1">mdi-open-in-app</v-icon>
				</v-btn>
				<v-btn color="error" text :value="action" class="py-2 v-btn--active" v-if="$auth.loggedIn && $megate.allow($auth.user, 'deals', 'reports')" @click.prevent="reportDeal">
					<span class="text-uppercase font-weight-medium caption">{{ $t('t_report') }}</span>
					<v-icon size="20px" class="pb-1">mdi-alert-octagon</v-icon>
				</v-btn>
				<v-btn :color="$vuetify.theme.dark ? '#ffeb3b' : '#795548'" text :value="action" class="py-2 v-btn--active" v-if="$auth.loggedIn && $megate.allow($auth.user, 'make', 'offers')" @click="dialog.offer = true">
					<span class="text-uppercase font-weight-medium caption">{{ $t('t_send_an_offer') }}</span>
					<v-icon size="20px" class="pb-1">mdi-tag</v-icon>
				</v-btn>
			</v-bottom-navigation>
		</client-only>

		<!-- Phone number -->
		<v-dialog max-width="290" v-model="dialog.phone">
			<v-card>
				<v-card-text class="text-center pa-4">
					<h1 class="font-weight-black headline">{{ listing.user.phone }}</h1>
				</v-card-text>
			</v-card>
		</v-dialog>

		<!-- Send offer -->
		<v-dialog v-model="dialog.offer" persistent max-width="400px" v-if="$auth.loggedIn && $megate.allow($auth.user, 'make', 'offers')">
			<v-card>
				<v-card-title>
					<span class="title font-weight-black text-uppercase pb-6">{{ $t('t_send_an_offer') }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>
							<!-- Offer price -->
							 <v-flex xs12>
								<v-text-field
									v-model="offerForm.price"
									color="grey lighten-1"
									:label="$t('t_your_price')"
									:placeholder="$t('t_enter_your_price')"
									type="number"
									persistent-hint
									></v-text-field>
							</v-flex>
							<!-- Currency -->
							<v-flex xs12>
								<v-autocomplete
									v-model="offerForm.currency"
									autocomplete="off"
									color="grey lighten-1"
									:items="currencies"
									item-text="name"
									item-value="code"
									:placeholder="$t('t_enter_currency')"
									:label="$t('t_currency')"
									dense
								></v-autocomplete>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" text @click="dialog.offer = false">{{ $t('t_cancel') }}</v-btn>
					<v-btn color="blue darken-1" text @click.prevent="sendOffer()">{{ $t('t_send_offer') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Share-->
		<v-dialog width="400" v-model="dialog.share" persistent>
			<v-card>
				<v-card-title>
					<span class="title font-weight-bold">{{ $t('t_share') }}</span>
					<v-spacer></v-spacer>
					<v-btn
						class="mx-0"
						icon
						@click="dialog.share = false"
						>
						<v-icon class="mdi mdi-close"></v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="text-center">
					<qrcode :value="$home('listing/' + listing.unique_slug)" :options="{ width: 100, margin: 0, color: {dark: $vuetify.theme.dark ? '#424242' : '#4b575f'} }"></qrcode>
					<v-layout align-center justify-space-around class="my-5">
						<v-icon @click="share('facebook')" color="#3b5999">mdi-facebook</v-icon>

						<v-icon @click="share('twitter')" color="#55acee">mdi-twitter</v-icon>

						<v-icon @click="share('pinterest')" color="#bd081c">mdi-pinterest</v-icon>

						<v-icon @click="share('linkedin')" color="#0077B5">mdi-linkedin</v-icon>

						<v-icon @click="share('vk')" color="#4c6c91">mdi-vk</v-icon>

						<v-icon @click="share('reddit')" color="#ff4500">mdi-reddit</v-icon>

						<v-icon @click="share('whatsapp')" v-if="mobile" color="#25d366">mdi-whatsapp</v-icon>
					</v-layout>
					<v-text-field
						ref="link"
						:label="copied ? $t('t_link_copied') : $t('t_click_to_copy_link')"
						:placeholder="$t('t_click_to_copy_link')"
						class="pa-3"
						readonly
						:value="$home('a/' + listing.short_id)"
						@click="copy"
						color="grey lighten-1"
						></v-text-field>
				</v-card-text>
			</v-card>
		</v-dialog>

		<!-- Contact -->
		<v-dialog v-model="dialog.contact" persistent max-width="450px" v-if="$auth.loggedIn && $auth.user.id !== listing.user.id && $megate.allow($auth.user, 'send', 'messages')">
			
			<v-card class="px-4 py-3">
				<v-card-title>
					<span class="title text-uppercase font-weight-black">{{ $t('t_contact_user', {user: listing.user.username}) }}</span>
				</v-card-title>
				<v-card-text >
					<v-container grid-list-md class="px-0">
						<v-layout wrap>

							<!-- Message -->
							<v-flex xs12>
								<v-textarea
									v-model="contactForm.message"
									color="grey lighten-1"
									:label="$t('t_message')"
									:placeholder="$t('t_enter_message')"
									counter="500"
									maxlength="500"
									:rules="errors.contact.message"
									:error="errors.contact.message ? true : false"
									>
								</v-textarea>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="px-6">
					<v-btn v-if="$auth.loggedIn" text color="#ff6b6b" class="px-4" depressed :disabled="loading.chat" :loading="loading.chat" @click="chat(listing.user.username)">{{ $t('t_live_chat') }}</v-btn>
					<v-spacer></v-spacer>
					<v-btn color="blue-grey darken-4" text @click="dialog.contact = false">{{ $t('t_cancel') }}</v-btn>
					<v-btn color="blue-grey darken-4" text @click="contact()" :loading="loading.contact" :disabled="loading.contact">{{ $t('t_send') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-content>
      		<v-container fluid grid-list-xl>
      			<v-layout wrap>

					<v-flex xs12 v-if="$auth.loggedIn && $isAdmin() && listing.isPending">
						<v-banner two-line>
							<v-avatar slot="icon" color="#2196f3" size="40">
								<v-icon color="white">mdi-information-variant</v-icon>
							</v-avatar>
							{{ $t('t_this_deal_pending_active') }}
							<template v-slot:actions>
								<v-btn text color="#2196f3" to="/administrator/pending/deals" nuxt>{{ $t('t_pending_deal') }}</v-btn>
							</template>
						</v-banner>
					</v-flex>
					<v-flex xs12 v-else-if="$auth.loggedIn && $isModerator() && listing.isPending && $mogate.allow($auth.user, 'approve', 'deals')">
						<v-banner two-line>
							<v-avatar slot="icon" color="#2196f3" size="40">
								<v-icon color="white">mdi-information-variant</v-icon>
							</v-avatar>
							{{ $t('t_this_deal_pending_active') }}
							<template v-slot:actions>
								<v-btn text color="#2196f3" to="/moderator/pending/deals" nuxt>{{ $t('t_pending_deal') }}</v-btn>
							</template>
						</v-banner>
					</v-flex>

					<!-- Ad Photos -->
					<v-flex xs12>
      					<v-card class="m-card mb-3">
							<div class="deal-slider" v-if="listing.photosNumber !== 0" v-swiper:mySwiper="slider">
								<div class="swiper-wrapper">
									<div class="swiper-slide" v-for="(i, index) in listing.photosNumber" :key="index">
									<v-img :aspect-ratio="16/9" height="300px" :src="sliderPicture(i)">
										<v-layout align-content-center justify-center fill-height wrap class="deal-lightbox ma-0">
								        </v-layout>
									</v-img>
									</div>
								</div>
								<div class="swiper-pagination" slot="pagination"></div>
							</div>
							<v-img v-else height="300px" :src="$homeApi('storage/app/uploads/default/classifieds/no-image.png')"></v-img>
						</v-card>
      				</v-flex>

					<!-- Advertisement -->
					<v-flex xs12 v-if="advertisements.top_related">
      					<v-card class="advertisement m-card pa-0 text-center" v-html="advertisements.top_related"></v-card>
					</v-flex>

					<!-- Ad details -->
      				<v-flex xs12>
      					<v-card class="m-card mt-3">
							<v-tabs grow icons-and-text>
								<v-tab href="#deal-description">
									<span class="caption font-weight-medium pt-2">{{ $t('t_details') }}</span>
									<v-icon size="20px">mdi-tag</v-icon>
								</v-tab>
								<v-tab href="#deal-seller">
									<span class="caption font-weight-medium pt-2">{{ $t('t_seller') }}</span>
									<v-icon size="20px">mdi-account-card-details</v-icon>
								</v-tab>
								<v-tab href="#deal-location">
									<span class="caption font-weight-medium pt-2">{{ $t('t_location') }}</span>
									<v-icon size="20px">mdi-map-marker</v-icon>
								</v-tab>
								<v-tab href="#deal-video" v-if="listing.video">
									<span class="caption font-weight-medium pt-2">{{ $t('t_watch_video') }}</span>
									<v-icon size="20px">mdi-youtube</v-icon>
								</v-tab>

								<!-- Description -->
								<v-tab-item value="deal-description">
									<v-card flat>
										<!-- Title && price -->
										<v-card-text class="py-4 text-center">
											<h4>{{ listing.title }}</h4>
											<v-card color="#4a4a4a" flat class="text-center pa-3 mt-3" v-if="listing.price">
												<div style="font-size:18px;text-transform:uppercase;font-family:Hind, 'Noto Kufi Arabic', sans-serif;font-weight:700;letter-spacing:1px;color:#fff;">
													{{ $getCurrency(listing.price, listing.currency, listing.locale) }}
												</div>
											</v-card>
										</v-card-text>

										<!-- Custom fields -->
									  	<v-card-text class="px-4 pt-4" v-if="fields.length !== 0">

											<!-- Dropdowns / Radio -->
											<v-list subheader two-line>
												<v-container grid-list-xl fluid fill-height class="my-3 mx-0 pa-0">
													<v-layout wrap>

														<template v-for="(item, index) in fields">
															<v-flex xs12 :key="index" v-if="item.field.type === 'dropdown' || item.field.type === 'radio'">
																<v-list-item :style="$vuetify.theme.dark ? 'background-color: #353535' : 'background-color: #fafafa'">
																	<v-list-item-action>
																		<v-icon :color="$vuetify.theme.dark ? '#515151' : '#8c8c8c'">mdi-label</v-icon>
																	</v-list-item-action>
																	<v-list-item-content>
																		<v-list-item-title style="line-height: 20px;font-size:12px;text-transform:uppercase;font-family:'Hind', 'Noto Kufi Arabic';font-weight:500;letter-spacing:1px;color:#919191;">{{ item.field.name }}</v-list-item-title>
																		<v-list-item-subtitle style="$vuetify.theme.dark ? 'color: #8e8e8e' : 'color: #171717'">{{ item.options }}</v-list-item-subtitle>
																	</v-list-item-content>
																</v-list-item>
															</v-flex>
														</template>

													</v-layout>
												</v-container>
											</v-list>

											<!-- Checkboxes -->
											<v-container grid-list-xl fluid fill-height class="my-3 mx-0 pa-0">
												<v-layout wrap>
													
													<template v-for="(item, index) in fields">
														<v-flex xs12 :key="index" v-if="item.field.type === 'checkbox'">
															<p class="mt-3">{{ item.field.name }}</p>
															<v-checkbox
																v-for="(option, index) in JSON.parse(item.options)"
																:key="index"
																v-model="checked"
																:label="option"
																:value="true"
																disabled
																hide-details
															></v-checkbox>
														</v-flex>
													</template>

												</v-layout>
											</v-container>

										</v-card-text>

									  	<!-- Description -->
									  	<v-card-text class="py-4 " v-if="listing.description">
											  <client-only>
											  	<truncate action-class="text-center caption text-uppercase" :clamp="$t('t_show_more')" :length="280" :less="$t('t_show_less')" type="html" :text="listing.description"></truncate>
											  </client-only>
										  </v-card-text>
									</v-card>
								</v-tab-item>

								<!-- Seller -->
								<v-tab-item value="deal-seller">
									<v-card flat>
									  	<v-card-text class="text-center" id="listing-profile">
			      							<v-img :aspect-ratio="16/9" height="180px" class="text-center">
												<v-container fill-height class="pa-0">
													<v-layout align-center justify-center wrap fill-height>
														<v-flex xs12>
															<v-avatar size="80" color="grey lighten-4" class="my-0">
													          	<img :src="userAvatar">
													        </v-avatar>
													        <h1 class="text-uppercase mt-3" style="font-size:14px!important;font-family:Hind, 'Noto Kufi Arabic', sans-serif;font-weight:500!important;letter-spacing:1px;">{{ listing.user.username }}</h1>
													        <span style="font-size:12px;font-family:Hind, 'Noto Kufi Arabic', sans-serif;color:#a1a1a1;">Member since {{ $dateSince(listing.user.created_at) }}</span>
													    </v-flex>
												    </v-layout>
												</v-container>
											</v-img>
			      						</v-card-text>
									</v-card>
								</v-tab-item>

								<!-- Location -->
								<v-tab-item value="deal-location">
									<v-card flat>
									  	<v-card-text class="pt-4">
									  		<iframe
									  			v-if="listing.latitude && listing.longitude"
											  	width="100%" 
											  	height="250" 
											  	frameborder="0" 
											  	scrolling="no" 
											  	marginheight="0" 
											  	marginwidth="0" 
											  	:src="'https://maps.google.com/maps?q=' + listing.latitude + ',' + listing.longitude + '&hl=es;z=14&amp;output=embed'"
											 	>
											 	</iframe>
									  	</v-card-text>
									</v-card>
								</v-tab-item>

								<!-- Video -->
								<v-tab-item value="deal-video" v-if="listing.video">
									<v-card flat>
									  	<v-card-text class="pt-4">
									  		<iframe width="100%" height="300" :src="'https://www.youtube.com/embed/' + $getYoutubeId(listing.video)" style="display: block;border-width: 0"></iframe>
									  	</v-card-text>
									</v-card>
								</v-tab-item>

							</v-tabs>
						</v-card>
      				</v-flex>

					<!-- Advertisement -->
					<v-flex xs12 v-if="advertisements.bottom_related">
      					<v-card class="my-3 advertisement m-card pa-0 text-xs-center" v-html="advertisements.bottom_related"></v-card>
					</v-flex>

					<!-- Related deals -->
      				<v-flex xs12 v-if="related.length !== 0">
      					<v-container fluid grid-list-xl class="pa-0">
							<v-layout wrap>
								<v-flex xs12 class="mb-2">
									<div class="section-title">
										<h3>{{ $t('t_related_deals') }}</h3>
										<div class="more">
											<nuxt-link to="/browse/deals">{{ $t('t_see_more') }}</nuxt-link>
										</div>
									</div>
								</v-flex>

								<div v-if="related.length !== 0" v-swiper:mySwiper="relatedSlider">
									<div class="swiper-wrapper">
										<div class="swiper-slide" v-for="(deal, index) in related" :key="index">
											<v-flex xs12 class="mb-5">
												<v-card class="m-card ad-box" flat>
													<v-img cover height="100px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="preview(deal)">
														<v-container fill-height class="pa-0">
															<v-layout align-center justify-center>
																<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
															</v-layout>
														</v-container>
													</v-img>
													<v-card-title class="pb-0">
														<div class="text-truncate">
															<nuxt-link class="deal-title" :to="'/listing/' + deal.unique_slug">{{ deal.title }}</nuxt-link>
														</div>
													</v-card-title>
													<v-card-actions>
														<v-list-item class="grow deal-avatar">
															<v-list-item-avatar size="25px">
																<v-img :src="avatar(deal.user.avatar)"></v-img>
															</v-list-item-avatar>
															<v-layout align-center justify-end style="margin-bottom: 14px">
																<span class="deal-price" v-if="deal.price && deal.currency && deal.locale">{{ $getCurrency(deal.price, deal.currency, deal.locale, deal.id) }}</span>
															</v-layout>
														</v-list-item>
													</v-card-actions>
												</v-card>
											</v-flex>
										</div>
									</div>
								    <div class="swiper-button-prev" slot="button-prev">
										<i class="mdi mdi-chevron-left"></i>
									</div>
									<div class="swiper-button-next" slot="button-next">
										<i class="mdi mdi-chevron-right"></i>
									</div>
								</div>
							</v-layout>
						</v-container>
      				</v-flex>

					<!-- Comments -->
					<v-flex xs12>
						<!-- comments -->
      					<v-card class="m-card py-5" text>
					        <div class="py-4">
					        	<ul id="comments" class="px-5">

									<li v-for="(comment, index) in comments[0]" :key="index">
									    <div class="avatar-container">
									      	<div>
									        	<img v-if="comment.user.avatar === null" :src="$homeApi('storage/app/uploads/default/avatar/noavatar.png')" class="avatar" :alt="comment.user.username" />
									        	<img v-else :src="$homeApi('storage/app/' + comment.user.avatar)" class="avatar" :alt="comment.user.username" />
									      	</div>
									    </div>
									    <div class="post-container">
									      	<a :class="comment.user_id === listing.user_id ? 'author_name author_owner' : 'author_name'">
									        	{{ comment.user.username }}
									      	</a>
									      
									      	<span class="bullet" aria-hidden="true">•</span>
									
									      	<span class="cm-action">{{ $ago(comment.created_at) }}</span>
									      	<span v-if="$auth.loggedIn && $auth.user.id === comment.user.id">
										      	<span class="bullet" aria-hidden="true" v-if="comment.isPending && $auth.user.id === comment.user_id">•</span>
										      	<span class="cm-action cursor-pointer warning--text" v-if="comment.isPending && $auth.user.id === comment.user_id">{{ $t('t_pending') }}</span>
									      	</span>
									      	<span v-if="$auth.loggedIn && $auth.user.id !== comment.user_id && $megate.allow($auth.user, 'comments', 'reports')">
									      		<span class="bullet" aria-hidden="true" v-if="$auth.loggedIn">•</span>
									      		<span class="cm-action cursor-pointer" @click="reportComment(comment)">{{ $t('t_report') }}</span>
									      	</span>
									
									      	<p v-html="comment.comment"></p>

									    </div>   
									    <v-divider class="mb-3 mt-5" :color="$vuetify.theme.dark ? '#575757' : '#f3f3f3'" v-if="index != Object.keys(comments[0]).length - 1"></v-divider> 
									</li>
									<client-only>
										<infinite-loading spinner="spiral" @distance="1" @infinite="loadComments" force-use-infinite-wrapper>
											<span slot="no-more"></span>
											<span slot="no-results"></span>
											<v-alert v-if="comments.length === 0" slot="no-results" text dense :color="$vuetify.theme.dark ? '#ff5454' : 'teal'" :border="$vuetify.rtl ? 'left' : 'right'">
												{{ $t('t_no_comment_now') }}
											</v-alert>
										</infinite-loading>
									</client-only>
								</ul>
								<!-- Post new comment -->
								<div v-if="$auth.loggedIn && $megate.allow($auth.user, 'write', 'comments')" class="mt-6 px-5 comment-textarea" style="width: 100%">
									<v-textarea
										v-model="forms.comment.comment"
										:hint="$t('t_spam_comments_will_remove')"
										persistent-hint
										solo
										text
										:placeholder="$t('t_leave_comment')"
										counter="500"
										maxlength="500"
										:rules="errors.comment.comment"
										:error="errors.comment.comment ? true : false"
										>
									</v-textarea>
									<v-btn class="mt-3" depressed @click="comment" block :loading="loading.comment" :disabled="loading.comment || !forms.comment.comment">{{ $t('t_post_comment') }}</v-btn>
								</div>

								<!-- Not logged in -->
								<div v-else-if="!$auth.loggedIn" class="mt-6 px-5" style="width: 100%">
									<v-alert text dense color="teal" :border="$vuetify.rtl ? 'left' : 'right'">
										<v-row align="center">
											<v-col class="grow">{{ $t('t_login_to_comment') }}</v-col>
											<v-col class="shrink">
												<v-btn depressed color="#27a69a" dark to="/auth/login" nuxt>{{ $t('t_login') }}</v-btn>
											</v-col>
										</v-row>
									</v-alert>
								</div>
					        </div>
      					</v-card>
					</v-flex>

					<!-- Post new -->
				  	<v-flex xs12 class="text-center my-5" id="scrollTarget">
      					<div class="subheading font-weight-black text-uppercase mb-3 mt-4">{{ $t('t_do_have_something') }}</div>
      					<div class="body-1 font-weight-light mb-2">{{ $t('t_do_have_something_para') }}</div>
      					<v-btn color="green accent-4" large :to="$auth.loggedIn ? '/create' : '/auth/register'" dark depressed block class="mt-4">
      						{{ $t('t_create_deal') }}
      					</v-btn>
      				</v-flex>

      			</v-layout>
      		</v-container>
      	</v-content>

    </v-app>
</template>

<script>
	import InfiniteLoading from 'vue-infinite-loading'

	export default {
		middleware: false,

		layout: 'default/main',

	  	components: {
	  		'infinite-loading': InfiniteLoading
	  	},

		async asyncData ({ $axios, params }) {
			let response = await $axios.post(`listing/${params.slug}`)
			return {
				listing: response.data.listing,
				fields: response.data.fields,
				currencies: response.data.currencies,
				stats: {
					visits: response.data.statistics_count,
					comments: response.data.comments_count,
				},
				related: response.data.related,
				advertisements: {
					top_related: response.data.advertisements.ad_deal_top_related,
					bottom_related: response.data.advertisements.ad_deal_bottom_related,
					sidebar: response.data.advertisements.ad_deal_sidebar,
				},
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
	  				image: response.data.seo.image
				},
				structuredData: {
			        "@context": "http://schema.org",
			        "@type": "Product",
			        "name": response.data.listing.title,
			        "image": response.data.seo.image,
			        "description": response.data.seo.description,
			        "aggregateRating": {
					    "@type": "AggregateRating",
					    "ratingValue": "5",
					    "reviewCount": "1000"
					},
					"offers": {
					    "@type": "Offer",
					    "priceCurrency": response.data.listing.currency,
					    "price": response.data.listing.price,
					    "itemCondition": "http://schema.org/UsedCondition",
					    "availability": "http://schema.org/InStock",
					    "seller": {
					      "@type": "Organization",
					      "name": response.data.listing.user.username
					    }
					}
			    }
			}
		},

	  	data: function() {
			return {
				action: true,
				forms: {
					comment: {
						comment: ''
					}
				},
				stats: {
					visits: 0,
					comments: 0
				},
				swiperOption: {
					slidesPerGroup: 4,
          			loopFillGroupWithBlank: true,
			        slidesPerView: 3,
			        spaceBetween: 20,
		          	//loop: true,
		          	breakpoints: {
			            1024: {
			              slidesPerView: 3,
			              spaceBetween: 20
			            },
			            768: {
			              slidesPerView: 2,
			              spaceBetween: 30
			            },
			            640: {
			              slidesPerView: 2,
			              spaceBetween: 20
			            },
			            320: {
			              slidesPerView: 1,
			              spaceBetween: 10
			            }
		          	},
		          	navigation: {
			            nextEl: '.swiper-button-next',
			            prevEl: '.swiper-button-prev'
					},
					pagination: {
						el: '.swiper-pagination',
						clickable: true
					},
			        autoplay: {
					    delay: 2500,
					    disableOnInteraction: true
				  	}
				},
				slider: {
		          	pagination: {
		            	el: '.swiper-pagination',
		            	clickable: true
		          	},
				},
				relatedSlider: {
					slidesPerGroup: 4,
	      			loopFillGroupWithBlank: true,
			        slidesPerView: 4,
			        spaceBetween: 20,
		          	//loop: true,
		          	breakpoints: {
			            1024: {
			              slidesPerView: 4,
			              spaceBetween: 40
			            },
			            768: {
			              slidesPerView: 3,
			              spaceBetween: 30
			            },
			            640: {
			              slidesPerView: 2,
			              spaceBetween: 20
			            },
			            320: {
			              slidesPerView: 1,
			              spaceBetween: 10
			            }
		          	},
		          	navigation: {
			            nextEl: '.swiper-button-next',
			            prevEl: '.swiper-button-prev'
			        },
			        autoplay: {
					    delay: 2500,
					    disableOnInteraction: true
				  	}
		        },
				shareLinks: [
					{icon: "mdi-facebook", color: "#3B5998", title: "Share on Facebook", href: "https://www.facebook.com/sharer.php?u=" + this.getFullPath()},
					{icon: "mdi-twitter", color: "#1DA1F2", title: "Share on Twitter", href: "https://twitter.com/share?url=" + this.getFullPath()},
					{icon: "mdi-google", color: "#DC4E41", title: "Share on Google+", href: "https://plus.google.com/share?url=" + this.getFullPath()},
					{icon: "mdi-linkedin", color: "#0077B5", title: "Share on LinkedIn", href: "https://www.linkedin.com/shareArticle?mini=true&amp;url=" + this.getFullPath()},
					//{icon: "mdi-pinterest", color: "#BD081C", title: "Share on Pinterest", href: ""},
					{icon: "mdi-reddit", color: "#FF4500", title: "Share on Reddit", href: "https://reddit.com/submit?url=" + this.getFullPath()},
					{icon: "mdi-tumblr", color: "#36465D", title: "Share on Tumblr", href: "https://www.tumblr.com/share/link?url=" + this.getFullPath()},
					{icon: "mdi-vk", color: "#6383A8", title: "Share on Vk", href: "https://vkontakte.ru/share.php?url=" + this.getFullPath()}
				],
				contactForm: {
					message: '',
				},
				commentForm: {
					comment: ''
				},
				offerForm: {
					price: null,
					currency: null
				},
				dialog: {
					phone: false,
					contact: false,
					translate: false,
					video: false,
					offer: false,
					share: false
				},
				loading: {
					listing: true,
					contact: false,
					comment: false,
					chat: false,
					popup: false
				},
				errors: {
					contact: [],
					comment: []
				},
				fields: [],
				related: [],
				comments: [],
				advertisements: {
					top_related: null,
					bottom_related: null,
					sidebar: null
				},
				commentsPage: 1,
				commentsHasNextPage: true,
				phoneDialog: false,
				contactDialog: false,
				shareDialog: false,
				translateDialog: false,
				copied: false,
				pageLoaded: false,
				checked: true
			}
		},

		head () {
			return {
				title: this.listing.title,
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.listing.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home(`listing/${this.listing.unique_slug}`) },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.listing.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			],
      			__dangerouslyDisableSanitizers: ['script'],
      			script: [{ innerHTML: JSON.stringify(this.structuredData), type: 'application/ld+json' }]
			}
		},

		computed: {
			breadcrumbs: function() {
				return [
			      	{
			        	text: this.$t('t_home'),
			        	disabled: false,
			        	to: '/'
			      	},
			      	{
			        	text: this.listing.category.title,
			        	disabled: false,
			        	to: '/category/' + this.listing.category.slug
			      	},
			      	{
			        	text: this.listing.title,
			        	disabled: true,
			        	to: '/listing/' + this.listing.unique_slug
			      	}
			    ]
			},

			firstImage: function() {
				if (this.listing.photosNumber !== 0) {
					return this.$homeApi('storage/app/uploads/classifieds/' + this.listing.unique_id + '/preview_0.jpg');
				}else{
					return this.$homeApi('storage/app/uploads/default/classifieds/no-image.jpg');
				}
			},

			userAvatar: function() {
				if (this.listing.user.avatar !== null) {
					return this.$homeApi('storage/app/' + this.listing.user.avatar)
				}else{
					return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
				}
			},

			address: function() {
				return (this.listing.city !== null ? this.listing.city.name + ' - ' : ' ') + (this.listing.state !== null ? this.listing.state.name + ' - ' : ' ') + (this.listing.country !== null ? this.listing.country.name : ' ')
			},

			mobile: function() {
				return this.$device.isMobileOrTablet ? true : false
			}
		},

		methods: {
			sliderPicture: function(i) {
				let index = i - 1
				return this.$homeApi('storage/app/uploads/classifieds/' + this.listing.unique_id + '/preview_' + index + '.jpg?v=' + Math.random())
			},

			preview: function(deal) {
				if (deal.photosNumber === 0) {
					return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png')
				}else{
					return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg')
				}
			},

			avatar: function(avatar) {
				if (avatar === null) {
					return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
				}else{
					return this.$homeApi('storage/app/' + avatar)
				}
			},

			contact: function() {
				if (!this.$auth.loggedIn && !this.$megate.allow(this.$auth.user, 'send', 'messages')) {return}
				this.loading.contact = true
				this.$axios
				.post('listing/contact', {
					id: this.listing.unique_id,
					message: this.contactForm.message
				})
				.then(response => {
					this.contactForm = {
						message: '',
					}
					this.loading.contact = false
					this.dialog.contact  = false
					this.$toasted.show(this.$t('t_toasted_message_sent'))
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors.contact  = error.response.data.errors
					}
					if (error.response.data === 'not_allowed') {
						this.dialog.contact = false
						this.$toasted.error(this.$t('t_toasted_yourself_message'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}else{
						this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}
					this.loading.contact = false
				})
			},

			chat: function(username) {
				this.loading.chat = true
				this.$axios
				.post('chat/options/start', {
					username: username
				})
				.then(response => {
					this.$router.push(`/chat?conversation=${response.data}`)
				})
				.catch(error => {
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading.chat = false
				})
			},

			copy: function() {
        		const markup = this.$refs.link
        		markup.focus()
        		document.execCommand('selectAll', false, null)
        		this.copied = document.execCommand('copy')
			},

			comment: function() {
				if (this.forms.comment.comment !== '') {
					this.loading.comment = true
					this.$axios
					.post('comments/create/deals', {
						comment: this.forms.comment.comment,
						listing_id: this.listing.unique_id
					})
					.then(response => {
						this.comments.push([])
						this.comments[0].push(response.data)
						this.forms.comment.comment = ''
						this.loading.comment       = false
						this.$toasted.show(this.$t('t_toasted_comment_added'))
					})
					.catch(error => {
						if (error.response.data.errors) {
							this.errors.comment  = error.response.data.errors
						}
						this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
						this.loading.comment = false
					})
				}
			},

			loadComments: function($state) {
				this.$axios
				.get('listing/' + this.listing.unique_id + '/comments?page=' + this.commentsPage)
				.then(response => {
					if (response.data.data.length !== 0) {
						if (!this.commentsHasNextPage) {
							$state.complete();
							return
						}
						this.comments.push(response.data.data)
		                $state.loaded()
						this.commentsPage      = this.commentsPage + 1
						if (response.data.next_page_url === null) {
							this.commentsHasNextPage = false
						}
					}else{
						$state.complete();
						this.commentsHasNextPage = false
						return
					}
				})
				.catch(error => {
					console.log(error)
				})
			},

			getFullPath: function() {
				return this.$home(this.$router.currentRoute.fullPath)
			},

			reportComment: function(comment) {
				if (!this.$auth.loggedIn && !this.$megate.allow(this.$auth.user, 'comments', 'reports')) {return}
				this.loading.popup = true
				this.$axios
				.post('report/comment', {
					id: comment.id
				})
				.then(response => {
					this.$toasted.show(this.$t('t_toasted_comment_reported'))
					this.loading.popup = false
				})
				.catch(error => {
					if (error.response.data === 'error_yourself') {
						this.$toasted.error(this.$t('t_toasted_yourself_cm_report'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}else if (error.response.data === 'error_reported') {
						this.$toasted.error(this.$t('t_toasted_comment_already_reported'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}else {
						this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}
					this.loading.popup = false
				})
			},

			reportDeal: function() {
				if (!this.$auth.loggedIn) {
					this.$toasted.info(this.$t('t_toasted_login_to_report_deal'), {
						icon: 'info',
						action: {
					        text : this.$t('t_login'),
					        onClick : (e, toastObject) => {
					            this.$router.push(`/auth/login?redirect=/listing/${this.listing.unique_slug}`)
					        }
						},
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					return
				}else if (!this.$megate.allow(this.$auth.user, 'deals', 'reports')) {
					return
				}
				this.loading.popup = true
				this.$axios
				.post('report/deal', {
					id: this.listing.unique_id
				})
				.then(response => {
					this.$toasted.show(this.$t('t_toasted_deal_reported'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading.popup = false
				})
				.catch(error => {
					if (error.response.data === 'error_yourself') {
						this.$toasted.error(this.$t('t_toasted_no_your_deals_report'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}else if (error.response.data === 'error_reported') {
						this.$toasted.error(this.$t('t_toasted_deal_already_reported'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}else {
						this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}
					this.loading.popup = false
				})
			},

			share: function(to) {
				let link        = this.$home('listing/' + this.listing.unique_slug)
				let text        = this.listing.title
				let description = this.listing.description
				let media       = this.firstImage

				switch (to) {
					case 'facebook':
						var url = 'https://www.facebook.com/sharer.php?u=' + link
						window.open(url, '_blank').focus()
					    break;
					case 'twitter':
						var url = 'https://twitter.com/share?url=' + link
						window.open(url, '_blank').focus()
					    break;
					case 'pinterest':
						var url = 'https://pinterest.com/pin/create/button/?url=' + link + '&media=' + media + '&description=' + description
						window.open(url, '_blank').focus()
					    break;
					case 'linkedin':
						var url = 'https://www.linkedin.com/shareArticle?title=' + text + '&url=' + link
						window.open(url, '_blank').focus()
					    break;
					case 'vk':
						var url = 'https://vk.com/share.php?url=' + link
						window.open(url, '_blank').focus()
					    break;
					case 'reddit':
						var url = 'https://www.reddit.com/submit?url=' + link + '&title=' + text
						window.open(url, '_blank').focus()
					    break;
					case 'whatsapp':
						var url = 'https://api.whatsapp.com/send?text=' + link
						window.open(url, '_blank').focus()
					    break;
					default:
						//
				}
			},

			sendOffer: function() {
				if ((!this.$auth.loggedIn &&  this.$megate.allow(this.$auth.user, 'make', 'offers')) ||!this.offerForm.price) {
					return
				}
				this.loading.popup = true
				this.$axios
				.post('account/offers/options/send', {
					id: this.listing.unique_id,
					price: this.offerForm.price,
					currency: this.offerForm.currency
				})
				.then(response => {
					this.loading.popup = false
					this.dialog.offer  = false
					this.$toasted.show(this.$t('t_toasted_offer_made'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
				})
				.catch(error => {
					this.loading.popup = false
					this.dialog.offer  = false
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
				})
			},

			handleScroll () {
				let element  = document.getElementById('scrollTarget')
				let position = element.getBoundingClientRect().top + window.scrollY
				if (window.scrollY + 100 > position) {
					this.action = false
				}else{
					this.action = true
				}
			}
		},

		created () {
			if (process.browser) { 
				window.addEventListener('scroll', this.handleScroll);
			}
		},

		destroyed () {
			if (process.browser) { 
				window.removeEventListener('scroll', this.handleScroll);
			}
		}
	}
</script>

<style scoped>
	#listing-profile .v-dialog__container {
		width: 100%
	}
	.v-dialog__content input {
		cursor:pointer
	}
	.v-breadcrumbs__item {
	    color: #4a4848;
	    font-family: 'Hind', 'Noto Kufi Arabic', sans-serif;
	    font-weight: 500;
	}

	button.btn.btn--outline{
	  min-width: 65px !important;
	}

	div.dialog.dialog--active{
	  margin-right: 0px;
	  margin-left: 0px;
	}
	ul#comments {
	  	list-style-type: none;
	  	font-size: 15px;
	  	color: #a5b2b9;
	}

	ul#comments li {
		position: relative;
		padding-bottom: 20px;
	}
	ul#comments li:last-child {
		border-bottom: unset !important;
		background-color: red;
	}
	ul#comments li a { 
		color: #484848
	}
	#comments .v-input__slot {
	    border: 2px solid #f5f5f5;
	}
	#comments .avatar-container { 
		width: 60px; box-sizing: border-box; 
	}
	.v-application--is-rtl #comments .avatar-container {
		float: right;
	}
	#comments .avatar {
		width: 50px;
		height: 50px;
		border-radius: 100%;
		float: left;
		margin: 5px;
	}

	#comments .post-container { 
		padding-top: 3px; 
		margin-left: 70px;  
		box-sizing: border-box; 
	}
	.v-application--is-rtl #comments .post-container {
		margin-right: 65px;
	}
	#comments .post-container p {
		color: #565656;
		font-size: 13px;
		padding: 10px 0px;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
	.theme--dark #comments .post-container p {
		color: #fff;
	}
	#comments .author_owner {
		background-color: #ff6b6b !important;
		color: #fff !important;
	}
	#comments .author_name {
		display: inline-block;
	    font-weight: 500;
		background-color: #e4f1fe;
		color: #232323;
	    font-family: 'Hind', 'Noto Kufi Arabic',;
	    padding: 3px 12px 1.5px 12px;
	    font-size: 10px;
	    text-transform: uppercase;
	    border-radius: 100px;
	    letter-spacing: 1px;
	    margin-right: 5px;
	}
	.v-application--is-rtl #comments .author_name {
		margin-right: 0;
		margin-left: 5px
	}
	#comments .cm-action { 
	  	font-weight: 500;
	  	font-size: 12px;
	  	color: #a5b2b9;
	}
	#comments .bullet {
		padding: 0 5px;
		font-size: 11px;
		color: #adadad;
		line-height: 1.4;
		font-weight: 900;
	}
</style>

<style>
	.comment-textarea .v-input__slot {
		box-shadow: unset !important
	}
</style>