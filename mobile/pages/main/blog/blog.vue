<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap>

      				<v-flex xs12 class="my-3">
						<div class="text-center">
							<h1 class="display-1 text-uppercase font-weight-black mb-5">{{ $t('t_blog') }}</h1>
						</div>
					</v-flex>

					<v-flex xs6 v-for="(article, index) in articles" :key="index">
						<v-card class="m-card mb-3" flat>
							<v-img
								class="white--text"
								height="120px"
								:src="cover(article.cover)"
								gradient="to top right, rgba(10, 10, 10, 0.6), rgba(31, 31, 31, 0.73)"
								>
								<v-container fill-height fluid>
									<v-layout>
										<v-flex xs12 align-end d-flex>
											<span class="title">{{ article.title }}</span>
										</v-flex>
									</v-layout>
								</v-container>
							</v-img>
							<v-card-text style="height: 50px" class="px-3 py-4" v-html="article.content.substring(0, 50)"></v-card-text>
							<v-card-actions class="px-3 py-4">
								<v-spacer></v-spacer>
								<v-btn text class="blue--text" :to="`/blog/${article.slug}`">{{ $t('t_read_more') }}</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>

					<!-- Pagination -->
					<v-flex xs12>
						<div class="text-center pt-2">
					      	<pagination :data="articlesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
								<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
								<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
							</pagination>
					    </div>
					</v-flex>

				</v-layout>
      		</v-container>
      	</v-content>

    </v-app>
</template>

<script>
	export default {
		layout: 'default/main',

		async asyncData ({ $axios }) {
			let response = await $axios.get('blog')
			return {
				articles: response.data.articles.data,
				articlesData: response.data.articles,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
	  				image: response.data.seo.image
				}
			}
		},

		data: function() {
			return {
				loading: false
			}
		},

		head () {
			return {
				title: this.$t('t_blog'),
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.$t('t_blog')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home('blog') },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.$t('t_blog')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		methods: {
			getNextPage: function(page) {
				this.loading = true
				this.$axios
				.get(`blog?page=${page}`)
				.then(response => {
					this.articles     = response.data.articles.data
					this.articlesData = response.data.articles
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
				})
			},

			cover: function(cover) {
				if (cover === null) {
					return this.$homeApi('storage/app/uploads/default/article/no-image.png')
				}else{
					return this.$homeApi('storage/app/' + cover)
				}
			} 
		},
	}
</script>