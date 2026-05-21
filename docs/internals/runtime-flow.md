# 运行流程

## Composer 加载链

1. Composer 读取 `composer.json`。
2. `autoload.files` 执行 `bin/autoload.php`。
3. `bin/autoload.php` 先加载 `bin/helper.php`。
4. `bin/autoload.php` 再加载 `bin/autorun.php`。
5. `bin/autorun.php` 检查 `pms\hook\LifecycleHook` 是否存在。
6. 如果存在，挂载 `LIFECYCLE_BOOT` 回调。
7. 启动阶段回调读取 `config('captcha', [])`。
8. 回调执行 `Captcha::setConfig($config)`。

## 配置投影链

包通过 `composer.json` 声明：

```json
{
  "extra": {
    "pms": {
      "config": {
        "captcha": "resource/config.php"
      }
    }
  }
}
```

安装 hook 会把 `resource/config.php` 投影到项目配置中的 `captcha` 逻辑配置。运行期 `config('captcha', [])` 再读取该配置。

## 生成流程

`create($scope)` 的核心步骤：

1. 调用 `generate($scope)`。
2. 根据配置选择普通字符、中文字符或算术验证码。
3. 生成答案 hash，并加密 scope、签发时间、过期时间、答案 hash。
4. 创建 GD 图像。
5. 分配背景色和字体颜色。
6. 选择字体资源。
7. 按配置绘制背景、曲线、噪点。
8. 写入验证码字符。
9. 输出 PNG 内容并转成 base64 Data URL。
10. 返回 `code`、`aes`、`image`。

## 校验流程

`check($aes, $code, $scope)` 的核心步骤：

1. 空 `aes` 直接失败。
2. 用配置 `password` 解密 `aes`。
3. 解密失败直接失败。
4. JSON 解码票据。
5. 比较传入 scope 和票据 scope。
6. 检查过期时间。
7. 将用户输入转小写。
8. 用 `password_verify()` 校验答案。

## helper 与 facade 的差异

空配置调用：

```php
captcha_scope('login');
```

会走 `pms\facade\Captcha::create('login')`，使用启动期注入的全局配置。

非空配置调用：

```php
captcha_scope('login', ['password' => 'secret']);
```

会创建新的 `pms\program\captcha\Driver`，并使用这份临时配置。它不会读取 facade 中已有配置。
