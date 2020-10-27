import BlogPolicy from '~/policies/administrator/BlogPolicy'
import CategoryPolicy from '~/policies/administrator/CategoryPolicy'
import ConversationPolicy from '~/policies/administrator/ConversationPolicy'
import FieldPolicy from '~/policies/administrator/FieldPolicy'
import PagePolicy from '~/policies/administrator/PagePolicy'
import UserPolicy from '~/policies/administrator/UserPolicy'
import CommentPolicy from '~/policies/administrator/CommentPolicy'
import DealPolicy from '~/policies/administrator/DealPolicy'
import ShopPolicy from '~/policies/administrator/ShopPolicy'

export default class AdministratorGate
{
    constructor()
    {
        this.policies = {
            comments: CommentPolicy,
            deals: DealPolicy,
            shops: ShopPolicy,
            blog: BlogPolicy,
            fields: FieldPolicy,
            pages: PagePolicy,
            users: UserPolicy,
            conversations: ConversationPolicy,
            categories: CategoryPolicy
        };
    }

    before(user)
    {
        return user.role.group === 'owner'
    }

    allow(user, action, type, model = null)
    {
        try {
            // Check for owner
            if (this.before(user)) {
                return true;
            }

            // Not Administrator
            if (user.role.group !== 'administrator') {
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