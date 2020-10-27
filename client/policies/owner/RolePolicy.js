export default class RolePolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_roles
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_roles
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_roles
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_roles
    }
}