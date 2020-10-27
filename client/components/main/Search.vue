<template>
	<v-dialog v-model="dialogs.search" fullscreen hide-overlay transition="dialog-bottom-transition">
		<template v-slot:activator="{ on }">
			<v-btn v-on="on" text icon :color="index ? 'white' : 'grey darken-3'">
				<v-icon>mdi-image-search</v-icon>
			</v-btn>
		</template>
		<v-card class="no-border-radius search-field" color="#fafafa">
		    <v-toolbar color="white" tabs class="m-card">
		        <!-- Close -->
		        <v-btn icon :class="$vuetify.rtl ? 'mr-5' : 'ml-5'" @click="dialogs.search = false">
		            <v-icon color="grey darken-2">mdi-close</v-icon>
		        </v-btn>

		        <!-- Reset search -->
		        <v-tooltip bottom>
					<template v-slot:activator="{ on }">
						<v-btn v-on="on" icon @click="resetSearch" v-if="results.deals.length">
							<v-icon color="grey darken-2">mdi-replay</v-icon>
						</v-btn>
					</template>
		            <span>{{ $t('t_reset_search') }}</span>
		        </v-tooltip>

		        <!-- Search field -->
		        <v-text-field
		            v-model="search.keyword"
		            :append-icon="search.isVoiceRecording ? 'mdi-microphone-off' : 'mdi-microphone'"
		            class="mx-auto mt-3 search-input search-looking"
		            flat
		            :placeholder="$t('t_what_are_you_looking_for')"
		            solo
		            style="max-width: 50%;"
		            background-color="#f7f7f7"
		            color="#abb7b7"
		            :loading="search.isVoiceRecording || search.isLoading"
		            :disabled="search.isLoading"
		            @click:append="listenToVoice()"
		            @keyup.enter="startSearch"
		        ></v-text-field>
				
				<!-- Save search -->
		        <v-tooltip bottom v-if="$auth.loggedIn && $megate.allow($auth.user, 'save', 'search')">
					<template v-slot:activator="{ on }">
						<v-btn v-on="on" icon @click="saveSearch" v-if="results.deals.length">
							<v-icon color="grey darken-2">mdi-content-save</v-icon>
						</v-btn>
					</template>
		            <span>{{ $t('t_save_search') }}</span>
		        </v-tooltip>

		        <!-- Advanced search -->
		        <v-tooltip bottom>
					<template v-slot:activator="{ on }">
						<v-btn v-on="on" icon :class="$vuetify.rtl ? 'ml-5' : 'mr-5'" @click="advanced">
							<v-icon color="grey darken-2">mdi-filter-variant</v-icon>
						</v-btn>
					</template>
		            <span>{{ $t('t_advanced_search') }}</span>
		        </v-tooltip>

		        <template v-slot:extension v-if="search.advanced">
		            <v-card flat style="width: 52%">
		                <v-card-text>
		                    <v-container grid-list-xl fluid class="pa-0 theme--light">
		                        <v-layout wrap>
		                    
		                            <!-- Category -->
		                            <v-flex xs12>
		                                <v-autocomplete
		                                    v-model="filter.category"
		                                    @change="fetchChilds()"
		                                    browser-autocomplete="off"
		                                    :items="categories"
		                                    item-text="title"
		                                    item-value="id"
		                                    placeholder="Choose category"
		                                    background-color="#f7f7f7"
		                                    color="#abb7b7"
		                                    :disabled="search.isLoading"
		                                    :rules="errors.category"
		                                    :error="errors.category ? true : false"
		                                    class="mx-auto search-input"
		                                    dense
		                                    flat
		                                    solo
		                                    ></v-autocomplete>
		                            </v-flex>
		                    
		                            <!-- Sub categories -->
		                            <v-flex xs12 v-if="childs.length !== 0">
		                                <v-autocomplete
		                                    @change="fetchSubs()"
		                                    v-model="filter.child"
		                                    browser-autocomplete="off"
		                                    :items="childs"
		                                    item-text="title"
		                                    item-value="id"
		                                    placeholder="Choose sub category"
		                                    background-color="#f7f7f7"
		                                    color="#abb7b7"
		                                    :disabled="search.isLoading"
		                                    :rules="errors.child"
		                                    :error="errors.child ? true : false"
		                                    class="mx-auto search-input"
		                                    dense
		                                    flat
		                                    solo
		                                    ></v-autocomplete>
		                            </v-flex>
		                    
		                            <!-- Sort -->
		                            <v-flex xs12>
		                                <v-autocomplete
		                                    v-model="filter.sort_by"
		                                    browser-autocomplete="off"
		                                    :items="['Newest', 'Oldest', 'Popular', 'Featured', 'Urgent', 'Highlight']"
		                                    placeholder="Sort by"
		                                    background-color="#f7f7f7"
		                                    color="#abb7b7"
		                                    :disabled="search.isLoading"
		                                    :rules="errors.sort_by"
		                                    :error="errors.sort_by ? true : false"
		                                    class="mx-auto search-input"
		                                    dense
		                                    flat
		                                    solo
		                                    ></v-autocomplete>
		                            </v-flex>
		                    
		                            <!-- Min price -->
		                            <v-flex xs6>
		                                <v-text-field
		                                    v-model="filter.min_price"
		                                    browser-autocomplete="off"
		                                    placeholder="Minimum price"
		                                    :prefix="$currencySymbol(filter.currency)"
		                                    append-icon="mdi-dots-vertical"
		                                    @click:append="changeCurrency"
		                                    background-color="#f7f7f7"
		                                    color="#abb7b7"
		                                    :disabled="search.isLoading"
		                                    :rules="errors.min_price"
		                                    :error="errors.min_price ? true : false"
		                                    class="mx-auto search-input"
		                                    flat
		                                    solo
		                                    ></v-text-field>
		                            </v-flex>
		                    
		                            <!-- Max price -->
		                            <v-flex xs6>
		                                <v-text-field
		                                    v-model="filter.max_price"
		                                    browser-autocomplete="off"
		                                    placeholder="Maximum price"
		                                    :prefix="$currencySymbol(filter.currency)"
		                                    append-icon="mdi-dots-vertical"
		                                    @click:append="changeCurrency"
		                                    background-color="#f7f7f7"
		                                    color="#abb7b7"
		                                    :disabled="search.isLoading"
		                                    :rules="errors.max_price"
		                                    :error="errors.max_price ? true : false"
		                                    class="mx-auto search-input"
		                                    flat
		                                    solo
		                                    ></v-text-field>
		                            </v-flex>
		                    
		                            <!-- Currency -->
		                            <v-flex xs12 v-if="dialogs.currency">
		                                <v-autocomplete
		                                    @change="dialogs.currency = !dialogs.currency"
		                                    v-model="filter.currency"
		                                    browser-autocomplete="off"
		                                    :items="currencies"
		                                    item-text="name"
		                                    item-value="code"
		                                    placeholder="Choose currency"
		                                    background-color="#f7f7f7"
		                                    color="#abb7b7"
		                                    :disabled="search.isLoading"
		                                    :rules="errors.currency"
		                                    :error="errors.currency ? true : false"
		                                    class="mx-auto search-input"
		                                    dense
		                                    flat
		                                    solo
		                                    ></v-autocomplete>
		                            </v-flex>
		                    
		                            <!-- Search -->
		                            <v-flex xs12>
		                                <v-btn :disabled="search.isLoading" color="#ebebeb" block depressed @click="startSearch">Search</v-btn>
		                            </v-flex>
		                    
		                        </v-layout>
		                    </v-container>
		                </v-card-text>
		            </v-card>
		        </template>
		    </v-toolbar>
		    
		    <!-- Results -->
		    <v-card class="px-3 py-5 mt-4" color="#fafafa" flat>
		        <v-container fluid grid-list-xl>
		            <!-- Results -->
		            <v-layout wrap class="px-12" v-if="results.deals.length !== 0">
						<v-flex xs12 sm6 md6 lg3 class="mb-5" v-for="(deal, index) in results.deals" :key="index">
							<v-card class="m-card ad-box" text :class="deal.isUpgraded && deal.upgradedTo === 'highlight' ? 'deal-highlight' : ''">
								<v-img cover height="230px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="preview(deal)">
									<v-container fill-height class="pa-0">
										<v-layout align-center justify-center @click="dialogs.search = false">
											<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
										</v-layout>
									</v-container>
								</v-img>
								<v-card-title>
									<div class="text-xs-left text-truncate" @click="dialogs.search = false">
										<span class="deal-urgent" v-if="deal.isUpgraded && deal.upgradedTo === 'urgent'">urgent</span>
										<nuxt-link class="deal-title" :to="'/listing/' + deal.unique_slug">{{ deal.title }}</nuxt-link>
									</div>
								</v-card-title>
								<v-card-actions>
									<v-list-item class="grow deal-avatar px-2">
										<v-list-item-avatar color="white" size="25px" :class="$vuetify.rtl ? 'ml-2' : 'mr-2'">
											<v-img :src="avatar(deal.user.avatar)"></v-img>
										</v-list-item-avatar>
										<v-list-item-content>
											<v-list-item-title class="deal-username">{{ deal.user.username }}</v-list-item-title>
										</v-list-item-content>
										<v-layout align-center justify-end style="margin-bottom: 14px">
											<span class="deal-price" v-if="deal.price && deal.currency && deal.locale">{{ $getCurrency(deal.price, deal.currency, deal.locale) }}</span>
										</v-layout>
									</v-list-item>
								</v-card-actions>
							</v-card>
						</v-flex>
		                <v-flex xs12 v-if="results.dealsData">
		                    <div class="text-center pt-2">
		                        <pagination :data="results.dealsData" @pagination-change-page="searchNextPage" :limit=8>
		                            <span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_left</i></span>
		                            <span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_right</i></span>
		                        </pagination>
		                    </div>
		                </v-flex>
		            </v-layout>
		            <!-- No results -->
		            <v-layout wrap class="px-12" v-else>
		                <v-flex xs12>
		                    <v-alert
		                        icon="mdi-information-variant"
								prominent
								text
								type="info"
		                        >
		                        {{ $t('t_search_no_results') }}
		                    </v-alert>
		                </v-flex>
		            </v-layout>
		        </v-container>
		    </v-card>
		    
		</v-card>

		<!-- Advanced -->
		<v-dialog v-model="dialogs.advanced" max-width="600px">
			<v-card class="pt-5">
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>

							<!-- Search keyword -->
							<v-flex xs12>
								<v-text-field
									v-model="search.keyword"
									:append-icon="search.isVoiceRecording ? 'mdi-microphone-off' : 'mdi-microphone'"
									class="search-looking"
									:placeholder="$t('t_what_are_you_looking_for')"
									color="grey lighten-1"
									:loading="search.isVoiceRecording || search.isLoading"
									:disabled="search.isLoading"
									@click:append="listenToVoice()"
									@keyup.enter="startSearch"
								></v-text-field>
							</v-flex>
					
							<!-- Category -->
							<v-flex xs12>
								<v-autocomplete
									v-model="filter.category"
									@change="fetchChilds()"
									autocomplete="off"
									:items="categories"
									item-text="title"
									item-value="id"
									:placeholder="$t('t_choose_category')"
									color="grey lighten-1"
									:disabled="search.isLoading"
									:rules="errors.category"
									:error="errors.category ? true : false"
									dense
									></v-autocomplete>
							</v-flex>
					
							<!-- Sub categories -->
							<v-flex xs12 v-if="childs.length !== 0">
								<v-autocomplete
									@change="fetchSubs()"
									v-model="filter.child"
									autocomplete="off"
									:items="childs"
									item-text="title"
									item-value="id"
									:placeholder="$t('t_choose_sub_category')"
									color="grey lighten-1"
									:disabled="search.isLoading"
									:rules="errors.child"
									:error="errors.child ? true : false"
									dense
									></v-autocomplete>
							</v-flex>
					
							<!-- Sort -->
							<v-flex xs12>
								<v-autocomplete
									v-model="filter.sort_by"
									browser-autocomplete="off"
									:items="sort"
									item-text="title"
									item-value="id"
									:placeholder="$t('t_sort_by')"
									color="grey lighten-1"
									:disabled="search.isLoading"
									:rules="errors.sort_by"
									:error="errors.sort_by ? true : false"
									dense
									></v-autocomplete>
							</v-flex>
					
							<!-- Min price -->
							<v-flex xs6>
								<v-text-field
									v-model="filter.min_price"
									autocomplete="off"
									:placeholder="$t('t_min_price')"
									:prefix="$currencySymbol(filter.currency)"
									append-icon="mdi-dots-vertical"
									color="grey lighten-1"
									@click:append="changeCurrency"
									:disabled="search.isLoading"
									:rules="errors.min_price"
									:error="errors.min_price ? true : false"
									></v-text-field>
							</v-flex>
					
							<!-- Max price -->
							<v-flex xs6>
								<v-text-field
									v-model="filter.max_price"
									autocomplete="off"
									:placeholder="$t('t_max_price')"
									:prefix="$currencySymbol(filter.currency)"
									append-icon="mdi-dots-vertical"
									color="grey lighten-1"
									@click:append="changeCurrency"
									:disabled="search.isLoading"
									:rules="errors.max_price"
									:error="errors.max_price ? true : false"
									></v-text-field>
							</v-flex>
					
							<!-- Currency -->
							<v-flex xs12 v-if="dialogs.currency">
								<v-autocomplete
									@change="dialogs.currency = !dialogs.currency"
									v-model="filter.currency"
									autocomplete="off"
									:items="currencies"
									item-text="name"
									item-value="code"
									:placeholder="$t('t_enter_currency')"
									color="grey lighten-1"
									:disabled="search.isLoading"
									:rules="errors.currency"
									:error="errors.currency ? true : false"
									dense
									></v-autocomplete>
							</v-flex>
					
							<!-- Search -->
							<v-flex xs12>
								<v-btn :disabled="search.isLoading" color="#ebebeb" block depressed @click="startSearch">{{ $t('t_search') }}</v-btn>
							</v-flex>
		                    
		                </v-layout>
					</v-container>
				</v-card-text>
			</v-card>
		</v-dialog>

	</v-dialog>
