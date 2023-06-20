<?php

namespace App\Models;
use App\Entities\UserEntity;

class User extends Model
{
    /* این کلاس صفت های پروتکتد مورد نیاز را  مقدار دهی می کند*/
    protected $fileName = 'users';
    protected $entityClass = UserEntity::class;

    /*انجام بدهیم user model را انجام بدهد و اینکار را باید در  authenticate را وارد سیستم بکنیم پس باید یک متدی داشته باشیم که بحث  user انجام شد  باید validation وقتی  */
    public function authenticationUser($email,$password)
    {
        //می باشد user entity قرار گرفته یک شی از کلاس  item مقداری که درون متغییر 
        $data =  $this->database->getData();
        $user = array_filter($data,
        function ($item)use($email,$password)
        {
            if($item->getEmail() == $email and $item->getPassword() == $password)
                return true;
            return false;   
        });
        
        /*شوند reset ,user را انجام دهیم تا این کلیدها در متغییر  array values اکنون باید  */
        $user = array_values($user);
        if(count($user))
            return $user[0];
    }
}