<?php

namespace App\Models\ACL;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Components\Filters\QueryFilters;
use App\Models\ACL\Menu;
use App\Models\ACL\Role;
use App\Models\ACL\RolePermission;
use App\Models\ACL\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if(@\Auth::user()) $model['created_by'] = @\Auth::user()->id;
        });

        static::saving(function($model) {
            if(@\Auth::user()) $model['updated_by'] = @\Auth::user()->id;
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'photo_url',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        $data = [
            $this->email
        ];

        Mail::send('email.reset-password', [
            'name'      => $this->name,
            'url'     => route('password.reset', ['token' => $token, 'email' => $this->email]),
        ], function($message) use($data){
            $message->subject('Reset Password Request');
            $message->to($data[0]);
        });
    }

    public static function rule()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'role_id' => 'required|integer',
            'password' => 'required|confirmed|string',
            'active' => 'nullable|numeric',
            'photo_url' => 'nullable',
        ];
    }

    public static function ruleOnUpdate()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'role_id' => 'required|integer',
            'password' => 'nullable|confirmed|string',
            'active' => 'nullable|numeric',
            'photo_url' => 'nullable',
        ];
    }

    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getMenu()
    {
        $result = [];
        $searching = @$request->search ? true : false;

        // SECTIONS
        $sections = Menu::where('type', 'section')->orderBy('order')->get();
        foreach ($sections as $section) {
            
            $addSection = true;

            // CHECKING PERMISSIONS, SET FALSE IF NOT PERMITTED

            // GROUPS AND OR LINKS
            $groups = Menu::whereIn('type', ['group', 'link'])->where('parent_id', $section->id)->orderBy('order')->get();
            $groupDatas = [];

            foreach ($groups as $group) {
                
                $addGroup = true;

                // CHECKING PERMISSIONS, SET FALSE IF NOT PERMITTED
                if(@$group->permission_id){
                    $addGroup = @RolePermission::wherePermissionId($group->permission_id)->whereRoleId(@\Auth::user()->role_id)->first()->access ? true : false;
                }

                // LINKS
                $links = Menu::where('type', 'link')->where('parent_id', $group->id)->orderBy('order')->get();
                $linkDatas = [];

                foreach ($links as $link) {

                    $addLink = true;

                    // CHECKING PERMISSIONS, SET FALSE IF NOT PERMITTED
                    if(@$link->permission_id){
                        $addLink = @RolePermission::wherePermissionId($link->permission_id)->whereRoleId(@\Auth::user()->role_id)->first()->access ? true : false;
                    }

                    // CHECK MASTER
                    if(master()) $addLink = true;

                    // IS MENU HIDDEN
                    if($link->is_hidden) $addLink = false;

                    if($addLink) $linkDatas[] = $link;
                    if(!$addGroup && $addLink) $addGroup = true;
                }

                if(count($linkDatas) > 0) $group['links'] = $linkDatas;
                if($group->type == 'group' && count($linkDatas) == 0) $addGroup = false;
                if($links->count() > 0 && count($linkDatas) == 0) $addGroup = false;

                // CHECK MASTER
                if(master()) $addGroup = true;

                // IS MENU HIDDEN
                if($group->is_hidden) $addGroup = false;

                if($addGroup) $groupDatas[] = $group;
                if(!$addSection && $addGroup) $addSection = true;
            }

            if(count($groupDatas) > 0){
                $section['groups'] = $groupDatas;
            }else{
                $addSection = false;
            }

            // CHECK MASTER
            if(master()) $addSection = true;

            // IS MENU HIDDEN
            if($section->is_hidden) $addSection = false;

            if($addSection) $result[] = $section;
        }

        // ADD SYSTEM (MASTER MENU)
        if(\Auth::user()->role->is_master){
            $system = [
                'label' => 'System',
                'type' => 'section',
                'groups' => 
                [
                    [
                        'label' => 'Setting',
                        'type' => 'link',
                        'icon' => '<i class="ki-duotone ki-setting-2 text-muted fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>',
                        'url' => 'setting'
                    ],
                    [
                        'label' => 'Menu',
                        'type' => 'link',
                        'icon' => '<i class="ki-duotone ki-data">
                                     <i class="path1"></i>
                                     <i class="path2"></i>
                                     <i class="path3"></i>
                                     <i class="path4"></i>
                                     <i class="path5"></i>
                                    </i>',
                        'url' => 'menu'
                    ],
                    [
                        'label' => 'Permission',
                        'type' => 'link',
                        'icon' => '<i class="ki-duotone ki-shield">
                                     <i class="path1"></i>
                                     <i class="path2"></i>
                                    </i>',
                        'url' => 'permission'
                    ]
                ]
            ];

            $result[] = $system;
        }

        return $result;
    }
}
