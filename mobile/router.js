import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

/**
 * Installation routes
 */
const InstallWelcome           = () => import('~/pages/install/welcome').then(m => m.default || m)
const InstallVerify            = () => import('~/pages/install/verify').then(m => m.default || m)
const InstallRequirements      = () => import('~/pages/install/requirements').then(m => m.default || m)
const InstallDatabase          = () => import('~/pages/install/database').then(m => m.default || m)
const InstallWebsite           = () => import('~/pages/install/website').then(m => m.default || m)
const InstallAccount           = () => import('~/pages/install/account').then(m => m.default || m)
const InstallFinish           = () => import('~/pages/install/finish').then(m => m.default || m)


/**
 * Main routes
 * @return {[type]} [description]
 */
const MainIndex                = () => import('~/pages/main/home/home').then(m => m.default || m)
const MainLogin                = () => import('~/pages/main/auth/login').then(m => m.default || m)
const MainLoginCallback        = () => import('~/pages/main/auth/callback').then(m => m.default || m)
const MainRegister             = () => import('~/pages/main/auth/register').then(m => m.default || m)
const MainActivation           = () => import('~/pages/main/auth/activation').then(m => m.default || m)
const MainPasswordReset        = () => import('~/pages/main/auth/password/reset').then(m => m.default || m)
const MainPasswordUpdate        = () => import('~/pages/main/auth/password/update').then(m => m.default || m)
const MainShop                 = () => import('~/pages/main/shop/shop').then(m => m.default || m)
const MainCreateDeal           = () => import('~/pages/main/create/create').then(m => m.default || m)
const MainListing              = () => import('~/pages/main/listing/listing').then(m => m.default || m)
const MainRedirect             = () => import('~/pages/main/listing/redirect').then(m => m.default || m)
const MainAccountSettings      = () => import('~/pages/main/account/settings/settings').then(m => m.default || m)
const MainAccountFollowing     = () => import('~/pages/main/account/following/following').then(m => m.default || m)
const MainAccountDeals         = () => import('~/pages/main/account/deals/deals').then(m => m.default || m)
const MainAccountEditDeal      = () => import('~/pages/main/account/deals/options/edit').then(m => m.default || m)
const MainAccountPromoteDeal   = () => import('~/pages/main/account/deals/options/promote').then(m => m.default || m)
const MainAccountDealStats     = () => import('~/pages/main/account/deals/options/statistics').then(m => m.default || m)
const MainAccountOffers        = () => import('~/pages/main/account/offers/offers').then(m => m.default || m)
const MainAccount2FA           = () => import('~/pages/main/account/security/2fa').then(m => m.default || m)
const MainAccountMessages      = () => import('~/pages/main/account/messages/messages').then(m => m.default || m)
const MainAccountSubscription  = () => import('~/pages/main/account/subscription/subscription').then(m => m.default || m)
const MainAccountNotifications = () => import('~/pages/main/account/notifications/notifications').then(m => m.default || m)
const MainAccountSearch        = () => import('~/pages/main/account/search/search').then(m => m.default || m)
const MainBrowseDeals          = () => import('~/pages/main/browse/deals/deals').then(m => m.default || m)
const MainBrowseShops          = () => import('~/pages/main/browse/shops/shops').then(m => m.default || m)
const MainPricing              = () => import('~/pages/main/pricing/pricing').then(m => m.default || m)
const MainPricingCheckout      = () => import('~/pages/main/pricing/checkout').then(m => m.default || m)
const MainBlog                 = () => import('~/pages/main/blog/blog').then(m => m.default || m)
const MainArticle              = () => import('~/pages/main/blog/article').then(m => m.default || m)
const MainFeedback             = () => import('~/pages/main/contact/feedback').then(m => m.default || m)
const MainContact              = () => import('~/pages/main/contact/contact').then(m => m.default || m)
const MainPage                 = () => import('~/pages/main/pages/page').then(m => m.default || m)
const MainChat                 = () => import('~/pages/main/chat/chat').then(m => m.default || m)
const MainChatConversation     = () => import('~/pages/main/chat/conversation').then(m => m.default || m)
const MainCategory             = () => import('~/pages/main/category/category').then(m => m.default || m)
const NotFound                 = () => import('~/pages/main/errors/404').then(m => m.default || m)
const ServerError              = () => import('~/pages/main/errors/500').then(m => m.default || m)
const Maintenance              = () => import('~/pages/main/errors/503').then(m => m.default || m)

