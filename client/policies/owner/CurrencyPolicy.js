export default class CurrencyPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_currencies
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_currencies
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_currencies
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_currencies
    }
}