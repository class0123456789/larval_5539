<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Kind
 *  机构类型表 
 * @mixin \Eloquent
 */
class Kind extends Model
{
    protected $table='admin_kinds';
    protected $guard=[];
    protected $fillable = ['name'];
    public function institutions() {
        return $this->belongsTo(Institution::class);
    }
}
