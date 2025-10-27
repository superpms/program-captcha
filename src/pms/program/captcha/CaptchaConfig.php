<?php

namespace pms\program\captcha;

class CaptchaConfig
{
    /**
     * @var string 验证码加密密码
     */
    protected string $password;

    /**
     * @var int 验证码位数
     */
    protected int $length = 5;

    /**
     * @var string 验证码字符集合
     */
    protected string $codeSet = '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';


    /**
     * @var int 验证码过期时间
     */
    protected int $expire = 1800;

    /**
     * @var bool 是否使用中文验证码
     */
    protected bool $useZh = false;

    // 中文验证码字符串
    protected string $zhSet = '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借';

    /**
     * @var bool 是否使用算术验证码
     */
    protected bool $math = false;

    /**
     * @var bool 是否使用背景图
     */
    protected bool $useImgBg = false;

    /**
     * @var int 验证码字符大小
     */
    protected int $fontSize = 25;

    /**
     * @var bool 是否使用混淆曲线
     */
    protected bool $useCurve = true;

    /**
     * @var bool 是否添加杂点
     */
    protected bool $useNoise = true;

    /**
     * @var string 验证码字体 不设置则随机
     */
    protected string $fontttf = '';

    /**
     * @var array 背景颜色
     */
    protected array $bg = [243, 251, 254];

    /**
     * @var int 验证码图片高度
     */
    protected int $imageH = 0;

    /**
     * @var int 验证码图片宽度
     */
    protected int $imageW = 0;

    /**
     * @var int 验证码图片透明度
     */
    protected int $alpha = 0;

    public function __construct(array $config = []){
        if(!empty($config)){
            foreach ($config as $key => $value){
                if(property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * @var array|string[] 验证码干扰项及处理方法
     */
    protected array $interfere = [
        'useImgBg' => 'background',
        'useCurve' => 'writeCurve',
        'useNoise' => 'writeNoise',
    ];

    /**
     * @return string
     * @throws \Exception
     */
    public function getPassword(): string
    {
        if(isset($this->password)){
            return $this->password;
        }
        throw new \Exception('Captcha 未设置 password');
    }


    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return $this
     */
    public function setLength(int $length): static
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeSet(): string
    {
        return $this->codeSet;
    }

    /**
     * @param string $codeSet
     * @return $this
     */
    public function setCodeSet(string $codeSet): static
    {
        $this->codeSet = $codeSet;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     * @return $this
     */
    public function setExpire(int $expire): static
    {
        $this->expire = $expire;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseZh(): bool
    {
        return $this->useZh;
    }

    /**
     * @param bool $useZh
     * @return $this
     */
    public function setUseZh(bool $useZh): static
    {
        $this->useZh = $useZh;
        return $this;
    }

    /**
     * @return string
     */
    public function getZhSet(): string
    {
        return $this->zhSet;
    }

    /**
     * @param string $zhSet
     * @return $this
     */
    public function setZhSet(string $zhSet): static
    {
        $this->zhSet = $zhSet;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMath(): bool
    {
        return $this->math;
    }

    /**
     * @param bool $math
     * @return $this
     */
    public function setMath(bool $math): static
    {
        $this->math = $math;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseImgBg(): bool
    {
        return $this->useImgBg;
    }

    /**
     * @param bool $useImgBg
     * @return $this
     */
    public function setUseImgBg(bool $useImgBg): static
    {
        $this->useImgBg = $useImgBg;
        return $this;
    }

    /**
     * @return int
     */
    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    /**
     * @param int $fontSize
     * @return $this
     */
    public function setFontSize(int $fontSize): static
    {
        $this->fontSize = $fontSize;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseCurve(): bool
    {
        return $this->useCurve;
    }

    /**
     * @param bool $useCurve
     * @return $this
     */
    public function setUseCurve(bool $useCurve): static
    {
        $this->useCurve = $useCurve;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUseNoise(): bool
    {
        return $this->useNoise;
    }

    /**
     * @param bool $useNoise
     * @return $this
     */
    public function setUseNoise(bool $useNoise): static
    {
        $this->useNoise = $useNoise;
        return $this;
    }

    /**
     * @return string
     */
    public function getFontttf(): string
    {
        return $this->fontttf;
    }

    /**
     * @param string $fontttf
     * @return $this
     */
    public function setFontttf(string $fontttf): static
    {
        $this->fontttf = $fontttf;
        return $this;
    }

    /**
     * @return array
     */
    public function getBg(): array
    {
        return $this->bg;
    }

    /**
     * @param array $bg
     * @return $this
     */
    public function setBg(array $bg): static
    {
        $this->bg = $bg;
        return $this;
    }

    /**
     * @return int
     */
    public function getImageH(): int
    {
        return $this->imageH;
    }

    /**
     * @param int $imageH
     * @return $this
     */
    public function setImageH(int $imageH): static
    {
        $this->imageH = $imageH;
        return $this;
    }

    /**
     * @return int
     */
    public function getImageW(): int
    {
        return $this->imageW;
    }

    /**
     * @param int $imageW
     * @return $this
     */
    public function setImageW(int $imageW): static
    {
        $this->imageW = $imageW;
        return $this;
    }

    /**
     * @return int
     */
    public function getAlpha(): int
    {
        return $this->alpha;
    }

    /**
     * @param int $alpha
     * @return $this
     */
    public function setAlpha(int $alpha): static
    {
        $this->alpha = $alpha;
        return $this;
    }

    /**
     * @return array
     */
    public function getInterfere(): array
    {
        return $this->interfere;
    }

    /**
     * @param array $interfere
     * @return $this
     */
    public function setInterfere(array $interfere): static
    {
        $this->interfere = $interfere;
        return $this;
    }
}
