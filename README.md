# SetOnlineWorker
Устанавливает онлайн каждые n минут определенному пользователю.

# Stand-alone использование
```php
use VKauto\Auth\Auth;
use VKauto\CaptchaRecognition\Captcha;
use VKauto\Workers\SetOnlineWorker\SetOnline;

$account = Auth::directly('+79057151171', 'password');
$account->captcha = new Captcha(Captcha::AntiCaptchaService, 'API key');

$worker = new SetOnline(10, $account);
$worker->start();
```
