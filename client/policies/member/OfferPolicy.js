export default class OfferPolicy
{
    static make(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.make_offers
    }

    static receive(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.receive_offers
    }
}