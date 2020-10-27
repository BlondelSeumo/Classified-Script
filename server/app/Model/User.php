<?php

namespace App\Model;

use App\Model\ChatMessage;
use App\Model\Classified;
use App\Model\Comment;
use App\Model\Message;
use App\Model\Offer;
use App\Model\PasswordSecurity;
use App\Model\Plan;
use App\Model\Product;
use App\Model\Role;
use App\Model\Room;
use App\Model\Store;
use App\Model\Subscription;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Khsing\World\Models\City;
use Khsing\World\Models\Country;
use Khsing\World\Models\Division;
use QCod\ImageUp\HasImageUploads;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasImageUploads, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    

    const UNIQUE_ID = true;

    /**
     * Auto upload images
     * @var boolean
     */
    protected $autoUploadImages   = true;
    
    /**
     * Upload settings
     * @var [type]
     */
    protected static $imageFields = [
        'avatar'  => [
            'width'       => 200,
            'height'      => 200,
            'crop'        => true,
            'disk'        => 'local',
            'path'        => 'uploads/avatars',
            'placeholder' => null,
            'rules'       => 'image|mimes:png,jpg,jpeg|max:5000',
            'auto_upload' => true,
        ]
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 
        'email', 
        'password',
        'unique_id',
        'role_id',
        'plan_id',
        'email_verified_at',
        'country',
        'state',
        'city',
        'phone',
        'avatar',
        'isActive',
        'isPending',
        'isBlocked',
        'provider_name',
        'provider_id',
        'locale'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Set a default unique id
     * @var [type]
     */
    protected $attributes = [
        'unique_id' => self::UNIQUE_ID,
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Run boot function
     * @return [type] [description]
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model){
            $model->attributes['unique_id'] = uniqueId();
        });
    }


    /**
     * Scope a query to not include admin with id 1
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeisNotSuperAdmin($query)
    {
        return $query->where('id', '!=', 1);
    }


    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('isActive', true)->where('isPending', false);
    }


    /**
     * Check user role
     * @return [type] [description]
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }


    /**
     * Check if user has permission
     * @param  [type]  $permission [description]
     * @return boolean             [description]
     */
    public function hasPermission($permission)
    {
        $role = json_decode($this->role->permissions, true);

        return $role[$permission];
    }



    /**
     * Check if current user is super admin
     * @return boolean [description]
     */
    public function isSuperAdmin()
    {
        return $this->id === 1;
    }


    /**
     * Check if site owner
     * @return boolean [description]
     */
    public function isOwner()
    {
        if ($this->role->group == 'owner') {
            return true;
        }

        return false;
    }


    /**
     * Check if user is administrator
     * @return boolean [description]
     */
    public function isAdministrator()
    {
        if ($this->isOwner()) {
            return true;
        }elseif ($this->role->group == 'administrator') {
            return true;
        }else{
            return false;
        }
    }



    /**
     * Check if user is manager
     * @return boolean [description]
     */
    public function isManager()
    {
        if ($this->isOwner() || $this->isAdministrator()) {
            return true;
        }

        if (auth()->check() && $this->subscription()->exists()) {
            
            $subscription = $this->subscription()->first();

            // Check if subscription expired
            if (!now()->gte($subscription->expires_at)) {
               return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }



    /**
     * Check if online user a moderator
     * @return boolean [description]
     */
    public function isModerator()
    {
        if ($this->isOwner() || $this->isAdministrator()) {
            return true;
        }

        if ($this->role->group == 'moderator') {
            
            return true;

        }else{
            return false;
        }
    }



    /**
     * Can a user access a room
     * @param  [type] $roomId [description]
     * @return [type]         [description]
     */
    public function canJoinRoom($roomId)
    {
        // Get room
        return $this->hasOne(Room::class, 'sender_id');
    }


    /**
     * Two Factor Authentication
     * @return [type] [description]
     */
    public function passwordSecurity()
    {
        return $this->hasOne(PasswordSecurity::class);
    }


    /**
     * Get user avatar
     * @return [type] [description]
     */
    public function avatar()
    {
        // If default avatar
        if (is_null($this->avatar)) {
            return index('application/storage/app/uploads/default/avatar/noavatar.png');
        }

        return index('application/storage/app/'.$this->avatar);
    }


    /**
     * Get user country
     * @return [type] [description]
     */
    public function country()
    {
        return $this->hasOne('Khsing\World\Models\Country', 'id', 'country');
    }



    /**
     * Get user state
     * @return [type] [description]
     */
    public function state()
    {
        return $this->hasOne(Division::class, 'id', 'state');
    }



    /**
     * Get user city
     * @return [type] [description]
     */
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city');
    }



    /**
     * Get user package plan
     * @return [type] [description]
     */
    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }



    /**
     * Check if user register using 
     * Social media or Direct
     * @return [type] [description]
     */
    public function getRegisterService()
    {
        return is_null($this->provider_name) ? 'Site' : $this->provider_name;
    }



    /**
     * Get chat messages
     * @return [type] [description]
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'user_id');
    }



    /**
     * Get user received messages
     * @return [type] [description]
     */
    public function conversations()
    {
        return $this->hasMany(Message::class, 'user_to');
    }


    /**
     * Get product by user
     * @return [type] [description]
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }



    /**
     * Get user classifieds
     * @return [type] [description]
     */
    public function deals()
    {
        return $this->hasMany(Classified::class, 'user_id');
    }



    /**
     * Get user offers
     * @return [type] [description]
     */
    public function offers()
    {
        return $this->hasMany(Offer::class, 'to_id');
    }



    /**
     * Get shops by user
     * @return [type] [description]
     */
    public function shops()
    {
        return $this->hasMany(Store::class, 'user_id');
    }



    /**
     * Get comments by user
     * @return [type] [description]
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }


    /**
     * Get user subscription
     * @return [type] [description]
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }


    /**
     * Get only activated users
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeactivated($query)
    {
        $query->where('isActive', true);
    }


    /**
     * Get user followers
     *
     * @return void
     */
    public function followers()
    {
        return $this->hasManyThrough(
            'App\Model\Follower', 'App\Model\Store', 
            'user_id', 'store_id', 'id'
        );
    }


    /**
     * Get user deals statistics
     *
     * @return void
     */
    public function statistics()
    {
        return $this->hasManyThrough(
            'App\Model\ClassifiedStatistics', 'App\Model\Classified', 
            'user_id', 'deal_id', 'id'
        );
    }

}