</template>

<script>
	export default {
		name: "search",

		data () {
			return {
				dialogs: {
                    search: false,
                    advanced: false,
                    currency: false
                },
                search: {
                    keyword: null,
                    isLoading: false,
                    isVoiceRecording: false,
                    advanced: false,
                },
                filter: {
                    category: null,
                    child: null,
                    sortBy: null,
                    min_price: null,
                    max_price: null,
                    currency: null
				},
				sort: [
					{title: this.$t('t_newest'), id: 'newest'},
					{title: this.$t('t_oldest'), id: 'oldest'},
					{title: this.$t('t_popular'), id: 'popular'},
					{title: this.$t('t_featured'), id: 'featured'},
					{title: this.$t('t_urgent'), id: 'urgent'},
					{title: this.$t('t_highlight'), id: 'highlight'}
				],
                categories: [],
                childs: [],
                currencies: [],
                results: {
                    deals: [],
                    dealsData: {},
                },
                errors: []
			}
		},

		computed: {
			index: function() {
				return this.$route.name === 'mainIndex'
			}
		},

		methods: {
            advanced: function() {
                if (this.categories.length === 0) {
                    this.fetchSearchAdvanced()
                    this.dialogs.advanced = !this.dialogs.advanced
                }else{
                    this.dialogs.advanced = !this.dialogs.advanced
                }
            },

            fetchSearchAdvanced: function() {
                this.$axios
                .post('search/settings')
                .then(response => {
                    this.categories      = response.data.categories
                    this.currencies      = response.data.currencies
                    this.filter.currency = response.data.currency.code
                })
                .catch(error => {
                    console.log(error)
                })
            },

            fetchChilds: function() {
                this.search.isLoading = true
                this.$axios
                .post('ajax/fetch/categories/childs', {
                    id: this.filter.category
                })
                .then(response => {
                    if (response.data.length === 0) {
                        this.filter.child = null
                        this.childs       = []
                    }else{
                        this.childs  = response.data
                    }
                    this.search.isLoading = false
                })
                .catch(error => {
                    console.log(error)
                    this.search.isLoading = false
                })
            },

            fetchSubs: function() {
                this.search.isLoading = true
                this.$axios
                .post('ajax/fetch/categories/childs', {
                    id: this.filter.child
                })
                .then(response => {
                    if (response.data.length !== 0) {
                        let older             = this.filter.child
                        this.filter.child     = null
                        this.$nextTick(() => {
                            this.childs       = response.data
                            this.filter.child = older
                        })
                    }
                    this.search.isLoading = false
                })
                .catch(error => {
                    console.log(error)
                    this.search.isLoading = false
                })
            },

            changeCurrency: function() {
                this.dialogs.currency = !this.dialogs.currency
            },

            listenToVoice: function() {
                let vm = this
                try {
                    let SpeechRecognition  = window.SpeechRecognition || window.webkitSpeechRecognition
                    let recognition        = new SpeechRecognition()
                    let language           = window.navigator.userLanguage || window.navigator.language
                    recognition.lang       = language
                    recognition.continuous = false;

                    // This block is called every time the Speech APi captures a line. 
                    recognition.onresult = function(event) {

                        // event is a SpeechRecognitionEvent object.
                         // It holds all the lines we have captured so far. 
                        // We only need the current one.
                        var current = event.resultIndex;

                        // Get a transcript of what was said.
                        var transcript = event.results[current][0].transcript;

                        // Add the current transcript to the contents of our Note.
                        // There is a weird bug on mobile, where everything is repeated twice.
                        // There is no official solution so far so we have to handle an edge case.
                        var mobileRepeatBug = (current == 1 && transcript == event.results[0][0].transcript);

                        if(!mobileRepeatBug) {
                            vm.search.keyword = transcript
                            vm.startSearch()
                        }
                    };

                    recognition.onstart = function() { 
                        vm.search.isVoiceRecording = true
                    }

                    recognition.onspeechend = function() {
                        vm.search.isVoiceRecording = false
                    }

                    recognition.onerror = function(event) {
                        if(event.error == 'no-speech') {
                            vm.search.isVoiceRecording = false  
                        };
                    }

                    // Check if want to stop recording
                    if (vm.search.isVoiceRecording) {
                        vm.search.isVoiceRecording =  false
                        recognition.abort()
                    }else{
                        recognition.start();
                    }
                    
                }
                catch(e) {
                    console.error(e);
                }
            },

            startSearch: function() {
                if (this.search.keyword) {
                    this.search.isLoading = true
                    let category = this.filter.child !== null ? this.filter.child : this.filter.category
                    let url      = 'search?q=' + this.search.keyword 
                    if (category) {
                        url     += '&category=' + category
                    }
                    if (this.filter.sort_by) {
                        url     += '&sort=' + this.filter.sort_by
                    }
                    if (this.filter.min_price) {
                        url     += '&min=' + this.filter.min_price
                    }
                    if (this.filter.max_price) {
                        url     += '&max=' + this.filter.max_price
                    }
                    if (this.filter.currency) {
                        url     += '&currency=' + this.filter.currency
                    }
                    this.$axios
                    .get(url)
                    .then(response => {
                        this.results.deals     = response.data.data
						this.results.dealsData = response.data
						this.dialogs.advanced  = false
                        this.search.isLoading  = false
                    })
                    .catch(error => {
                        if (error.response.data.errors) {
                            this.errors = error.response.data.errors
                        }
                        this.search.isLoading  = false
                    })
                }
            },

            resetSearch: function() {
                this.search.keyword   = null
                this.filter.category  = null
                this.filter.sort_by   = null
                this.filter.min_price = null
                this.filter.max_price = null
            },

            saveSearch: function() {
                this.search.isLoading = true
                this.$axios
                .post('account/search/options/create', {
                    keyword: this.search.keyword,
                    category: this.filter.category,
                    child: this.filter.child,
                    sort: this.filter.sort_by,
                    min: this.filter.min_price,
                    max: this.filter.max_price,
                    currency: this.filter.currency
                })
                .then(response => {
                    this.$toasted.show("Search has been successfully saved.", {
                        icon: 'done_all'
                    })
                    this.search.isLoading = false
                })
                .catch(error => {
                    console.log(error)
                    this.$toasted.error("Oops! Something went wrong. Please try again", {
                        icon: 'error',
                        action: null
                    })
                    this.search.isLoading = false
                })
            },

            searchNextPage: function(page = 1) {
                this.$axios
                .get('/search/' + this.search.searchIn + '?keyword=' + this.search.keyword + '&page=' + page)
                .then(response => {
                    // Products
                    if (this.search.searchIn === 'products') {
                        this.results.products     = response.data.data
                        this.results.productsData = response.data
                    }
                    // Shops
                    if (this.search.searchIn === 'shops') {
                        this.results.shops     = response.data.data
                        this.results.shopsData = response.data
                    }
                    // Deals
                    if (this.search.searchIn === 'deals') {
                        this.results.deals     = response.data.data
                        this.results.dealsData = response.data
                    }
                    document.getElementsByClassName('v-dialog v-dialog--active')[0].scrollTop = 0
                })
                .catch(error => {

                })
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
		},

		mounted() {
			this.$root.$on('launchSearch', value => {
				this.search.isLoading = true
				this.$axios
				.get(value)
				.then(response => {
					this.results.deals     = response.data.data
					this.results.dealsData = response.data
					this.dialogs.advanced  = false
					this.search.isLoading  = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors = error.response.data.errors
					}
					this.search.isLoading  = false
				})
				this.dialogs.search = true
			})
		}
	}
</script>