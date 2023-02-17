<?php

namespace App\Models;

use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use App\Traits\UserTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use UserTrait;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use \Awobaz\Compoships\Compoships;
    use DeleteModelTrait;
    use StorageImageTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [
//        'is_admin',
    ];

//    protected $guarded = [];

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

    // begin

    public function userType(){
        return $this->hasOne(UserType::class,'id','user_type_id');
    }

    // end

    public function getTableName()
    {
        return Helper::getTableName($this);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['image_path_avatar'] = $this->avatar();
        $array['path_images'] = $this->images;
        return $array;
    }

    public function avatar($size = "100x100"){
        $image = $this->image;
        if (!empty($image)){
            return Formatter::getThumbnailImage($image->image_path,$size);
        }

        return config('_my_config.default_avatar');
    }

    public function image(){
        return $this->hasOne(SingleImage::class,'relate_id','id')->where('table' , $this->getTable());
    }

    public function images(){
        return $this->hasMany(Image::class,'relate_id','id')->where('table' , $this->getTable())->orderBy('index');
    }

    public function gender()
    {
        return $this->belongsTo(GenderUser::class);
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function checkPermissionAccess($permissionCheck)
    {
        if (optional(auth()->user())->is_admin == 2) return true;

        $roles = optional(auth()->user())->roles;
        foreach ($roles as $role) {
            $permissions = $role->permissions;
            if ($permissions->contains('key_code', $permissionCheck)) {
                return true;
            }
        }
        return false;
    }

    public function isAdmin(){
        return auth()->check() && optional(auth()->user())->is_admin == 2;
    }

    public function isEmployee(){
        return auth()->check() && optional(auth()->user())->is_admin != 0;
    }

    public function searchByQuery($request, $queries = [])
    {
        return Helper::searchByQuery($this, $request, $queries);
    }

    public function storeByQuery($request)
    {
        $dataInsert = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_type_id' => $request->user_type_id,
            'date_of_birth' => $request->date_of_birth,
            'gender_id' => $request->gender_id ?? 1,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
        ];

        if ($this->isAdmin()){
            $dataInsert['role_id'] = $request->role_id ?? 0;
            $dataInsert['is_admin'] = $request->is_admin ?? 0;
        }

        $item = Helper::storeByQuery($this, $request, $dataInsert);

        if (!empty($request->is_admin && $request->is_admin == 1 && isset($request->role_ids))){
            $item->roles()->attach($request->role_ids);
        }

        return $this->findById($item->id);
    }

    public function updateByQuery($request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'user_type_id' => $request->user_type_id,
        ];

        if (!empty($request->date_of_birth)){
            $dataUpdate['date_of_birth'] = $request->date_of_birth;
        }

        if (!empty($request->gender_id)){
            $dataUpdate['gender_id'] = $request->gender_id;
        }

        if (!empty($request->password)) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $item = Helper::updateByQuery($this, $request, $id, $dataUpdate);

        if ($item->is_admin != 0 && isset($request->role_ids)){
            $item->roles()->sync($request->role_ids);
        }

        return $item;
    }

    public function deleteByQuery($request, $id, $forceDelete = false)
    {
        return Helper::deleteByQuery($this, $request, $id, $forceDelete);
    }

    public function deleteManyByIds($request, $forceDelete = false)
    {
        return Helper::deleteManyByIds($this, $request, $forceDelete);
    }

    public function findById($id)
    {
        $item = $this->find($id);
        $item->gender;
        $item->role;
        return $item;
    }
}
