# 快速接入

## 安装

```bash
composer require superpms/program-captcha
```

包声明了：

```json
{
  "autoload": {
    "files": ["bin/autoload.php"],
    "psr-4": {
      "pms\\": "src/pms/"
    }
  },
  "extra": {
    "pms": {
      "config": {
        "captcha": "resource/config.php"
      }
    }
  }
}
```

安装投影流程会把包内 `resource/config.php` 作为项目 `captcha` 配置模板。目标项目已经存在对应配置时，不会被覆盖。

## 启动期配置注入

`bin/autoload.php` 加载顺序：

1. `bin/helper.php`
2. `bin/autorun.php`

`bin/autorun.php` 在 `LIFECYCLE_BOOT` 中读取：

```php
$config = config('captcha', []);
\pms\facade\Captcha::setConfig($config);
```

因此在框架内使用 `captcha()`、`captcha_scope()`、`captcha_check()` 前，应确保项目 `captcha` 配置已经存在并包含有效 `password`。

## 生成验证码

```php
$payload = captcha();
```

返回：

```php
[
    'code' => 'abcde',
    'aes' => '...',
    'image' => 'data:image/png;base64,...',
]
```

前端通常展示 `image`，并在提交时带回 `aes` 和用户输入。

## 使用 scope 隔离场景

```php
$payload = captcha_scope('login');
```

校验时必须使用相同 scope：

```php
$ok = captcha_check($aes, $code, 'login');
```

如果生成时 scope 是 `login`，校验时传空字符串或其他 scope，结果会是 `false`。

## 临时覆盖配置

helper 接受数组或 `CaptchaConfig`：

```php
$payload = captcha([
    'password' => 'project-secret',
    'length' => 4,
    'expire' => 300,
    'useCurve' => false,
]);
```

传入非空配置时，helper 会创建新的 `Driver` 实例，不使用 facade 中已经注入的全局配置。这个临时配置必须包含生成所需的关键字段，尤其是 `password` 和可用图片尺寸。

## 当前项目引用现状

当前 server 端只看到包内 helper/facade 自身引用，以及 `server/app/system/http_router.php` 中一条已注释的验证码路由示例。

这说明本文档只描述包级开发用法，不描述某个业务端的验证码接口。
