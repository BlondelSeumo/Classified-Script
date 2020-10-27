export default class MessagePolicy
{
    static send(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.send_messages
    }

    static receive(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.receive_messages
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_messages
    }
}