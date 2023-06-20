<?php


namespace App\Templates;

use App\Exceptions\NotFoundException;   
use App\Models\Post;

/*باشد category query string انها باید برابر با  category و پست هایی داشته باشیم که  top post , last post در این صفحه ما باید  */

class CategoryPage extends Template
{
    private $posts;
    private $topPosts;
    private $lastPosts;
    public function __construct()
    {
        /*وجود داشته باشد category string ان در  post هست که می خواهیم  category در واقع حاوی  category وجود داشته باشد. پارامتر  category پارامتر  query string ابتدا متد سازنده والد را تعریف کرده و سپس می سنجیم که در  */
        parent::__construct();

        /*وجود دارد یا نه query string  در  category در اینجا چک می شود که ایا   */
        if(!$this->request->has("category"))
            throw new NotFoundException("page not found!");
      
        /*را میخوانیم که ببینیم وجود دارد یا نه category , query string  در اینجا از  */    
        $category = $this->request->category;
        $this->title = $this->setting->getTitle() . ' - ' . $category;

        $postModel = new Post();

        /*از کلاس مدل پست با متد فیلتر object  ساخت  */
        $this->posts = $postModel->filterData('getCategory',$category);
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