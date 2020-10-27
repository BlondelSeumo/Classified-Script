export default class ServicePolicy
{
    static sms(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_sms_services
    }

    static clouds(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_clouds
    }
}