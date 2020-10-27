export default class SeePolicy
{
    static statistics(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.see_classifieds_stats
    }

    static advertisements(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.see_advertisements
    }
}