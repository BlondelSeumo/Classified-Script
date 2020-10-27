export default class ConversationPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_conversations
    }

    static chat(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_chat
    }

    static users(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_users_messages
    }

    static admins(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_admin_messages
    }
}