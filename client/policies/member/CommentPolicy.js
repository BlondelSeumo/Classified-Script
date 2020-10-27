export default class CommentPolicy
{
    static write(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.write_comments
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
}