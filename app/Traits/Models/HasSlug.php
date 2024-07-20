<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{

    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? str($model->{self::slugFrom()})->append(time() . random_int(0, 12))->slug();
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
