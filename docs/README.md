# program-captcha docs

这是 `superpms/program-captcha` 面向开发者的详细文档入口。

## 先读

- [快速接入](guide/quick-start.md): 安装、配置投影、helper 用法
- [返回载荷与校验协议](reference/payload-protocol.md): `code`、`aes`、`image` 和 `scope` 的协议
- [源码清单与包入口](reference/source-inventory.md): composer、bin、config、src 覆盖清单

## 按问题读

- 想核对 composer、bin、config、src 清单: [源码清单与包入口](reference/source-inventory.md)
- 想改验证码长度、过期时间、字体、图片尺寸: [配置说明](reference/config.md)
- 想查 helper、facade、driver 方法: [API 参考](reference/api.md)
- 想知道配置如何在启动期生效: [运行流程](internals/runtime-flow.md)
- 想排查图片生成、AES 校验、背景图、临时配置问题: [实现注意事项](internals/implementation-notes.md)

## 不在这里读

- HTTP 路由、业务登录、注册流程不属于本包。
- 前端展示和表单提交流程只需要遵守本包的返回载荷协议。
- Composer 安装投影命令本身不在本包实现，本包只声明 `extra.pms.config`。
