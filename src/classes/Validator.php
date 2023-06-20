<?php

namespace App\Classes;

class Validator
{
    private $errors = [];//هارا درون خودش نگهداری می کند error آرایه ای که 
    private $request;//مقداری ارسالی کاربر در هر فیلد
    
    public function __construct($request)
    {
        $this->request = $request;
    }

    //متد اعتبار سنجی دیتاها
    public function validate($array)
    {
        /*می زنیم foreach برای اینکه آرایه اصلی را تقسیم کنیم و به ازای هر فیلد یکبار اعمال موردنظر را انجام دهیم ،بر روی آرایه کلی یک */
        
        foreach($array as $field=>$rules){
            if(in_array('optional',$rules) and !$this->request->{$field}->isFile())
                continue;
            foreach($rules as $rule){
                if($rule == 'optional') continue;
                if(str_contains($rule,':')){
                    $rule  = explode(':',$rule);
                    $ruleName = $rule[0];
                    $ruleValue = $rule[1];

                    if($error = $this->{$ruleName}($field,$ruleValue))
                    {
                        $this->errors[$field]= $error;
                        break;
                    }
                }else
                {
                    if($error = $this->{$rule}($field))
                    {
                        $this->errors[$field]= $error;
                        break;
                    }
                }
            }
        }
        return $this;
    }

    //نمایش می دهد false را بر می گرداند و در غیر این صورت  true وجود داشت مقدار  error هیچ مقداری وجود دارد یا خیر پس اگر  error property این متد می سنجد که داخل 
    public function hasError()
    {
        return count($this->errors) ? true : false;
    }

    //است error propertu getter  این متد 
    public function getErrors()
    {
        return $this->errors;
    }

    // این متد فیلدی که به عنوان ورودی  ارسال شده است داخل آن مقداری قرار دارد یا خیر
    private function required($field)
    {
        if(is_null($this->request->get($field)))
            return "please fill $field";
        if(empty($this->request->get($field)))
            return "please fill $field";

        return false;
    }
        //چک می نماییم filter var ایمیل را با استفاده از 

    private function email($field)
    {
        if(!filter_var($this->request->{$field},FILTER_VALIDATE_EMAIL))
            return "`$field` is not valid";
        return false;
    }
    
    //در این بخش چک می کنیم که مقدار ورودی اول از مقدار ورودی دوم بیشتر باشد
    private function min($field,$value)
    {
        if(strlen($this->request->get($field))<$value)
            return "`$field` chars length is less than `$value`";

        return false;

    }
    private function max($field,$value)
    {
        if(strlen($this->request->get($field))>$value)
            return "`$field` chars length is more than `$value`";
        return false;

    }
    //قرار گرفته یا نه category در این بخش چک می کنیم که مقادیر داخل 
    private function in($field,$items)
    {
        $items = explode(',',$items);

        if(!in_array($this->request->{$field},$items))
            return "selected $field is invalid";
        return false;
    }

    //می توانیم پست جدیدی بارگذاری کنیم به همین خاطر دراینجا بررسی می کنیم که سایز آن فایل بارگذاری شده معتبر هست یانه creat post میدانیم که درصفحه 
    private function size($field,$len)
    {
        if($this->request->{$field}->getSize()> $len)
            return "$field is too large to upload.it must be smaller than $len";

        return false;
    }

    //در اینجا بررسی می کنیم که نوع پستی که کاربر میخواهد در پست جدید بارگذاری کند داخل ایتم های پیش فرض وجود دارد یا نه
    private function type($field,$types)
    {
        $types = explode(',',$types);
        if(!in_array($this->request->{$field}->getExtension(),$types))
            return "'$field' format is invalid.";
        return false;

    }
    //شدن فایل پست می پردازیم instance در اینجا به 
    private function file($field)
    {
        if(!$this->request->{$field} instanceof Upload)
           return "'$field' is not a file!";

        return false;
    }
}