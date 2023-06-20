<?php
namespace App\Templates;

use App\Models\Post;

/*این کلاس از کلاس تمپلیت ارث بری می کند در متد سازنده این کلاس،عملیات واکشی دیتاهارا از دیتا بیس انجام می دهیم و
،  در متد انتزاعی رندر پیج ،به ساختن پیج خود می پرداختیم و کدهای رندر پیج،صفحه اصلی سایت مارا می سازد*/
class MainPage extends Template
{
    private $topPosts;
    private $lastPosts;
    private $posts;
    public function __construct() /*این متد سازنده تگ هدر را پر می کند*/ 
    {
        parent::__construct();/* اجرا کردن متد سازنده بصورت دستی*/
        $this->title = $this->setting->getTitle();


        $postModel = new Post();

        $this->topPosts = $postModel->sortData("getView");

        $this->lastPosts = $postModel->sortData("getDate");

        $this->posts = $postModel->getAllData();
    }

    /* ما درون این متد هرچیزی که قرار بدهیم با فراخوانی کردن این متد بر روی اشیا ساخته شده از این کلاس درون خروجی به ما نمایش داده می شود در تگ زیر بخش های مختلف سایت دیده می شود*/
    public function renderPage()
    {
        ?>
            <html lang="en">
                <?php $this->getHead();?>
                <body>
                    <main>
                        <?php $this->getHeader()?>
                        <?php $this->getNabvar()?>
                        <section id="content">
                            <?php $this->getSidebar($this->topPosts,$this->lastPosts)?>
                                <div id="articles">

                                    <?php foreach($this->posts as $post):?>
                                        <article>
                                        <div class="caption">
                                            <h3><?= $post->getTitle()?></h3>
                                            <ul>
                                                <li>Date: <span><?=$post->getDate()?></span></li>
                                                <li>Views: <span><?=$post->getView()?></span></li>
                                            </ul>
                                            <p>
                                                <?= $post->getExcerpt()?>
                                            </p>
                                            <a href="<?= url('index.php',['action'=>'single','id'=>$post->getId()])?>">More...</a>
                                        </div>
                                        <div class="image">
                                            <img src="<?= assets($post->getImage())?>" alt="<?= $post->getTitle()?>">
                                        </div>
                                        <div class="clearfix"></div>
                                    </article>
                                    <?php endforeach; ?>
                                </div>
                                <div class="clearfix"></div>
                        </section>
                        <?php $this->getFooter()?>

                    </main>
                </body>
            </html>
        <?php
    }
}