<template>
	<v-app id="inspire">

		<v-content>
      		<v-container fluid grid-list-xl>
      			<v-layout wrap>

					<v-flex xs12>
						<div class="text-center">
							<h1 class="display-1 text-uppercase font-weight-black mb-5">{{ page.title }}</h1>
						</div>
					</v-flex>

					<v-flex xs12>
						<v-card class="m-card px-4 pt-4 pb-3">
							<v-card-text v-html="page.content"></v-card-text>
						</v-card>
					</v-flex>

				</v-layout>
      		</v-container>
      	</v-content>

    </v-app>
</template>

<script>
	export default {
		layout: 'default/main',

		async asyncData ({ $axios, params }) {
			let response = await $axios.post(`page/${params.name}`)
			return {
				page: response.data.page,
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
				title: this.page.title,
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.page.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home(`page/${this.page.slug}`) },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.page.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		}
	}
</script>