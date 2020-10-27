export default class SubscriptionPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_users_subscriptions
    }
}