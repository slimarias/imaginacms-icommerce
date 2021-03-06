<?php

namespace Modules\Icommerce\Entities;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ProductTranslation extends Model
{
    use Sluggable;
    public $timestamps = false;
    protected $fillable = [
      'name',
      'description',
      'summary',
      'slug',
      'meta_title',
      'meta_description'
    ];
    protected $table = 'icommerce__product_translations';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function setMetaDescriptionAttribute($value){

        if(empty($value)){
            $this->attributes['meta_description'] = substr(strip_tags($this->summary??''),0,150);
        }else{
            $this->attributes['meta_description'] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function setMetaTitleAttribute($value){

        if(empty($value)){
            $this->attributes['meta_title'] = $this->name??'';
        }else{
            $this->attributes['meta_title'] = $value;
        }
    }


}