/**
 * Administrator routes
 * @return {[type]} [description]
 */
const AdminHome                    = () => import('~/pages/admin/home/home').then(m => m.default || m)
const AdminProfile                 = () => import('~/pages/admin/account/profile').then(m => m.default || m)
const AdminPendingDeals            = () => import('~/pages/admin/pending/deals').then(m => m.default || m)
const AdminPendingUsers            = () => import('~/pages/admin/pending/users').then(m => m.default || m)
const AdminPendingShops            = () => import('~/pages/admin/pending/shops').then(m => m.default || m)
const AdminPendingComments         = () => import('~/pages/admin/pending/comments').then(m => m.default || m)
const AdminPendingReportedDeals    = () => import('~/pages/admin/reports/deals').then(m => m.default || m)
const AdminPendingReportedComments = () => import('~/pages/admin/reports/comments').then(m => m.default || m)
const AdminUsers                   = () => import('~/pages/admin/users/users').then(m => m.default || m)
const AdminCreateUser              = () => import('~/pages/admin/users/options/create').then(m => m.default || m)
const AdminEditUser                = () => import('~/pages/admin/users/options/edit').then(m => m.default || m)
const AdminRoles                   = () => import('~/pages/admin/roles/roles').then(m => m.default || m)
const AdminCreateOwnerRole         = () => import('~/pages/admin/roles/options/create/owner').then(m => m.default || m)
const AdminCreateAdministratorRole = () => import('~/pages/admin/roles/options/create/administrator').then(m => m.default || m)
const AdminCreateModeratorRole     = () => import('~/pages/admin/roles/options/create/moderator').then(m => m.default || m)
const AdminCreateMemberRole        = () => import('~/pages/admin/roles/options/create/member').then(m => m.default || m)
const AdminEditRole                = () => import('~/pages/admin/roles/options/edit').then(m => m.default || m)
const AdminDealsPayments           = () => import('~/pages/admin/deals/payments/payments').then(m => m.default || m)
const AdminDealsPackages           = () => import('~/pages/admin/deals/packages/packages').then(m => m.default || m)
const AdminCreateDealsPackages     = () => import('~/pages/admin/deals/packages/options/create').then(m => m.default || m)
const AdminDeals                   = () => import('~/pages/admin/deals/deals').then(m => m.default || m)
const AdminCreateDeal              = () => import('~/pages/admin/deals/options/create').then(m => m.default || m)
const AdminEditDeal                = () => import('~/pages/admin/deals/options/edit').then(m => m.default || m)
const AdminDealsOffers             = () => import('~/pages/admin/offers/offers').then(m => m.default || m)
const AdminDealStatistics          = () => import('~/pages/admin/deals/options/statistics').then(m => m.default || m)
const AdminCategories              = () => import('~/pages/admin/categories/categories').then(m => m.default || m)
const AdminCreateCategory          = () => import('~/pages/admin/categories/options/create').then(m => m.default || m)
const AdminEditCategory            = () => import('~/pages/admin/categories/options/edit').then(m => m.default || m)
const AdminCustomFields            = () => import('~/pages/admin/fields/fields').then(m => m.default || m)
const AdminCreateCustomField       = () => import('~/pages/admin/fields/options/create').then(m => m.default || m)
const AdminEditCustomField         = () => import('~/pages/admin/fields/options/edit').then(m => m.default || m)
const AdminComments                = () => import('~/pages/admin/comments/comments').then(m => m.default || m)
const AdminShops                   = () => import('~/pages/admin/shops/shops').then(m => m.default || m)
const AdminCreateShop              = () => import('~/pages/admin/shops/options/create').then(m => m.default || m)
const AdminEditShop                = () => import('~/pages/admin/shops/options/edit').then(m => m.default || m)
const AdminConversationsChat       = () => import('~/pages/admin/conversations/chat/chat').then(m => m.default || m)
const AdminConversationsChatRoom   = () => import('~/pages/admin/conversations/chat/room').then(m => m.default || m)
const AdminConversationsUsers      = () => import('~/pages/admin/conversations/users/messages').then(m => m.default || m)
const AdminConversationsAdmin      = () => import('~/pages/admin/conversations/admin/messages').then(m => m.default || m)
const AdminPackagesPlans           = () => import('~/pages/admin/plans/plans').then(m => m.default || m)
const AdminCreatePackagePlan       = () => import('~/pages/admin/plans/options/create').then(m => m.default || m)
const AdminEditPackagePlan         = () => import('~/pages/admin/plans/options/edit').then(m => m.default || m)
const AdminSubscriptions           = () => import('~/pages/admin/subscriptions/subscriptions').then(m => m.default || m)
const AdminBlog                    = () => import('~/pages/admin/blog/articles').then(m => m.default || m)
const AdminCreateArticle           = () => import('~/pages/admin/blog/options/create').then(m => m.default || m)
const AdminEditArticle             = () => import('~/pages/admin/blog/options/edit').then(m => m.default || m)
const AdminCurrencies              = () => import('~/pages/admin/currencies/currencies').then(m => m.default || m)
const AdminPages                   = () => import('~/pages/admin/pages/pages').then(m => m.default || m)
const AdminCreatePage              = () => import('~/pages/admin/pages/options/create').then(m => m.default || m)
const AdminEditPage                = () => import('~/pages/admin/pages/options/edit').then(m => m.default || m)
const AdminThemes                  = () => import('~/pages/admin/themes/themes').then(m => m.default || m)
const AdminRequestTheme            = () => import('~/pages/admin/themes/options/request').then(m => m.default || m)
const AdminCountries               = () => import('~/pages/admin/geolocation/countries/countries').then(m => m.default || m)
const AdminCreateCountry           = () => import('~/pages/admin/geolocation/countries/options/create').then(m => m.default || m)
const AdminStates                  = () => import('~/pages/admin/geolocation/states/states').then(m => m.default || m)
const AdminCreateState             = () => import('~/pages/admin/geolocation/states/options/create').then(m => m.default || m)
const AdminCities                  = () => import('~/pages/admin/geolocation/cities/cities').then(m => m.default || m)
const AdminCreateCity              = () => import('~/pages/admin/geolocation/cities/options/create').then(m => m.default || m)
const AdminAdvertisementsAdsense   = () => import('~/pages/admin/advertisements/adsense/adsense').then(m => m.default || m)
const AdminAdvertisementsCompanies = () => import('~/pages/admin/advertisements/companies/companies').then(m => m.default || m)
const AdminServicesSms             = () => import('~/pages/admin/services/sms/gateways').then(m => m.default || m)
const AdminServicesSmsNexmo        = () => import('~/pages/admin/services/sms/gateways/nexmo').then(m => m.default || m)
const AdminServicesClouds          = () => import('~/pages/admin/services/clouds/gateways').then(m => m.default || m)
const AdminSettingsGeneral         = () => import('~/pages/admin/settings/general').then(m => m.default || m)
const AdminSettingsHome            = () => import('~/pages/admin/settings/home').then(m => m.default || m)
const AdminSettingsAuth            = () => import('~/pages/admin/settings/auth').then(m => m.default || m)
const AdminSettingsSmtp            = () => import('~/pages/admin/settings/smtp').then(m => m.default || m)
const AdminSettingsSecurity        = () => import('~/pages/admin/settings/security').then(m => m.default || m)
const AdminSettingsGeo             = () => import('~/pages/admin/settings/geo').then(m => m.default || m)
const AdminSettingsSeo             = () => import('~/pages/admin/settings/seo').then(m => m.default || m)
const AdminSettingsUpload          = () => import('~/pages/admin/settings/upload').then(m => m.default || m)
const AdminSettingsPosting         = () => import('~/pages/admin/settings/posting').then(m => m.default || m)
const AdminSettingsPayments        = () => import('~/pages/admin/settings/payments').then(m => m.default || m)
const AdminSettingsSocial          = () => import('~/pages/admin/settings/social').then(m => m.default || m)
const AdminSettingsWatermark       = () => import('~/pages/admin/settings/watermark').then(m => m.default || m)
const AdminSettingsFooter          = () => import('~/pages/admin/settings/footer').then(m => m.default || m)
const AdminSupportContact          = () => import('~/pages/admin/support/contact').then(m => m.default || m)
const AdminSupportSettings         = () => import('~/pages/admin/support/settings').then(m => m.default || m)
const AdminMaintenance             = () => import('~/pages/admin/maintenance/maintenance').then(m => m.default || m)


