<?php
\pms\hook\LifecycleHook::mount(function () {
    $config = config('captcha',[]);
    \pms\facade\Captcha::setConfig($config);
});
