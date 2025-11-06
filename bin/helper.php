<?php

use pms\program\captcha\CaptchaConfig;

/**
 * @param array|CaptchaConfig $config
 * @return array
 */
function captcha(array|CaptchaConfig $config = []): array
{
    return captcha_scope("", $config);
}

/**
 * 创建一个范围性验证码
 * @param string $scope
 * @param array|CaptchaConfig $config
 * @return array
 */
function captcha_scope(string $scope, array|CaptchaConfig $config = []): array
{
    if (empty($config)) {
        return \pms\facade\Captcha::create($scope);
    }
    return (new \pms\program\captcha\Driver())->setConfig($config)->create($scope);
}

/**
 * 验证码验证
 * @param string $cache
 * @param string $code
 * @param string $scope
 * @return bool
 */
function captcha_check(string $cache, string $code, string $scope = ''): bool
{
    return \pms\facade\Captcha::check($cache, $code, $scope);
}