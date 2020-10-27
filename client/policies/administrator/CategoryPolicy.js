export default class CategoryPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_categories
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_categories
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_categories
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_categories
    }
}