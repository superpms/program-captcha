# 返回载荷与校验协议

## 创建返回结构

`captcha()`、`captcha_scope()` 和 `Driver::create()` 返回：

```php
[
    'code' => 'abcde',
    'aes' => '...',
    'image' => 'data:image/png;base64,...',
]
```

字段含义：

- `code`: 当前源码返回的明文验证码。
- `aes`: 加密票据，校验时必须原样传回。
- `image`: PNG Data URL，可直接给前端展示。

## AES 票据内容

生成阶段会把以下 JSON 加密：

```php
[
    'scope' => $scope,
    'time' => $currentTime,
    'last_time' => $currentTime + $expire,
    'key' => password_hash($answer, PASSWORD_BCRYPT, ['cost' => 10]),
]
```

加密算法：

- `aes-256-cbc`
- key 由配置 `password` 经过 SHA-256 派生
- IV 每次随机生成
- 返回值是 IV 和密文拼接后的 base64 字符串

## scope

`scope` 用来隔离验证码用途。

生成：

```php
$payload = captcha_scope('login');
```

校验：

```php
$ok = captcha_check($payload['aes'], $input, 'login');
```

校验时传入 scope 必须和票据中的 scope 完全一致，否则返回 `false`。

## 答案大小写

普通验证码生成时会把答案转成小写再 hash。校验时用户输入也会用：

```php
mb_strtolower($code, 'UTF-8')
```

因此普通字母验证码大小写不敏感。

## 有效期

票据中的 `last_time` 小于当前时间时，校验返回 `false`。

有效期来自配置 `expire`，默认 `1800` 秒。

## 无 session 校验

当前实现不把答案写入 session、缓存或数据库。

校验所需信息都在 `aes` 票据中，服务端只需要持有相同的 `password` 配置。

## 明文 code 字段

当前源码仍返回 `code` 字段。这对开发调试方便，但对公开接口不一定安全。

如果上层接口面向真实用户，应评估是否过滤该字段，只向客户端返回 `aes` 和 `image`。
