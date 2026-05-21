# 源码清单与包入口

本文记录 `superpms/program-captcha` 的包级入口、自动加载声明、配置投影和一方源码文件，作为功能覆盖核查的基准。

## composer.json

| 项 | 值 |
| --- | --- |
| `autoload.files` | `bin/autoload.php` |
| `autoload.psr-4` | `pms\\` -> `src/pms/` |
| `extra.pms.config` | `captcha` -> `resource/config.php` |
| `bin` | 无 |

## bin 文件

| 文件 | 作用 |
| --- | --- |
| `bin/autoload.php` | Composer files 入口，先加载 helper，再加载 autorun |
| `bin/helper.php` | 定义 `captcha()`、`captcha_scope()`、`captcha_check()` |
| `bin/autorun.php` | 如果存在 `LifecycleHook`，在 `LIFECYCLE_BOOT` 读取 `config('captcha', [])` 并注入 `Captcha` facade |

## resource/config.php

`resource/config.php` 提供默认验证码配置，并通过 `extra.pms.config.captcha` 投影为项目侧 `captcha` 配置。配置项包括 `password`、`length`、`codeSet`、`expire`、`useZh`、`math`、`useImgBg`、`fontSize`、`useCurve`、`useNoise`、`fontttf`、`bg`、`imageH`、`imageW`、`alpha`、`api`。

注意：`api` 存在于默认配置中，但当前 `CaptchaConfig` 没有同名属性，构造时会被忽略。

## src 一方源码

| 文件 | 公开功能面 |
| --- | --- |
| `src/pms/facade/Captcha.php` | `Captcha` facade，代理 `pms\program\captcha\Driver` |
| `src/pms/program/captcha/CaptchaConfig.php` | 验证码配置对象和 getter/setter；支持字符集、中文、算术题、字体、图片尺寸、干扰项等配置 |
| `src/pms/program/captcha/Driver.php` | 验证码 driver；提供 `setConfig`、`create`、`check`、`aesEncrypt`、`aesDecrypt` |

## 覆盖入口

- 使用入口见 [快速接入](../guide/quick-start.md)。
- helper、facade、driver 和配置对象见 [API 参考](api.md)。
- 配置项见 [配置说明](config.md)。
- 返回结构、AES 票据和 scope 协议见 [返回载荷与校验协议](payload-protocol.md)。
- 加载链、配置投影和生成/校验流程见 [运行流程](../internals/runtime-flow.md)。
