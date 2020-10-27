export default class BlogPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_blog
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_articles
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_articles
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_articles
    }
}