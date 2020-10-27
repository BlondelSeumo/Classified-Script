<template>
	<v-app id="inspire">
		<v-content>
			<v-container grid-list-xl fluid>
				<section class="mt-5">
					<v-img :src="cover" height="200" gradient="to top right, rgba(10, 10, 10, 0.6), rgba(31, 31, 31, 0.73)">
						<v-layout
							column
							align-center
							justify-center
							class="white--text"
							fill-height
							>
							<h1 class="white--text mb-3 headline text-center">{{ article.title }}</h1>
							<div class="caption mb-4 text-center">{{ $t('t_posted') }} {{ $ago(article.created_at) }}</div>
							<v-btn
								class="white"
								light
								to="/blog"
								depressed
								>
								{{ $t('t_back_to_blog') }}
							</v-btn>
						</v-layout>
					</v-img>
				</section>

				<v-card class="m-card mt-4" flat>
					<v-card-text v-html="article.content"></v-card-text>
				</v-card>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/main',

		async asyncData ({ $axios, params }) {
			let response = await $axios.post(`blog/${params.id}`)
			return {
				article: response.data.article,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
	  				image: response.data.seo.image
				}
			}
		},

		head () {
			return {
				title: this.article.title,
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.article.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home(`blog/article/${this.article.title}`) },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.article.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		computed: {
			cover: function() {
				if (this.article.cover === null) {
					return this.$homeApi('storage/app/uploads/default/article/no-image.png')
				}else{
					return this.$homeApi('storage/app/' + this.article.cover)
				}
			}
		}
	}
</script>