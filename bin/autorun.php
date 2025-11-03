<?php
if(class_exists('pms\hook\LifecycleHook')){
    \pms\hook\LifecycleHook::mount(LIFECYCLE_BOOT,function () {
        $config = config('captcha',[]);
        \pms\facade\Captcha::setConfig($config);
    });
}