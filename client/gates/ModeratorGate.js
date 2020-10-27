import CommentPolicy from '~/policies/moderator/CommentPolicy'
import DealPolicy from '~/policies/moderator/DealPolicy'
import ShopPolicy from '~/policies/moderator/ShopPolicy'

export default class ModeratorGate
{
    constructor()
    {
        this.policies = {
            comments: CommentPolicy,
            deals: DealPolicy,
            shops: ShopPolicy
        };
    }

    before(user)
    {
        return user.role.group === 'owner' || user.role.group === 'administrator'
    }

    allow(user, action, type, model = null)
    {
        // Check for owner or admin
        if (this.before(user)) {
            return true;
        }

        // Not moderator
        if (user.role.group !== 'moderator') {
            return false
        }

        return this.policies[type][action](user, model);
    }

    deny(user, action, type, model = null)
    {
        return ! this.allow(user, action, type, model);
    }
}