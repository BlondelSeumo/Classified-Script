import CommentPolicy from '~/policies/member/CommentPolicy'
import MessagePolicy from '~/policies/member/MessagePolicy'
import OfferPolicy from '~/policies/member/OfferPolicy'
import ReportPolicy from '~/policies/member/ReportPolicy'
import SearchPolicy from '~/policies/member/SearchPolicy'
import SeePolicy from '~/policies/member/SeePolicy'
import ShopPolicy from '~/policies/member/ShopPolicy'

export default class ModeratorGate
{
    constructor()
    {
        this.policies = {
            comments: CommentPolicy,
            messages: MessagePolicy,
            offers: OfferPolicy,
            reports: ReportPolicy,
            search: SearchPolicy,
            see: SeePolicy,
            shops: ShopPolicy
        };
    }

    before(user)
    {
        return user.role.group === 'owner' || user.role.group === 'administrator' || user.role.group === 'moderator'
    }

    allow(user, action, type, model = null)
    {
        // Check for owner or admin
        if (this.before(user)) {
            return true;
        }

        return this.policies[type][action](user, model);
    }

    deny(user, action, type, model = null)
    {
        return ! this.allow(user, action, type, model);
    }
}