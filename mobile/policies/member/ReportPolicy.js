export default class ReportPolicy
{
    static deals(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.report_classifieds
    }

    static comments(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.report_comments
    }
}