export default class ShopPolicy
{
    static follow(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.follow_stores
    }

    static contact(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.contact_stores
    }
}