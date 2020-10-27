import AdvertisementPolicy from '~/policies/owner/AdvertisementPolicy'
import CurrencyPolicy from '~/policies/owner/CurrencyPolicy'
import GeoPolicy from '~/policies/owner/GeoPolicy'
import MaintenancePolicy from '~/policies/owner/MaintenancePolicy'
import PaymentPolicy from '~/policies/owner/PaymentPolicy'
import PlanPolicy from '~/policies/owner/PlanPolicy'
import RolePolicy from '~/policies/owner/RolePolicy'
import ServicePolicy from '~/policies/owner/ServicePolicy'
import SettingsPolicy from '~/policies/owner/SettingsPolicy'
import SubscriptionPolicy from '~/policies/owner/SubscriptionPolicy'
import SupportPolicy from '~/policies/owner/SupportPolicy'
import ThemePolicy from '~/policies/owner/ThemePolicy'

export default class OwnerGate
{
    constructor()
    {
        this.policies = {
            advertisements: AdvertisementPolicy,
            currencies: CurrencyPolicy,
            geo: GeoPolicy,
            maintenance: MaintenancePolicy,
            payments: PaymentPolicy,
            plans: PlanPolicy,
            roles: RolePolicy,
            services: ServicePolicy,
            settings: SettingsPolicy,
            subscriptions: SubscriptionPolicy,
            support: SupportPolicy,
            themes: ThemePolicy
        };
    }

    before(user)
    {
        return user.id === 1
    }
    
    allow(user, action, type, model = null)
    {
        try {
            
            // Check if you are the main owner
            if (this.before(user)) {
                return true;
            }

            // Not Owner
            if (user.role.group !== 'owner') {
                return false
            }

            return this.policies[type][action](user, model);

        } catch (error) {
            console.log(type, action)
            return false
        }
    }

    deny(user, action, type, model = null)
    {
        return ! this.allow(user, action, type, model);
    }
}