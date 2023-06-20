<?php

namespace App\Models;
use App\Classes\Database;
use App\Exceptions\{DoesNotExistsException,InvalidMethodException};

abstract class Model
{
    /*، در این کلاس ابتدا باید ابجکتی از جنس کلاس دیتابیس داشته باشیم که تمامی دیتاهارا بخوانیم یا  اگر قرار هست دیتایی درون دیتا بیس ساخته شود از طریق ابجکت دیتابیس بتوانیم اینکار را انجام دهیم*/
    protected $database; /* یک ابجکت از دیتابیس ایجاد کرده و داخل ان ذخیره شود*/
    protected $fileName; /* فایل جیسونی که متد سازنده قرار هست ان را باز کند */
    protected $entityClass; /* کلاس موجودیت */

    public function __construct() /* ایجاد کردن ابجکت از دیتابیس */
    {
        $this->database = new Database($this->fileName,$this->entityClass);
    }

    /*این متد تمای دیتاهایی که از طریق دیتابیس خوانده شده را به ما برمی گرداند بدون اینکه هیچ تغییری روش انجام شده باشد*/
    public function getAllData()
    {
        return $this->database->getData();
    }

    /* این متد یک ایدی را دریافت می کند و بر اساس ان دیتایی را به ما برمی گرداند*/
    public function getDataById($id)
    {
        /*  ابتدا تمامی دیتا هارا با کمک متد گت دیتا از دیتا بیس برمی گردانیم*/
        $data = $this->database->getData();

        /* اکنون با استفاده از متد زیر بر اساس ایدی دیتاهارا فیلتر می کنیم*/
        $array  = array_filter($data,
        function ($item ) use ($id)
        {
            return $item->getId() == $id;
        }
        );

        /* با استفاده از متد زیر کلید های ارایه بالا را ریست می کنیم و تنها کلیدی که برگشت داده شده عضو صفر می باشد*/
        $data = array_values($array);

        if (count($data))
            return $data[0];
        else
            throw new DoesNotExistsException("There is not any {$this->entityClass}");
    }

    /* برای ایجاد یک پست جدید نیاز داریم که ایدی اخرین پست را بدانیم اکنون با این متد می توانیم ایدی اخرین پست را محاسبه کنیم و به اخرین دیتای یک فایل جیسون دسترسی داشته باشیم */
    public function getLastData()
    {
        /* این متد باید یک سورت بر روی دیتای اصلی ما داشته باشد تا بتواند به اخرین دیتا دست پیدا کند*/
        $data = $this->database->getData();
        uasort($data,
            function($first,$second)
            {
                /*این متد بر روی ارایه اصلی تغییر اعمال می کند و از ان چیزی برگشت نمی دهیم*/ 
                return ($first->getId() > $second->getId()) ? -1 :1;
            }
        );
        
        $data = array_values($data);
   
        if (count($data))
            return $data[0];
        else
            throw new DoesNotExistsException("There is not any {$this->entityClass}");
    }
    
    /*این متد برعکس متد قبلی ایدی اولین پست را می یابد*/ 
    public function getFirstData()
    {
        /* این متد نیز باید یک سورت بر روی دیتای اصلی ما داشته باشد تا بتواند به اخرین دیتا دست پیدا کند*/
        $data = $this->database->getData();
        uasort($data,
            function($first,$second)
            {
                return ($first->getId() < $second->getId() ? -1 : 1);
            }
        );
        $data = array_values($data);

        if (count($data))
            return $data[0];
        else
            throw new DoesNotExistsException("There is not any {$this->entityClass}");
    }

