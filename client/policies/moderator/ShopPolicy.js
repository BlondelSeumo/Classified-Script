export default class ShopPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_stores
    }

    static approve(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.approve_stores
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_stores
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_stores
    }

    static teams(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.stores_team
    }
}