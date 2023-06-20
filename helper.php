<?php

const BASE_URL = 'http://localhost:8000/';

/* این تابع ظاهر بهتری برای مقادیر جیسون تبدیل شده به یک شی از کلاس موجودیت را می سازد*/
function cleaner($data)
{
    die("<pre>" . print_r($data,true) . "</pre>"); 
}

function assets($file)
{
    return BASE_URL . 'assets/' . $file;
}

/* هایمان مارا درست راهنمایی کنند  url های سایتمان داشته باشیم تا  url  اکشن را در  query string تعریف شود زیرا برای آنها مشکل پیش می آید یعنی ما باید همیشه  static های ما نمی تواندبصورت  url داشته باشد  get یا روتینگ باید یک اکشن  url چون مسیر دهی  */
// .دریافت شده است یا خیر query string می باشد در بدنه این تابع چک می شود که query string تابع زیر این مشکل را حل می کند که دو ورودی دارد ،پارامتر اول مسیر و پارامتر دوم ارایه
function url($path,$query =[])
{
    if(!count($query))
        return BASE_URL . $path;
    else
        return BASE_URL . $path . '?' . http_build_query($query);
}

function redirect($path,$query = [])
{
    $url = url($path,$query);
    header("location: $url ");
    exit;
}

function deleteFile($file)
{
    if(file_exists('./assets/' . $file))
    {
        unlink('./assets/' . $file);
        return true;
    }
    return false;
}