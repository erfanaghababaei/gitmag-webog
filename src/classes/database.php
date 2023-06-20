<?php
namespace App\Classes;

/*وظیفه این کلاس این است که دیتاهای موجود در فایلهای -جیسون بخواند و اگر دیتای جدیدی داریم ، درون این کلاس بنویسدب*/

class Database
{
    private $databaseFileAddress;/* ادرس فایل جیسونی که قراره باز بشه */
    private $data;/*وقتی دیتاها خونده شد و به کلاس های متناظرشون مپ شد،اون اطلاعات داخل این متغییر قرار بگیره */
    
    public function __construct($filename,$entityClass)
    {
        /* ساخت ادرس فایل از نام فایل در زیر*/
        $this->databaseFileAddress = "./database/" .$filename . '.json';

        /*  باز کردن فایلی که ادرسش توی اون صفت بالا ذخیره شده و چون فقظ میخوایم اون رو بخونیم از فلگ ار پلاس استفاده می کنیم*/
        $file = fopen($this->databaseFileAddress,'r+');
        $database = fread($file,filesize($this->databaseFileAddress));
        fclose($file);

        /* تبدیل دیتاهای جیسون به ارایه انجمنی*/
        $data = json_decode($database,true);

        /* تبدیل دیتاهای جیسون قرار گرفته در ارایه به ابجکت هایی از پست انتیتی */
        $this->data = array_map(
            function($item)use($entityClass)
            {
                return new ($entityClass)($item);
            },
            $data
        );
    }

    /* اگر تغییراتی در دیتاهای ما اتفاق  افتاد،ان تغیرات را با استفاده از متد زیر درون دیتا بیس نوشته می شود 
    ورودی این متد دیتای جدیدی است که دیتاهای قدیمی را با تغییرات درون خود حفظ می کند*/ 
    public function setData($newData)
    {
    /* اگر از این ابجکت دیتابیس بعد از عمل نوشتن مجددا میخواستیم استفاده کنیم و دوباره عمل خواندن دیتابیس را انجام ندهیم ،دیتای جدید را در متغییر زیر می نویسیم*/

        $this->data = $newData;
    /* تبدیل کردن ابجکتها به ارایه ها تا توانایی تبدیل انها به دیتاهای جیسون و ویرایش انها را داشته باشیم*/
        $newData = array_map(
            function($item)
            {
                return $item->toArray();
            },
            $newData
        );

        /*حالا باید فایل جیسون رو باز کرد و دیتای جدید که از ارایه های انجمنی پست های جیسون تشکیل شده را درون ان فایل نوشت */
        $newData = json_encode($newData);

        $file = fopen($this->databaseFileAddress,"w+");
        fwrite($file,$newData);
        fclose($file);
        
        return $newData;
    }

    public function getData()
    {
        return $this->data;
    }
}