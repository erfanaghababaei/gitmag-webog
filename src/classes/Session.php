<?php
namespace App\Classes;

/*  تعریف می کنیم تا دسترسی ما به این متدها ساده تر شود و دیگر نیاز نداشته باشیم که هرجا از این کلاس اسفاده می کنیم،یک شی از روی آن ایجاد کنیم static  ها گردش بین کلاسها و متدهای مختلف وجود داشته باشد،متد های این کلاس را بصورت property نداریم که داخل این  property هارا مدیریت کنیم .چون در این کلاس گردش داده ای نداریم یعنی  Session   این کلاس به ما کمک می کند تا بتوانیم */
class Session
{
        //بیرون می کشد session با استفاده از نام آن  $_SESSION را از آرایه  session  این متد یک 
    public static function get($name)
    {
        if(isset($_SESSION[$name]))
            return $_SESSION[$name];
        return null;
    }

    //را درون برنامه خود تعریف کنیم session با استفاده از این متد می توانیم یک 
    /*هست session که مقدار آن  value هست ویک  session می گیردکه نام آن  name این متد در ورودی اولیه خود یک متغییر  */
    public static function set($name,$value)
    {
        $_SESSION[$name] =$value; 
    }

    //نشان بدهد rtue false در برنامه ما هست یا خیر و مقدار session با استفاده از این متد می توانیم چک کنیم که یک
    public static function has($name)
    {
        if(isset($_SESSION[$name]))
            return true;
        return false;

    }

    //بکند unset را از بین برد یا session  این یک متدی است که با استفاده از ان می توان یک 
    public static function forget($name)
    {
        unset($_SESSION[$name]);
        return true;
    }
    
    //در سیستم ما انجام می شود action وجود داشت مقدار آن را برگرداند و اگر وجود نداشت مقدار آن را ایجاد کند این متد برای نشان دادن و نمایش دادن پیام های مربوط به اعمال و  session  را فلاش کنیم یعنی اگر  session می توانیم یک  flash با استفاده از متد 
    public static function flush($name,$value = null)
    {
        if(self::has($name))
        {
            $session = self::get($name);
            self::forget($name);
            return $session;
        }
        else
            self::set($name,$value);
    }
}