export default class PaymentPolicy
{
    static access(user)
    {
        let permissions = JSON.parse(user.role.permissions)
        return permissions.access_payment_gateways
    }
}