/**
* Moderator routes
*/
const ModeratorHome                = () => import('~/pages/moderator/home/home.vue').then(m => m.default || m)
const ModeratorProfile             = () => import('~/pages/moderator/account/profile.vue').then(m => m.default || m)
const ModeratorPendingDeals        = () => import('~/pages/moderator/pending/deals.vue').then(m => m.default || m)
const ModeratorPendingShops        = () => import('~/pages/moderator/pending/shops.vue').then(m => m.default || m)
const ModeratorPendingComments     = () => import('~/pages/moderator/pending/comments.vue').then(m => m.default || m)
const ModeratorPendingReports      = () => import('~/pages/moderator/pending/reports.vue').then(m => m.default || m)
const ModeratorDeals               = () => import('~/pages/moderator/deals/deals.vue').then(m => m.default || m)
const ModeratorEditDeal            = () => import('~/pages/moderator/deals/options/edit.vue').then(m => m.default || m)
const ModeratorDealStatistics      = () => import('~/pages/moderator/deals/options/statistics.vue').then(m => m.default || m)
const ModeratorShops               = () => import('~/pages/moderator/shops/shops.vue').then(m => m.default || m)
const ModeratorEditShop            = () => import('~/pages/moderator/shops/options/edit.vue').then(m => m.default || m)
const ModeratorComments            = () => import('~/pages/moderator/comments/comments.vue').then(m => m.default || m)
const ModeratorReportedDeals       = () => import('~/pages/moderator/reports/deals.vue').then(m => m.default || m)
const ModeratorReportedComments       = () => import('~/pages/moderator/reports/comments.vue').then(m => m.default || m)

