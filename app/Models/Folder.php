<?php

namespace App\Models;

use App\Models\Obj;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Folder extends Model
{
    use HasFactory;

    protected $fillable =['name'];
    
    public function object()
    {
       return $this->belongsTo(Obj::class);
    }
    
    public static function booted()
    {
        static::creating(function($model){
            $model->uuid = Str::uuid();
        });
    }
}
