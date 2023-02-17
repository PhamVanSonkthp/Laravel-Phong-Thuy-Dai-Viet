<?php

namespace App\Models;

use App\Components\Recusive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = [];

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'permission_role' , 'role_id' , 'permission_id');
    }

    public function getRole($parent_id = null){
        $data = Role::all();
        $recusive = new Recusive($data);
        $htmlOption = $recusive->categoryRecusive($parent_id);

        return $htmlOption;
    }

    public function getTableName()
    {
        return Helper::getTableName($this);
    }

    public function searchByQuery($request, $queries = [])
    {
        return Helper::searchByQuery($this, $request, $queries);
    }

    public function storeByQuery($request, $isApi = false)
    {
        $dataInsert = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender_id' => $request->gender_id ?? 1,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
        ];

        if ($this->isAdmin()){
            $dataInsert['role_id'] = $request->role_id ?? 0;
            $dataInsert['is_admin'] = $request->is_admin ?? 0;
        }

        $item = $this->create($dataInsert);

        return $this->findById($item->id);
    }

    public function updateByQuery($id, $request, $isApi = false)
    {
        try {
            DB::beginTransaction();
            $updatetem = [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'gender_id' => $request->gender_id ?? 1,
                'email_verified_at' => $request->verify_email ? now() : null,
            ];

            if (!empty($request->password)) {
                $updatetem['password'] = Hash::make($request->password);
            }

            $this->find($id)->update($updatetem);
            $item = $this->find($id);
            $item->roles()->sync($request->role_id);
            DB::commit();

            return $this->findById($item->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . 'Line' . $exception->getLine());
            return null;
        }
    }

    public function findById($id, $isApi = false)
    {
        $item = $this->find($id);
        $item->gender;
        $item->role;
        return $item;
    }

}
