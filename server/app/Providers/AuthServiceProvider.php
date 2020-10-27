<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerOwnerPolicies();
        $this->registerAdminPolicies();
        $this->registerModeratorPolicies();
        $this->registerMemberPolicies();
    }



    /**
     * Register owner policies
     * @return [type] [description]
     */
    private function registerOwnerPolicies()
    {
        Gate::define('owner-access-packages', 'App\Policies\Owner\PackagePolicy@access');
        Gate::define('owner-create-packages', 'App\Policies\Owner\PackagePolicy@create');
        Gate::define('owner-edit-packages', 'App\Policies\Owner\PackagePolicy@edit');
        Gate::define('owner-delete-packages', 'App\Policies\Owner\PackagePolicy@delete');

        Gate::define('owner-access-currencies', 'App\Policies\Owner\CurrencyPolicy@access');
        Gate::define('owner-create-currencies', 'App\Policies\Owner\CurrencyPolicy@create');
        Gate::define('owner-edit-currencies', 'App\Policies\Owner\CurrencyPolicy@edit');
        Gate::define('owner-delete-currencies', 'App\Policies\Owner\CurrencyPolicy@delete');

        Gate::define('owner-access-geo', 'App\Policies\Owner\GeoPolicy@access');
        Gate::define('owner-create-geo', 'App\Policies\Owner\GeoPolicy@create');
        Gate::define('owner-edit-geo', 'App\Policies\Owner\GeoPolicy@edit');
        Gate::define('owner-delete-geo', 'App\Policies\Owner\GeoPolicy@delete');

        Gate::define('owner-access-roles', 'App\Policies\Owner\RolePolicy@access');
        Gate::define('owner-create-roles', 'App\Policies\Owner\RolePolicy@create');
        Gate::define('owner-edit-roles', 'App\Policies\Owner\RolePolicy@edit');
        Gate::define('owner-delete-roles', 'App\Policies\Owner\RolePolicy@delete');

        Gate::define('owner-access-themes', 'App\Policies\Owner\ThemePolicy@access');
        Gate::define('owner-request-themes', 'App\Policies\Owner\ThemePolicy@request');
        Gate::define('owner-edit-themes', 'App\Policies\Owner\ThemePolicy@edit');

        Gate::define('owner-access-settings', 'App\Policies\Owner\SettingsPolicy@access');
        Gate::define('owner-access-settings-general', 'App\Policies\Owner\SettingsPolicy@general');
        Gate::define('owner-access-settings-auth', 'App\Policies\Owner\SettingsPolicy@auth');
        Gate::define('owner-access-settings-smtp', 'App\Policies\Owner\SettingsPolicy@smtp');
        Gate::define('owner-access-settings-security', 'App\Policies\Owner\SettingsPolicy@security');
        Gate::define('owner-access-settings-geo', 'App\Policies\Owner\SettingsPolicy@geo');
        Gate::define('owner-access-settings-seo', 'App\Policies\Owner\SettingsPolicy@seo');
        Gate::define('owner-access-settings-upload', 'App\Policies\Owner\SettingsPolicy@upload');
        Gate::define('owner-access-settings-payments', 'App\Policies\Owner\SettingsPolicy@payments');
        Gate::define('owner-access-settings-social', 'App\Policies\Owner\SettingsPolicy@social');
        Gate::define('owner-access-settings-watermark', 'App\Policies\Owner\SettingsPolicy@watermark');
        Gate::define('owner-access-settings-footer', 'App\Policies\Owner\SettingsPolicy@footer');

        Gate::define('owner-access-subscriptions', 'App\Policies\Owner\SubscriptionPolicy@access');

        Gate::define('owner-access-payments', 'App\Policies\Owner\PaymentsPolicy@access');

        Gate::define('owner-access-sms', 'App\Policies\Owner\SmsPolicy@access');

        Gate::define('owner-access-clouds', 'App\Policies\Owner\CloudPolicy@access');

        Gate::define('owner-access-advertisements', 'App\Policies\Owner\AdvertisementPolicy@access');

        Gate::define('owner-access-support', 'App\Policies\Owner\SupportPolicy@access');

        Gate::define('owner-access-maintenance', 'App\Policies\Owner\MaintenancePolicy@access');
    }



    /**
     * Register admin policies
     * @return [type] [description]
     */
    private function registerAdminPolicies()
    {
        Gate::define('admin-access-categories', 'App\Policies\Administrator\CategoryPolicy@access');
        Gate::define('admin-create-categories', 'App\Policies\Administrator\CategoryPolicy@create');
        Gate::define('admin-edit-categories', 'App\Policies\Administrator\CategoryPolicy@edit');
        Gate::define('admin-delete-categories', 'App\Policies\Administrator\CategoryPolicy@delete');

        Gate::define('admin-access-fields', 'App\Policies\Administrator\FieldPolicy@access');
        Gate::define('admin-create-fields', 'App\Policies\Administrator\FieldPolicy@create');
        Gate::define('admin-edit-fields', 'App\Policies\Administrator\FieldPolicy@edit');
        Gate::define('admin-delete-fields', 'App\Policies\Administrator\FieldPolicy@delete');

        Gate::define('admin-access-blog', 'App\Policies\Administrator\BlogPolicy@access');
        Gate::define('admin-create-articles', 'App\Policies\Administrator\BlogPolicy@create');
        Gate::define('admin-edit-articles', 'App\Policies\Administrator\BlogPolicy@edit');
        Gate::define('admin-delete-articles', 'App\Policies\Administrator\BlogPolicy@delete');

        Gate::define('admin-access-pages', 'App\Policies\Administrator\PagePolicy@access');
        Gate::define('admin-create-pages', 'App\Policies\Administrator\PagePolicy@create');
        Gate::define('admin-edit-pages', 'App\Policies\Administrator\PagePolicy@edit');
        Gate::define('admin-delete-pages', 'App\Policies\Administrator\PagePolicy@delete');

        Gate::define('admin-access-conversations', 'App\Policies\Administrator\ConversationPolicy@access');
        Gate::define('admin-access-conversations-chat', 'App\Policies\Administrator\ConversationPolicy@chat');
        Gate::define('admin-access-conversations-users', 'App\Policies\Administrator\ConversationPolicy@users');
        Gate::define('admin-access-conversations-admins', 'App\Policies\Administrator\ConversationPolicy@admins');

        Gate::define('admin-access-users', 'App\Policies\Administrator\UserPolicy@access');
        Gate::define('admin-approve-users', 'App\Policies\Administrator\UserPolicy@approve');
        Gate::define('admin-create-users', 'App\Policies\Administrator\UserPolicy@create');
        Gate::define('admin-edit-users', 'App\Policies\Administrator\UserPolicy@edit');
        Gate::define('admin-delete-users', 'App\Policies\Administrator\UserPolicy@delete');

        Gate::define('admin-access-deals', 'App\Policies\Administrator\DealPolicy@access');
        Gate::define('admin-approve-deals', 'App\Policies\Administrator\DealPolicy@approve');
        Gate::define('admin-stats-deals', 'App\Policies\Administrator\DealPolicy@statistics');
        Gate::define('admin-edit-deals', 'App\Policies\Administrator\DealPolicy@edit');
        Gate::define('admin-delete-deals', 'App\Policies\Administrator\DealPolicy@delete');

        Gate::define('admin-access-shops', 'App\Policies\Administrator\ShopPolicy@access');
        Gate::define('admin-approve-shops', 'App\Policies\Administrator\ShopPolicy@approve');
        Gate::define('admin-edit-shops', 'App\Policies\Administrator\ShopPolicy@edit');
        Gate::define('admin-delete-shops', 'App\Policies\Administrator\ShopPolicy@delete');

        Gate::define('admin-access-comments', 'App\Policies\Administrator\CommentPolicy@access');
        Gate::define('admin-approve-comments', 'App\Policies\Administrator\CommentPolicy@approve');
        Gate::define('admin-edit-comments', 'App\Policies\Administrator\CommentPolicy@edit');
        Gate::define('admin-delete-comments', 'App\Policies\Administrator\CommentPolicy@delete');
        Gate::define('admin-reported-comments', 'App\Policies\Administrator\CommentPolicy@reported');
    }



    /**
     * Register moderator policies
     * @return [type] [description]
     */
    private function registerModeratorPolicies()
    {
        Gate::define('moderator-access-deals', 'App\Policies\Moderator\DealPolicy@access');
        Gate::define('moderator-approve-deals', 'App\Policies\Moderator\DealPolicy@approve');
        Gate::define('moderator-edit-deals', 'App\Policies\Moderator\DealPolicy@edit');
        Gate::define('moderator-delete-deals', 'App\Policies\Moderator\DealPolicy@delete');
        Gate::define('moderator-stats-deals', 'App\Policies\Moderator\DealPolicy@stats');

        Gate::define('moderator-access-shops', 'App\Policies\Moderator\ShopPolicy@access');
        Gate::define('moderator-approve-shops', 'App\Policies\Moderator\ShopPolicy@approve');
        Gate::define('moderator-edit-shops', 'App\Policies\Moderator\ShopPolicy@edit');
        Gate::define('moderator-delete-shops', 'App\Policies\Moderator\ShopPolicy@delete');

        Gate::define('moderator-access-comments', 'App\Policies\Moderator\CommentPolicy@access');
        Gate::define('moderator-approve-comments', 'App\Policies\Moderator\CommentPolicy@approve');
        Gate::define('moderator-edit-comments', 'App\Policies\Moderator\CommentPolicy@edit');
        Gate::define('moderator-delete-comments', 'App\Policies\Moderator\CommentPolicy@delete');
        Gate::define('moderator-reported-comments', 'App\Policies\Moderator\CommentPolicy@reported');
    }



    /**
     * Register member policies
     * @return [type] [description]
     */
    private function registerMemberPolicies()
    {
        Gate::define('member-write-comments', 'App\Policies\Member\CommentPolicy@write');
        Gate::define('member-edit-comments', 'App\Policies\Member\CommentPolicy@edit');
        Gate::define('member-delete-comments', 'App\Policies\Member\CommentPolicy@delete');

        Gate::define('member-send-messages', 'App\Policies\Member\MessagePolicy@send');
        Gate::define('member-receive-messages', 'App\Policies\Member\MessagePolicy@receive');
        Gate::define('member-delete-messages', 'App\Policies\Member\MessagePolicy@delete');

        Gate::define('member-report-deals', 'App\Policies\Member\ReportPolicy@deals');
        Gate::define('member-report-comments', 'App\Policies\Member\ReportPolicy@comments');

        Gate::define('member-send-offers', 'App\Policies\Member\OfferPolicy@send');
        Gate::define('member-receive-offers', 'App\Policies\Member\OfferPolicy@receive');

        Gate::define('member-save-search', 'App\Policies\Member\SearchPolicy@save');

        Gate::define('member-follow-shops', 'App\Policies\Member\ShopPolicy@follow');
        Gate::define('member-contact-shops', 'App\Policies\Member\ShopPolicy@contact');

        Gate::define('member-see-advertisements', 'App\Policies\Member\AdvertisementPolicy@see');

        Gate::define('member-see-stats', 'App\Policies\Member\DealPolicy@stats');
    }
}
