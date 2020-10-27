export default class UserPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_users
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_users
    }

    static approve(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.approve_users
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_users
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_users
    }
}