/**
 * Manager routes
 */
const ManagerHome                 = () => import('~/pages/manager/home/home.vue').then(m => m.default || m)
const ManagerDeals                = () => import('~/pages/manager/deals/deals.vue').then(m => m.default || m)
const ManagerCreateDeal           = () => import('~/pages/manager/deals/options/create.vue').then(m => m.default || m)
const ManagerEditDeal             = () => import('~/pages/manager/deals/options/edit.vue').then(m => m.default || m)
const ManagerDealStats            = () => import('~/pages/manager/deals/options/statistics.vue').then(m => m.default || m)
const ManagerShops                = () => import('~/pages/manager/shops/shops.vue').then(m => m.default || m)
const ManagerCreateShop           = () => import('~/pages/manager/shops/options/create.vue').then(m => m.default || m)
const ManagerEditShop             = () => import('~/pages/manager/shops/options/edit.vue').then(m => m.default || m)
const ManagerMessages             = () => import('~/pages/manager/messages/messages.vue').then(m => m.default || m)
const ManagerFollowers            = () => import('~/pages/manager/followers/followers.vue').then(m => m.default || m)
const ManagerNotifications        = () => import('~/pages/manager/notifications/notifications.vue').then(m => m.default || m)
const ManagerSettingsGeneral      = () => import('~/pages/manager/settings/general.vue').then(m => m.default || m)
const ManagerSettingsAutoshare    = () => import('~/pages/manager/settings/autoshare.vue').then(m => m.default || m)
const ManagerSettingsSubscription = () => import('~/pages/manager/settings/subscription.vue').then(m => m.default || m)

