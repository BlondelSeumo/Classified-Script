export default class DealPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_classifieds
    }

    static approve(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.approve_classifieds
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_classifieds
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_classifieds
    }

    static statistics(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.classifieds_stats
    }
}