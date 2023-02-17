<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RestfulAPI extends Model
{
    use HasFactory;

    public function response($model, $request, $queries = null, $randomRecord = null, $makeHiddens = null, $isCustom = false)
    {
        return Helper::searchByQuery($model, $request, $queries, $randomRecord, $makeHiddens, $isCustom);
    }
}
