# 实现注意事项

## password 是必需配置

`CaptchaConfig::getPassword()` 在没有设置 `password` 时会抛出：

```text
Captcha 未设置 password
```

框架内通常由项目 `captcha` 配置提供。临时传入配置时，如果数组不包含 `password`，不会自动继承全局配置。

## 图片尺寸行为

`create()` 当前会在 `imageW` 或 `imageH` 为非零时重新计算它们：

```php
if ($this->config->getImageW()) {
    $this->config->setImageW(...);
}

if ($this->config->getImageH()) {
    $this->config->setImageH(...);
}
```

默认配置中的 `imageW=80`、`imageH=30` 会触发重新计算。直接使用 `new CaptchaConfig()` 时默认宽高是 `0`，需要调用者提供可用尺寸或使用项目默认配置。

## 背景图路径

包内背景图实际在 `assets/bgs/`。

当前 `Driver::background()` 使用：

```php
__DIR__ . '/../assets/bgs/'
```

该路径和字体路径写法不一致。默认 `useImgBg=false`，所以常规流程不会触发背景图读取。开启 `useImgBg` 前需要先核对并修正背景路径。

## api 配置目前无效

`resource/config.php` 中有 `api` 字段，但 `CaptchaConfig` 中没有对应属性。

构造函数只写入 `property_exists($this, $key)` 为真的字段，所以 `api` 当前会被忽略。

## 明文 code 字段

`create()` 返回的 `code` 来源于绘制文本。普通验证码、中文验证码和算术验证码都会把当前展示内容返回。

如果上层接口直接透传完整返回值，客户端会拿到明文验证码。正式对外接口应按需要过滤。

## 字体资源

字体目录：

- 普通验证码: `assets/ttfs/`
- 中文验证码: `assets/zhttfs/`

当 `fontttf` 为空时，driver 会扫描目录并随机选择 `.ttf` 或 `.otf` 文件。

## 自定义干扰项

`interfere` 支持方法名或闭包。

闭包签名由调用处决定：

```php
function ($im, $imageW, $imageH, $fontSize, $color) {
    // draw custom noise
}
```

使用闭包时要直接操作 GD 图像资源。

## 错误与返回

`check()` 大多数异常会被转换为 `false`：

- AES 解密异常
- `password_verify()` 异常

但 `create()` 过程中的配置缺失、字体路径、GD 绘图问题不都会被吞掉。开发或部署时应先确认扩展和资源文件可用。