const routes = [

  // Installation routes
  {path: '/installation/welcome',      name: 'InstallWelcome',         component: InstallWelcome},
  {path: '/installation/verify',       name: 'InstallVerify',          component: InstallVerify},
  {path: '/installation/requirements', name: 'InstallRequirements',    component: InstallRequirements},
  {path: '/installation/website',      name: 'InstallWebsite',         component:InstallWebsite},
  {path: '/installation/database',     name: 'InstallDatabase',        component:InstallDatabase},
  {path: '/installation/account',      name: 'InstallAccount',         component:InstallAccount},
  {path: '/installation/finish',      name: 'InstallFinish',          component:InstallFinish},


  // Main routes
  {path: '/',                          name: 'mainIndex',              component: MainIndex},
  {path: '/a/:id',                     name: 'mainRedirect',           component: MainRedirect},
  {path: '/account/settings',          name: 'mainAccountSettings',    component: MainAccountSettings},
  {path: '/account/following',         name: 'mainAccountFollowing',   component: MainAccountFollowing},
  {path: '/account/deals',             name: 'mainAccountDeals',       component: MainAccountDeals},
  {path: '/account/deals/edit/:id',    name: 'mainAccountEditDeal',    component: MainAccountEditDeal},
  {path: '/account/deals/statistics/:id',   name: 'mainAccountDealStats',    component: MainAccountDealStats},
  {path: '/account/offers',            name: 'mainAccountOffers',      component: MainAccountOffers},
  {path: '/account/2fa',               name: 'main2FA',                component: MainAccount2FA},
  {path: '/account/messages',          name: 'mainMessages',           component: MainAccountMessages},
  {path: '/account/subscription',      name: 'mainSubscription',       component: MainAccountSubscription},
  {path: '/account/notifications',     name: 'mainNotifications',      component: MainAccountNotifications},
  {path: '/account/search',            name: 'mainSearch',             component: MainAccountSearch},
  {path: '/account/deals/promote/:id', name: 'mainAccountPromoteDeal', component: MainAccountPromoteDeal},
  {path: '/auth/login',                name: 'mainLogin',              component: MainLogin},
  {path: '/auth/:provider/callback',   name: 'mainLoginCallback',      component: MainLoginCallback},
  {path: '/auth/register',             name: 'mainRegister',           component: MainRegister},
  {path: '/auth/activation',           name: 'mainActivation',         component: MainActivation},
  {path: '/auth/password/reset',       name: 'mainPasswordReset',      component: MainPasswordReset},
  {path: '/auth/password/update',      name: 'mainPasswordUpdate',     component: MainPasswordUpdate},
  {path: '/blog',                      name: 'mainBlog',               component: MainBlog},
  {path: '/blog/:id',                  name: 'mainArticle',            component: MainArticle},
  {path: '/browse/deals',              name: 'mainBrowseDeals',        component: MainBrowseDeals},
  {path: '/browse/shops',              name: 'mainBrowseShops',        component: MainBrowseShops},
  {path: '/category/:slug',            name: 'mainCategory',           component: MainCategory},
  {path: '/chat',                      name: 'mainChat',               component: MainChat},
  {path: '/chat/conversation/:id',     name: 'mainChatConversation',   component: MainChatConversation},
  {path: '/contact',                   name: 'mainContact',            component: MainContact},
  {path: '/create',                    name: 'mainCreateDeal',         component: MainCreateDeal},
  {path: '/feedback',                  name: 'mainFeedback',           component: MainFeedback},
  {path: '/listing/:slug',             name: 'mainListing',            component: MainListing},
  {path: '/page/:name',                name: 'mainPage',               component: MainPage},
  {path: '/pricing',                   name: 'mainPricing',            component: MainPricing},
  {path: '/pricing/checkout/:plan',    name: 'mainPricingCheckout',    component: MainPricingCheckout},
  {path: '/shop/:shop',                name: 'mainShop',               component: MainShop},
  {path: '/maintenance',               name: 'Maintenance',            component: Maintenance},
  {path: '/500',                        name: 'ServerError',            component: ServerError},
  {path: '*',                          name: 'NotFound',               component: NotFound},

  //Administrator routes
  {path: '/administrator', name: 'adminHome', component: AdminHome},
  {path: '/administrator/profile', name: 'adminProfile', component: AdminProfile},
  {path: '/administrator/pending/deals', name: 'adminPendingDeals', component: AdminPendingDeals},
  {path: '/administrator/pending/users', name: 'adminPendingUsers', component: AdminPendingUsers},
  {path: '/administrator/pending/shops', name: 'adminPendingShops', component: AdminPendingShops},
  {path: '/administrator/pending/comments', name: 'adminPendingComments', component: AdminPendingComments},
  {path: '/administrator/reports/deals', name: 'adminPendingReportedDeals', component: AdminPendingReportedDeals},
  {path: '/administrator/reports/comments', name: 'adminPendingReportedComments', component: AdminPendingReportedComments},
  {path: '/administrator/users', name: 'adminUsers', component: AdminUsers},
  {path: '/administrator/users/create', name: 'adminCreateUser', component: AdminCreateUser},
  {path: '/administrator/users/edit/:username', name: 'adminEditUser', component: AdminEditUser},
  {path: '/administrator/roles', name: 'adminRoles', component: AdminRoles},
  {path: '/administrator/roles/options/create/owner', name: 'adminCreateOwnerRole', component: AdminCreateOwnerRole},
  {path: '/administrator/roles/options/create/administrator', name: 'adminCreateAdministratorRole', component: AdminCreateAdministratorRole},
  {path: '/administrator/roles/options/create/moderator', name: 'adminCreateModeratorRole', component: AdminCreateModeratorRole},
  {path: '/administrator/roles/options/create/member', name: 'adminCreateMemberRole', component: AdminCreateMemberRole},
  {path: '/administrator/roles/options/edit/:id', name: 'adminEditRole', component: AdminEditRole},
  {path: '/administrator/deals/payments', name: 'adminDealsPayments', component: AdminDealsPayments},
  {path: '/administrator/deals/packages', name: 'adminDealsPackages', component: AdminDealsPackages},
  {path: '/administrator/deals/packages/create', name: 'adminCreateDealsPackages', component: AdminCreateDealsPackages},
  {path: '/administrator/deals', name: 'adminDeals', component: AdminDeals},
  {path: '/administrator/deals/create', name: 'adminCreateDeal', component: AdminCreateDeal},
  {path: '/administrator/deals/options/edit/:id', name: 'adminEditDeal', component: AdminEditDeal},
  {path: '/administrator/deals/statistics/:id', name: 'adminDealStatistics', component: AdminDealStatistics},
  {path: '/administrator/deals/offers', name: 'adminDealsOffers', component: AdminDealsOffers},
  {path: '/administrator/categories', name: 'adminCategories', component: AdminCategories},
  {path: '/administrator/categories/create', name: 'adminCreateCategory', component: AdminCreateCategory},
  {path: '/administrator/categories/edit/:slug', name: 'adminEditCategory', component: AdminEditCategory},
  {path: '/administrator/fields', name: 'adminCustomFields', component: AdminCustomFields},
  {path: '/administrator/fields/create', name: 'adminCreateCustomField', component: AdminCreateCustomField},
  {path: '/administrator/fields/edit/:id', name: 'adminEditCustomField', component: AdminEditCustomField},
  {path: '/administrator/comments', name: 'adminComments', component: AdminComments},
  {path: '/administrator/shops', name: 'adminShops', component: AdminShops},
  {path: '/administrator/shops/create', name: 'adminCreateShop', component: AdminCreateShop},
  {path: '/administrator/shops/edit/:shop', name: 'adminEditShop', component: AdminEditShop},
  {path: '/administrator/conversations/chat', name: 'adminConversationsChat', component: AdminConversationsChat},
  {path: '/administrator/conversations/chat/room/:id', name: 'adminConversationsChatRoom', component: AdminConversationsChatRoom},
  {path: '/administrator/conversations/users', name: 'adminConversationsUsers', component: AdminConversationsUsers},
  {path: '/administrator/conversations/admin', name: 'adminConversationsAdmin', component: AdminConversationsAdmin},
  {path: '/administrator/membership/plans', name: 'adminPackagesPlans', component: AdminPackagesPlans},
  {path: '/administrator/membership/plans/create', name: 'adminCreatePackagePlan', component: AdminCreatePackagePlan},
  {path: '/administrator/membership/plans/edit/:id', name: 'adminEditPackagePlan', component: AdminEditPackagePlan},
  {path: '/administrator/membership/subscriptions', name: 'adminSubscriptions', component: AdminSubscriptions},
  {path: '/administrator/blog', name: 'adminBlog', component: AdminBlog},
  {path: '/administrator/blog/create', name: 'adminCreateArticle', component: AdminCreateArticle},
  {path: '/administrator/blog/edit/:id', name: 'adminEditArticle', component: AdminEditArticle},
  {path: '/administrator/currencies', name: 'adminCurrencies', component: AdminCurrencies},
  {path: '/administrator/pages', name: 'adminPages', component: AdminPages},
  {path: '/administrator/pages/create', name: 'adminCreatePage', component: AdminCreatePage},
  {path: '/administrator/pages/edit/:slug', name: 'adminEditPage', component: AdminEditPage},
  {path: '/administrator/themes', name: 'adminThemes', component: AdminThemes},
  {path: '/administrator/themes/request', name: 'adminRequestTheme', component: AdminRequestTheme},
  {path: '/administrator/geolocation/countries', name: 'adminCountries', component: AdminCountries},
  {path: '/administrator/geolocation/countries/create', name: 'adminCreateCountry', component: AdminCreateCountry},
  {path: '/administrator/geolocation/states', name: 'adminStates', component: AdminStates},
  {path: '/administrator/geolocation/states/create', name: 'adminCreateState', component: AdminCreateState},
  {path: '/administrator/geolocation/cities', name: 'adminCities', component: AdminCities},
  {path: '/administrator/geolocation/cities/create', name: 'admnCreateCity', component: AdminCreateCity},
  {path: '/administrator/advertisements/adsense', name: 'adminAdvertisementsAdsense', component: AdminAdvertisementsAdsense},
  {path: '/administrator/advertisements/companies', name: 'adminAdvertisementsCompanies', component: AdminAdvertisementsCompanies},
  {path: '/administrator/services/sms', name: 'adminServicesSms', component: AdminServicesSms},
  {path: '/administrator/services/sms/nexmo', name: 'adminServicesSmsNexmo', component: AdminServicesSmsNexmo},
  {path: '/administrator/services/clouds', name: 'adminServicesClouds', component: AdminServicesClouds},
  {path: '/administrator/settings/general', name: 'adminSettingsGeneral', component: AdminSettingsGeneral},
  {path: '/administrator/settings/home', name: 'adminSettingsHome', component: AdminSettingsHome},
  {path: '/administrator/settings/auth', name: 'adminSettingsAuth', component: AdminSettingsAuth},
  {path: '/administrator/settings/smtp', name: 'adminSettingsSmtp', component: AdminSettingsSmtp},
  {path: '/administrator/settings/security', name: 'adminSettingsSecurity', component: AdminSettingsSecurity},
  {path: '/administrator/settings/geo', name: 'adminSettingsGeo', component: AdminSettingsGeo},
  {path: '/administrator/settings/seo', name: 'adminSettingsSeo', component: AdminSettingsSeo},
  {path: '/administrator/settings/upload', name: 'adminSettingsUpload', component: AdminSettingsUpload},
  {path: '/administrator/settings/posting', name: 'adminSettingsPosting', component: AdminSettingsPosting},
  {path: '/administrator/settings/payments', name: 'adminSettingsPayments', component: AdminSettingsPayments},
  {path: '/administrator/settings/social', name: 'adminSettingsSocial', component: AdminSettingsSocial},
  {path: '/administrator/settings/watermark', name: 'adminSettingsWatermark', component: AdminSettingsWatermark},
  {path: '/administrator/settings/footer', name: 'adminSettingsFooter', component: AdminSettingsFooter},
  {path: '/administrator/support/contact', name: 'adminSupportContact', component: AdminSupportContact},
  {path: '/administrator/support/settings', name: 'adminSupportSettings', component: AdminSupportSettings},
  {path: '/administrator/maintenance', name: 'adminMaintenance', component: AdminMaintenance},


  // Moderator routes
  {path: '/moderator', name: 'moderatorHome', component: ModeratorHome},
  {path: '/moderator/profile', name: 'moderatorProfile', component: ModeratorProfile},
  {path: '/moderator/pending/deals', name: 'moderatorPendingDeals', component: ModeratorPendingDeals},
  {path: '/moderator/pending/shops', name: 'moderatorPendingShops', component: ModeratorPendingShops},
  {path: '/moderator/pending/comments', name: 'moderatorPendingComments', component: ModeratorPendingComments},
  {path: '/moderator/pending/reports', name: 'moderatorPendingReports', component: ModeratorPendingReports},
  {path: '/moderator/deals', name: 'moderatorDeals', component: ModeratorDeals},
  {path: '/moderator/deals/edit/:id', name: 'moderatorEditDeal', component: ModeratorEditDeal},
  {path: '/moderator/deals/statistics/:id', name: 'moderatorDealStatistics', component: ModeratorDealStatistics},
  {path: '/moderator/shops', name: 'moderatorShops', component: ModeratorShops},
  {path: '/moderator/shops/edit/:shop', name: 'moderatorEditShop', component: ModeratorEditShop},
  {path: '/moderator/comments', name: 'moderatorComments', component: ModeratorComments},
  {path: '/moderator/reports/deals', name: 'moderatorReportedDeals', component: ModeratorReportedDeals},
  {path: '/moderator/reports/comments', name: 'moderatorReportedComments', component: ModeratorReportedComments},


  // Manager routes
  {path: '/manager', name: 'managerHome', component: ManagerHome},
  {path: '/manager/deals', name: 'managerDeals', component: ManagerDeals},
  {path: '/manager/deals/create', name: 'managerCreateDeal', component: ManagerCreateDeal},
  {path: '/manager/deals/edit/:id', name: 'managerEditDeal', component: ManagerEditDeal},
  {path: '/manager/deals/statistics/:id', name: 'managerDealStats', component: ManagerDealStats},
  {path: '/manager/shops', name: 'managerShops', component: ManagerShops},
  {path: '/manager/shops/create', name: 'managerCreateShop', component: ManagerCreateShop},
  {path: '/manager/shops/edit/:shop', name: 'managerEditShop', component: ManagerEditShop},
  {path: '/manager/messages', name: 'managerMessages', component: ManagerMessages},
  {path: '/manager/followers', name: 'managerFollowers', component: ManagerFollowers},
  {path: '/manager/notifications', name: 'managerNotifications', component: ManagerNotifications},
  {path: '/manager/settings/general', name: 'managerSettingsGeneral', component: ManagerSettingsGeneral},
  {path: '/manager/settings/autoshare', name: 'managerSettingsAutoshare', component: ManagerSettingsAutoshare},
  {path: '/manager/settings/subscription', name: 'managerSettingsSubscription', component: ManagerSettingsSubscription}

]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
