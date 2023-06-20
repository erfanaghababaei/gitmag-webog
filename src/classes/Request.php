<?php

namespace App\Classes;

/* این کلاس دیتاهایی که از طریق متد پست یا گت به سمت سرور ارسال می شوند را داخل ابجکتی از جنس این کلاس قرار می گیرند 
و ما در برنامه از ابجکت های ساخته شده توسط این کلاس استفاده کنیم و از طریق ابجکت های این کلاس به پارامترهای ارسالی کاربر دسترسی داشته باشیم*/
class Request
{
    /* بدلیل اینکه نمی دانیم چه پارامتر هایی از طرف کاربر فرستاده شده اند،نمی توانیم صفت هایی را بصورت مستقیم و به همان نام ایجاد کنیم 
    این کلاس بصورت کلی ایجاد شده یعنی هرجا بتواند اطلاعات ارسالی کاربر را بصورت کلی درون ابجکت خودش ذخیره کند 
    به همین دلیل باید از یک ارایه ای استفاده کنیم که صفت هارا درون ان ست کنیم و با استفاده از متدهای جادویی گت و ست این ارایه را مدیریت کنیم*/

    private $attributes = [];/*هر مقداری که بخواهد در ابجکت های ریکوئست ست شود درون این ارایه قرار می گیرد */
    private $method;/*متد درخواست زده شده در این صفت قرار می گیرد */
    private $url;/*از طریق این صفت به یو ار ال درخواست ارسال شده دسترسی خواهیم داشت */



    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];/*مقدار دهی اولیه متد با استفاده ار کلید سراسری ریکوئست */
        $this->url = 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];/*مقدار دهی اولیه صفت یو ار ال */

        /*هر پارامتر ارسال شده توسط کاربر را بصورت کی و ولیو داخل ارایه متغییر قرار می گیرد */

        /* برداشت کنیم$_POSTاگر متد ما پست بود باید پارامتر هارا از ارایه سراسری  */
        if($this->method == 'POST')
        {
            /* قرار می گیرد $_FILES  قرار نمی گیرد  و درون $_POST باشد و پارامتر ارسالی به سمت فرم مایک باینری فایل باشد دیگر ان باینری فایل درونmulti-part form فرم ماenk type اگر خاصیت */
            foreach($_POST as $key => $value)
            {
                $this->attributes[$key] = $value;
            }
            /*  ذخیره می شوند $_GET بود پارامتر ها درون ارایه سراسری  get اگر متد ما  */
            foreach($_FILES as $key => $value)
            {
                $this->attributes[$key] = new Upload($value);
            }
        }
        foreach($_GET as $key => $value)
        {
            $this->attributes[$key] = $value;
        }
    }

    /*ست کردیم را در اختیار داشته باشیم یعنی اگر ان ویژگی درون  متغییر وجود داشت ان متغییر را برگردان attribute هایی که درون  property با استفاده از متد جادویی زیر */
    public function __get($name)
    {
        if(array_key_exists($name, $this->attributes))
            return $this->attributes[$name];
        else
            return null;
    }

    /*انجام دهیم request هم اینکار رو از روی ابجکت ایجاد شده از کلاس  get را دریافت می کند و ان را برگشت می دهد یعنی  علاوه بر اینکه می توانیم بر روی ابجکت مستقیما اون ویژگی هارا فراخوانی کنیم و ببینیم چ مقداری بر روی آنهاست می توانیم ازطریق متد  property این متد در ورودی یک  */
    public function get($name)
    {
        if(array_key_exists($name, $this->attributes))
            return $this->attributes[$name];
        else
            return null;
    }

    /*به ما برمیگرداند true /false با استفاده از این متد می توانیم بسنجیم که یک پارامتر از طرف کاربر ارسال شده یا نه این متد در واقع یک  */
    public function has($name)
    {
        if(isset($this->attributes[$name]))
            return true;
        else
            return false;
    }

     /*را بدست اوریم method های متغییر  property با استفاده از این متد می توانیم  */
    public function getMethod()
    {
        return $this->method;
    }

    /*را بدست اوریم url های متغییر  property با اسفاده از این متد نیز می توانیم  */
    public function getUrl()
    {
        return $this->url;
    }
    
    /*تعریف نکردیم url یا  method برای  setter را ست بکنیم هیچ  http متد  request بدلیل اینکه نمیخواهیم بر روی ابجکت  */

    /*را بر می گرداند false را بر میگرداند و در غیر این صورت مقدار  true درست باشد مقدار  login اگر متد ارسالی درخواست کاربر برای  */
    public function isPostMethod()
    {
        return strtolower($this->method) == 'post';
    }
}