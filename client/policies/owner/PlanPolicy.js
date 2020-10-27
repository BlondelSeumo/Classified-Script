export default class PlanPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_plans
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_plans
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_plans
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_plans
    }
}