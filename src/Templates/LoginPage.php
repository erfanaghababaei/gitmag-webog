<?php
namespace App\Templates;

use App\Models\User;
use App\Classes\Auth;
class LoginPage extends Template
{
    private $errors = [];

    /*باشد یعنی این اطلاعات را کاربر ارسال کرده و ما می توانیم این اطلاعات را اعتبار سنجی کرده و در صورت درست بودن اطلاعات عمل احراز حویت را انجام دهیم post اگر  متد get هست یا  post وقتی شی از این کلاس ساخته شد با متد سازنده زیر می توانیم بسنجیم که متد درخواست ارسالی  */
    public function __construct()
    {
        parent::__construct();

        /*شده یا نه Authenticate در این قسمت چک می شود که کاربر  */
        if(Auth::isAuthenicated())
            redirect('panel.php',['action' => 'posts']);
        $this->title  = $this->setting->getTitle()  . ' - login to system';
        if($this->request->isPostMethod())       
        {
            // دیتاها انجام می شود validation در کلاس زیر 
            $data = $this->validator->validate([
                'email'=>['required','email'],// شود  و درقدم بعد حتما باید ایمیل معتبر وارد شود required  نگهداری می شود در ابتدا حتما باید ایمیلvalidation های  rule در این قسمت 
                'password'=>['required','min:6'] //های آن باید حداقل 6 کاراکتر باشد mincharachter شود و  require باید  emailهم مانندا password
                            
            // ها  درصورتی که آن  در این آِرایه وجود داشته باشد،این متد فراخوانی شودrule در این کلاس متدی خواهیم داشت که به ازای هرکدام از این 
            /*برگشت داده شود که بتوان فهمید که اعتبار سنجی انجام شده باعث شده خطایی به وجود بیاید یا خیر validate در اینجا یک مقداری باید از  */
            ]);
            if(!$data->hasError())//وجود نداشت عمل لاگین انجام شود error اگر 
            {
                $userModel = new User();
                $user = $userModel->authenticationUser($this->request->email,$this->request->password);
                if($user)
                {
                    Auth::loginUser($user);
                    redirect('panel.php',['action'=>'posts']);
                }
                else
                {
                    $this->errors[] = 'Authentication failed';
                }
                    
            }
            else
            {
                $this->errors = $data->getErrors();
            }
        }
    }


    private function showErrors()
    {
        if(count($this->errors))
        {
            ?>
                <div class="errors">
                    <ul>
                        <?php foreach($this->errors as $error): ?>
                            <li><?= $error;count($this->errors) ?></li>
                        <?php endforeach; ?>
                    </ul>   
                </div>
            <?php
        }

            
        
    }

    public function renderPage()
    {
        ?>
            <html>
        <?php $this->getAdminHead()?>
        <body>
            <main>
                <form method='POST' action="<?= url('index.php',['action'=>'login'])?>">
                    <div class='login'>
                        <h3>Login to system</h3>
                        <?php $this->showErrors()?>
                        <div>
                            <label for="email">Email:</label>
                            <input type="text" id="email" name="email">
                        </div>
                        <div>
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password">
                        </div>
                        <div>
                            <input type="submit" value="Login">
                        </div>
                    </div>
                </form>
            </main>
        </body>
    </html>
        <?php
    }
}