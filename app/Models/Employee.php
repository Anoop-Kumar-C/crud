<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{
    use HasFactory;
    protected $table='employees';
    protected $primaryKey = 'id';
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'photo','designation','password'];
    public function designation() {
        return $this->hasMany("App\Models\Designation", "id", "designation");
    }

}
