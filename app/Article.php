<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function scopeSelection($query)
    {
        return $query->select(
            'id',
            'doctorId',
            'title_' . app()->getLocale() . ' as title',
            'description_' . app()->getLocale() . ' as description_',
            'specialityId',
            'image',
            'created_at',
            'updated_at'       
        );
    }

}
