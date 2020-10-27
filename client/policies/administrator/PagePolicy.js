export default class PagePolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_pages
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_pages
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_pages
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_pages
    }
}