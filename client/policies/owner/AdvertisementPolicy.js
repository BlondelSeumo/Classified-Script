export default class AdvertisementPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_advertisements
    }
}