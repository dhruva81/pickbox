<?php

namespace App\Models;

use auth;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use App\Traits\RelatesToTeams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Obj extends Model
{
    use HasFactory, RelatesToTeams, HasRecursiveRelationships;
    use Searchable;

    public $table = 'objects';
    public $asYouType = true;

    protected $fillable =['parent_id', 'path'];
    
    public function objectable()
    {
        return $this->morphTo();
    }

    public static function booted()
    {
        static::creating(function($model){
            $model->uuid = Str::uuid();
        });

        static::deleting(function($model){
            optional($model->objectable)->delete();
            $model->descendants->each->delete();
        });

    }

    public function children()
    {
        return $this->hasMany(Obj::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Obj::class, 'parent_id', 'id');
    }

    // public function ancestors()
    // {
    //     $ancestor = $this;
    //     $ancestors = collect();

    //     while($ancestor->parent)
    //     {
    //         $ancestor = $ancestor->parent;
    //         $ancestors->push($ancestor);
    //     }
    //     return $ancestors;
    // }

}
