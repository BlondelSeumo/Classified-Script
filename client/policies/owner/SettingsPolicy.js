export default class SettingsPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_settings
    }

    static general(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_general_settings
    }

    static auth(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_auth_settings
    }

    static smtp(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_smtp_settings
    }

    static security(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_security_settings
    }

    static geo(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_geo_settings
    }

    static seo(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_seo_settings
    }

    static upload(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_uploading_settings
    }

    static payments(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_payment_gateways_settings
    }

    static social(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_social_media_settings
    }

    static watermark(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_watermark_settings
    }

    static footer(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_footer_settings
    }
}