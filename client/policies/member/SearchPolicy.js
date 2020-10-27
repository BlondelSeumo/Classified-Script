export default class SearchPolicy
{
    static save(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.save_search
    }
}