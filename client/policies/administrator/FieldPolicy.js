export default class FieldPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_custom_fields
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_custom_fields
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_custom_fields
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_custom_fields
    }
}