<?php

namespace App\Templates;

use App\Exceptions\NotFoundException;
use App\Models\Post;

/*موجود ذخیره شود property این دو را تعریف می کنیم تا دیتای مربوط به این دو داخل دو  property را در سایدبار خود داریم  top post & last post  ذخیره شوند وچون  property خواهیم داشت که نتیجه سرچ ما در این  post به نام  property خواهیم داشت بنابراین یک  post در این صفحه مسلما یسری  */
/*  قرار میگیرد در حقیقت همان کلمه یا عبارتی است که باید سرچ شود و تایتل صفحه برابر با ان می شودquery string قرار میگیرد و مقداری که داخل این  url داخل  word مثلا  query string سازکار عملکرد صفحه سرچ به این صورت است که یک   */
class SearchPage extends Template
{
    private $posts;
    private $topPosts;
    private $lastPosts;
    public function __construct()
    {
        parent::__construct();

        if(!$this->request->has('word'))
            throw new NotFoundException('page not found!');

        $word = $this->request->word;
        $this->title = $this->setting->getTitle() . ' - result for:' . $word;

        $postModel = new Post();
        $this->posts = $postModel->filterData('getTitle',$word);

        //از دیتابیس top post واکشی کردن و سورت شدن 
        $this->topPosts = $postModel->sortData('getView');

        //از دیتابیس last post واکشی کردن و سورت شدن 
        $this->lastPosts = $postModel->sortData('getDate');
    }
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