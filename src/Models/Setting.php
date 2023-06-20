<?php

namespace App\Models;

use App\Entities\SettingEntity;

class Setting extends Model
{
    /* این کلاس صفت های پروتکتد مورد نیاز را  مقدار دهی می کند*/
    protected $fileName = "setting";
    protected $entityClass = SettingEntity::class;
}