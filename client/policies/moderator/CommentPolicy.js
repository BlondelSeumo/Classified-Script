export default class CommentPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_comments
    }

    static approve(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.approve_comments
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_comments
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_comments
    }

    static reported(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.reported_comments
    }
}