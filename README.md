# superpms/program-captcha

`program-captcha` 是 superpms 的图片验证码包，提供 helper、`pms\facade\Captcha` facade、验证码配置对象和 GD 图片生成 driver。

当前实现是“图片 Data URL + AES 票据 + 本地校验”的轻状态验证码：明文答案不写入 session，校验信息被加密打包到 `aes` 载荷里。

## 要求

- PHP `>=8.1`
- `ext-mbstring`
- `ext-gd`
- `ext-openssl`
- 依赖 superpms 基础 facade、配置和生命周期能力

## 安装与挂载

```bash
composer require superpms/program-captcha
```

包的 `composer.json` 会：

- 通过 `autoload.files` 加载 `bin/autoload.php`
- 通过 PSR-4 暴露 `pms\` 命名空间
- 通过 `extra.pms.config` 声明把 `resource/config.php` 投影为项目 `captcha` 配置

`bin/autoload.php` 会先加载 `bin/helper.php`，再加载 `bin/autorun.php`。当框架存在 `pms\hook\LifecycleHook` 时，包会在 `LIFECYCLE_BOOT` 阶段执行：

```php
$config = config('captcha', []);
\pms\facade\Captcha::setConfig($config);
```

## 快速使用

生成验证码：

```php
$payload = captcha();

// $payload['image'] 是 data:image/png;base64,... 字符串
// $payload['aes'] 需要随用户输入一起提交回来
```

带 scope 生成和校验：

```php
$payload = captcha_scope('login');

$ok = captcha_check($payload['aes'], $userInput, 'login');
```

临时覆盖配置：

```php
$payload = captcha([
    'length' => 4,
    'expire' => 300,
    'useNoise' => false,
]);
```

## 返回结构

`create()` 和 helper 返回数组：

```php
[
    'code' => 'abcde',
    'aes' => '...',
    'image' => 'data:image/png;base64,...',
]
```

`code` 是当前源码仍然返回的明文验证码字段。对外接口是否透传该字段，应由接口层按安全要求决定。

## 主要模块

- `bin/helper.php`: `captcha()`、`captcha_scope()`、`captcha_check()` helper
- `bin/autorun.php`: 启动期读取 `config('captcha')` 并注入 facade
- `resource/config.php`: 默认配置模板
- `src/pms/facade/Captcha.php`: facade 入口
- `src/pms/program/captcha/CaptchaConfig.php`: 配置对象
- `src/pms/program/captcha/Driver.php`: 生成、加密、绘图和校验实现
- `assets/`: 背景图和字体资源

## Docs 导航

- [文档入口](docs/README.md)
- [快速接入](docs/guide/quick-start.md)
- [配置说明](docs/reference/config.md)
- [API 参考](docs/reference/api.md)
- [返回载荷与校验协议](docs/reference/payload-protocol.md)
- [运行流程](docs/internals/runtime-flow.md)
- [实现注意事项](docs/internals/implementation-notes.md)

## 注意事项

- 默认 `password` 是占位值，项目必须替换成自己的稳定密钥。
- 校验时必须传回生成阶段返回的 `aes`，不能只传用户输入。
- 生成和校验的 `scope` 必须完全一致。
- `api` 出现在默认配置里，但当前 `CaptchaConfig` 没有对应属性，传入后会被忽略。
- 临时传入配置时仍必须保证有 `password`，否则生成或校验会抛出 `Captcha 未设置 password`。
