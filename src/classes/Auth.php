<?php
namespace App\Classes;

use App\Entities\UserEntity;

/*در این کلاس نخواهیم داشت  property در این کلاس گردش دیتا نخواهیم داشت و از این کلاس مانند یک جعبه ابزار استفاده خواهیم کرد،پس هیچ  */
/*خواهیم پرداخت user login , user log out,دسترسی داریم یا خیر sesseion که لاگین شده به اطلاعاتش از  user شده یا خیر ویا  login و در این کلاس به  چک کردن این که آیا کاربری */
class Auth
{
    public static function loginUser($user)
    {
        Session::set('user',$user->toArray());
    }
    public static function logoutUser()
    {
        Session::forget('user');
        redirect('index.php',['action'=>'login']);
    }

     /*ذخیره شده دست پیدا کنیم session که در  user object با متد زیر می توانیم به */
    public static function getLoggedInUser()
    {
        return new UserEntity(Session::get('user'));
    }
    
    /*کرده باشد باید وارد صفحه پنل شودlogin user کرده یا خیر اگر  login درون سیستم  user با متد زیر می توانیم بفهمیم که  */
    public static function isAuthenicated()
    {
        return Session::has('user')? true : false;
    }

        /*می کند redirect ,login نشده بود متد زیر کاربر را به صفحه  Authenticate درصورتی که کاربر  */
    public static function checkAuthenticated()
    {
        if(! self::isAuthenicated())
            redirect('index.php',['action' => 'login']);
    }
}   