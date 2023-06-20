<?php

namespace App\Templates;

use App\Exceptions\NotFoundException;
use App\Models\Post;

/*و یک پست که با استفاده از ایدی ان در دسترس باشد top post , last post در سینگل پیج ما  */
class SinglePage extends Template
{
    private $post;
    private $topPosts;
    private $lastPosts;

    /* قرار میدادیم تا در دسترس باشد  setting property یک شی ایجاد می کردیم و ان را در  setting در متد سازنده والد ما از روی 
    می توانیم ان را دریافت کنیم request آن پست را دریافت کنیم که از طریق کلاس  id ما نیاز داریم  */
    public function __construct()
    {
        parent::__construct();

        /*که می تواند چک کند یک پارامتر ارسال شده است یا خیر has  متدی بنام  request  با توجه به اینکه  */
        if (!$this->request->has('id'))
            throw new NotFoundException('page not found!');

        //id عملیات واکشی پست از دیتابیس با استفاده از     
        $id = $this->request->id;
        $postModel = new Post();
        $this->post = $postModel->getDataById($id);

        //از دیتابیس  last post و  top post واکشی کردن 
        $this->title = $this->setting->getTitle() . ' - ' . $this->post->getTitle();

        $this->topPosts = $postModel->sortData('getView');

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

                                        <article>
                                        <div class="caption">
                                            <h3><?= $this->post->getTitle()?></h3>
                                            <ul>
                                                <li>Date: <span><?=$this->post->getDate()?></span></li>
                                                <li>Views: <span><?=$this->post->getView()?></span></li>
                                            </ul>
                                            <p>
                                                <?= $this->post->getContent()?>
                                            </p>
                                        </div>
                                        <div class="image">
                                            <img src="<?= assets($this->post->getImage())?>" alt="<?= $this->post->getTitle()?>">
                                        </div>
                                        <div class="clearfix"></div>
                                    </article>
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