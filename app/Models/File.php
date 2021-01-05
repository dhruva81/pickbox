<?php

namespace App\Models;

use App\Models\Obj;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable =['name', 'size', 'path'];

    public function object()
    {
        return $this->belongsTo(Obj::class);
    }

    public static function booted()
    {
        static::creating(function($model){
            $model->uuid = Str::uuid();
        });

        static::deleting(function($model){
            Storage::disk('local')->delete($model->path);
        });
    }

    public function sizeForHumans()
    {
        $bytes = $this->size;
        $units = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

        for($i=0; $bytes > 1024; $i++)
        {
            $bytes /= 1024;
        }

        return round($bytes, 2) . $units[$i];
    }
}