    /* این متد دیتاهارا مرتب می کند اما  نه بر اساس تاریخ یا تعداد بازدید،چون باید کد کمتری استفاده کنیم پس از کالبک فانکشنی استفاده می کنیم که قرار است در متد زیر سرت شود زیرا تفاوت مقادیر جیسون همین کالبک فانکشن می باشد*/
    public function sortData($method,$ascending = true)
    {
        if ($this instanceof Setting)
            throw new InvalidMethodException('The sortData method is not applicable to Setting objects');

        $allowedMethods = ['getId', 'getView', 'getDate'];

        if (!in_array($method, $allowedMethods))
            throw new \InvalidArgumentException("Invalid method: {$method}. Allowed methods are: " . implode(', ', $allowedMethods));
        
        $data = $this->database->getData();

        uasort($data,
            function($first,$second)use ($method,$ascending)
            {
                if ($method == 'getDate')
                    return ($ascending)? ($first->getTimestamp() < $second->getTimestamp()) : ($second->getTimestamp() < $first->getTimestamp());
                else
                    return ($ascending)? ($first->$method() < $second->$method()) : ($second->$method() < $first->$method());
            }
        );

        $data = array_values($data);

        if (count($data))
            return $data;
        else
            throw new DoesNotExistsException("There is not any {$this->entityClass}");
    }
    /* با استفاده از متد زیر دیتاهارا فیلتر می کنیم یعنی کالبک فانکشن را در ورودی دریافت می کنیم زیرا فیلتر کردن می تواند بر اساس صفت ها و ویژگی های یک شی اتفاق بیفتد */

    public function filterData($method,$value)
    {
        $data = $this->database->getData();
        /*در خروجی ارایه نتیجه را نشان می دهد*/

        $data = array_filter($data,
            function ($item)use($value,$method)
            {
                return str_contains($item->$method(),$value);
            }
        );

        $data = array_values($data);

        if (count($data))
            return $data;
        else
            throw new DoesNotExistsException("Counln't find {$value} in {$method}");
    }

 /* با استفاده از متد زیر یک داده جدید را می توانیم در دیتابیس بسازیم برای اینکار ابتدا یک شی از کلاس موجودیت می سازیم و این کلاس در ورودی یک ارایه را که حاوی اطلاعات ان کلاس هست را دریافت می کندو.
    وقتی این شی از کلاس موجودیت ایجاد شد ،این شی را به متد سازنده دیتاها می دهیم که این متد از طریق شی دیتابیس این موجودیت جدید را می سازد  و درون دیتابیس می نویسد*/
    public function createData($new)
    {
        $data = $this->database->getData();
        $data[] = $new;
        
        $this->database->setData($data);
    }

    /*این متد برای حذف یک داده از دیتابیس استفاده می شود،که ایدی ان دیتارا به عنوان ورودی دریافت می کند و همه دیتاهای موجود در دیتابیس را می گیرد و بعد از ان کافی است که فقط دیتایی که ایدی ان با متغییر ایدی است را فیلتر کند.
    و سپس با استفاده از ابجکت دیتا بیس، مقادیر جدید را درون دیتا بیس می نویسیم و ایدی ان دیتای حذف شده دیگر در مقادیر دیتابیس وجود نخواهد داشت*/
    public function deleteData($id)
    {
        $data = $this->database->getData();
        $newData = array_filter($data,
            function($item)use ($id)
            {
                return $item->getId() == $id ? false : true;
            }
        );
        $newData = array_values($newData);
        $this->database->setData($newData);
    }
/* این متد برای ویرایش مقادیر یک دیتا از دیتابیس کاربرد دارد.از طریق یک متغییر دیتای جدید را به این متد ارسال کرده و در این متد دیتایی که ایدی ان برابر  ایدی جدید هست را با متغییر جایگزین می کنیم
    سپس کلید هارا یک بار ریست می کنیم و با استفاده از این ابجکت دیتابیس و متد ست دیتا دیتای جدید را درون دیتابیس می نویسیم */ 
    public function editData($new)
    {
        $data = $this->database->getData();
        $newData = array_map(
            function($item)use ($new)
            {
                return $item->getId() == $new->getId() ? $new : $item;
            }
            ,$data
        );
        $newData = array_values($newData);
        $this->database->setData($newData);
    }
}