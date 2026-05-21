# API 参考

公开入口：

- `captcha()`
- `captcha_scope()`
- `captcha_check()`
- `pms\facade\Captcha`
- `pms\program\captcha\CaptchaConfig`
- `pms\program\captcha\Driver`

## captcha(array|CaptchaConfig $config = []): array

生成默认 scope 的验证码。

等价于：

```php
captcha_scope('', $config);
```

如果 `$config` 为空，使用 facade 中已经注入的全局配置。如果 `$config` 非空，会创建新的 `Driver` 并使用临时配置。

## captcha_scope(string $scope, array|CaptchaConfig $config = []): array

生成指定 scope 的验证码。

- `$scope` 会写入 AES 票据。
- 校验时必须传入相同 scope。
- 返回 `code`、`aes`、`image`。

## captcha_check(string $cache, string $code, string $scope = ''): bool

校验验证码。

参数名 `$cache` 对应生成阶段返回的 `aes` 字段。

校验失败返回 `false`，包括：

- `aes` 为空。
- AES 解密失败。
- scope 不一致。
- 已过期。
- 用户输入和票据中的 hash 不匹配。

## Captcha facade

`pms\facade\Captcha` 代理到 `pms\program\captcha\Driver`。

常用方法：

- `Captcha::setConfig(array|CaptchaConfig $config)`
- `Captcha::create(string $scope = '')`
- `Captcha::check(string $aes, string $code, string $scope = '')`

## Driver::setConfig(array|CaptchaConfig $config = []): static

设置 driver 配置。

- 传入 `CaptchaConfig` 时直接使用该对象。
- 传入数组时构造新的 `CaptchaConfig`。

## Driver::create(string $scope = ''): array

生成验证码图片和票据。

返回：

```php
[
    'code' => '...',
    'aes' => '...',
    'image' => 'data:image/png;base64,...',
]
```

内部流程：

1. 生成文本验证码或算术验证码。
2. 用 `password_hash()` 保存答案 hash。
3. 用 AES 加密 scope、时间、过期时间和答案 hash。
4. 用 GD 创建 PNG 图片。
5. 返回 Data URL。

## Driver::check(string $aes, string $code, string $scope = ''): bool

校验验证码。

内部流程：

1. 用配置中的 `password` 解密 `aes`。
2. JSON 解码票据。
3. 比较 scope。
4. 检查 `last_time` 是否过期。
5. 将用户输入转成小写。
6. 用 `password_verify()` 比对答案 hash。

## Driver::aesEncrypt(string $data, string $key): string

使用 `aes-256-cbc` 加密数据。

- `$key` 先经过 `hash('sha256', $key, true)` 派生。
- 每次加密生成随机 IV。
- 返回值是 `base64_encode($iv . $encrypted)`。

## Driver::aesDecrypt(string $data, string $key): bool|string

解密 `aesEncrypt()` 生成的数据。

异常时返回 `false`。

## CaptchaConfig

`CaptchaConfig` 提供配置 getter/setter，包括：

- `password`
- `length`
- `codeSet`
- `expire`
- `useZh`
- `zhSet`
- `math`
- `useImgBg`
- `fontSize`
- `useCurve`
- `useNoise`
- `fontttf`
- `bg`
- `imageH`
- `imageW`
- `alpha`
- `interfere`

构造函数只接收类中真实存在的属性。数组里不存在的配置项会被忽略。
