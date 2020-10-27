export default class ThemePolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_themes
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_themes
    }

    static request(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.request_themes
    }
}