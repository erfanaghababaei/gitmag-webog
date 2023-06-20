<?php

namespace App\Models;

use App\Entities\PostEntity;

class Post extends Model
{
    /* این کلاس صفت های پروتکتد مورد نیاز را  مقدار دهی می کند*/
    protected $fileName  = 'posts';
    protected $entityClass = PostEntity::class;
}