<?php
namespace App\Templates;

class ErrorPage extends Template
{
    private $message;
    public function __construct($message)
    {
        parent::__construct();

        $this->message = $message;
        $this->title = $message;
    }
    public function renderPage()
    {
        ?>
            <!DOCTYPE html>
            <html lang="en">
                <?php $this->getHead()?>
            <body>
                <main>
                    <section id='content'>
                        <div>
                            <?= $this->message?>
                            <br>
                            <a href='<?= url('index.php')?>'>Go to home</a>
                        </div>

                    </section>
                </main>
            </body>
            </html>
        <?php
    }
}