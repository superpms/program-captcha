# 配置说明

默认配置文件：`resource/config.php`。

配置在安装投影后由项目配置系统读取，并在 `LIFECYCLE_BOOT` 注入 `Captcha` facade。

## password

```php
'password' => '<PASSWORD>',
```

AES 票据加密口令。项目必须替换默认占位值。

同一个验证码从生成到校验必须使用同一个 `password`。不同环境、不同节点或临时配置不一致时，`aes` 无法正确解密或校验会失败。

## length

验证码长度，默认 `5`。

算术验证码开启时，源码会把长度设置为 `5`，并使用类似 `12 + 3 =` 的表达式。

## codeSet

普通字符验证码的字符集合。

当 `useZh=false` 且 `math=false` 时，生成逻辑会从 `codeSet` 中随机取字符。

## expire

验证码有效期，单位秒，默认 `1800`。

生成时会把 `time() + expire` 写入 AES 票据的 `last_time` 字段。

## useZh

是否使用中文验证码，默认 `false`。

开启后：

- 字符来自 `CaptchaConfig` 内置的 `zhSet`。
- 字体目录使用 `assets/zhttfs/`。
- 如果同时开启 `math`，源码会把 `useZh` 改回 `false`。

## math

是否使用算术验证码，默认 `false`。

开启后：

- 表达式为 `$x + $y =`。
- `$x` 范围是 `10..30`。
- `$y` 范围是 `1..9`。
- 校验答案是两数之和。

## useImgBg

是否使用背景图，默认 `false`。

注意：当前 `Driver::background()` 使用的背景路径和包内 `assets/bgs/` 路径不一致，开启前需要核对实现路径。

## fontSize

验证码字体大小，默认 `25`。

图片宽高自动计算也会使用该值。

## useCurve

是否绘制混淆曲线，默认 `true`。

## useNoise

是否绘制噪点，默认 `true`。

## fontttf

指定字体文件名。

- 空字符串表示随机选择字体。
- 普通验证码从 `assets/ttfs/` 选择 `.ttf` 或 `.otf`。
- 中文验证码从 `assets/zhttfs/` 选择 `.ttf` 或 `.otf`。

## bg

背景色 RGB 数组，默认 `[243, 251, 254]`。

## imageH / imageW

图片高度和宽度。

默认配置给出：

```php
'imageH' => 30,
'imageW' => 80,
```

当前 `create()` 中的实现会在它们为非零值时按字体和长度重新计算宽高。

## alpha

背景透明度，默认 `0`。

传给 `imagecolorallocatealpha()`。

## api

默认配置包含：

```php
'api' => false,
```

但当前 `CaptchaConfig` 没有 `api` 属性，构造函数只接收 `property_exists()` 为真的字段。因此该配置项目前不会生效。

## 自定义干扰项

`CaptchaConfig` 内部有 `interfere` 数组：

```php
[
    'useImgBg' => 'background',
    'useCurve' => 'writeCurve',
    'useNoise' => 'writeNoise',
]
```

可以通过 `setInterfere()` 替换。数组值可以是方法名，也可以是 `Closure`。闭包会收到图像实例、宽、高、字体大小和颜色。
