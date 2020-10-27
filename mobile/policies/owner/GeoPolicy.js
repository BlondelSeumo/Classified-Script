export default class GeoPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_geolocation
    }

    static create(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.create_geolocation
    }

    static edit(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.edit_geolocation
    }

    static delete(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.delete_geolocation
    }
}