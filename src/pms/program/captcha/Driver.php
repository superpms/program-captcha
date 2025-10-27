<?php

namespace pms\program\captcha;

use Closure;
use GdImage;
use pms\facade\RDb;

class Driver
{
    /**
     * @var false|GdImage 验证码图片实例
     */
    private false|GdImage $im;

    /**
     * @var false|int 验证码字体颜色
     */
    private false|int $color;

    protected CaptchaConfig $config;

    /**
     * 设置配置
     * @param array|CaptchaConfig $config
     * @return $this
     */
    public function setConfig(array|CaptchaConfig $config = []): static
    {
        if ($config instanceof CaptchaConfig) {
            $this->config = $config;
        } else {
            $this->config = new CaptchaConfig($config);
        }
        return $this;
    }

    protected function getCacheName(string $key, string $scope = ''): string
    {
        if ($scope !== '') {
            $scope = $scope . ':';
        }
        return 'captcha:' . $scope . $key;
    }

    public function aesEncrypt(string $data, string $key): string
    {
        $method = 'aes-256-cbc';
        $key = hash('sha256', $key, true); // 哈希派生256位密钥
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function aesDecrypt($data, $key): bool|string
    {
        try{
            $method = 'aes-256-cbc';
            $key = hash('sha256', $key, true);
            $data = base64_decode($data);
            $iv = substr($data, 0, openssl_cipher_iv_length($method));
            $encrypted = substr($data, openssl_cipher_iv_length($method));
            return openssl_decrypt($encrypted, $method, $key, OPENSSL_RAW_DATA, $iv);
        }catch (\Throwable $e){
            return false;
        }
    }

    /**
     * 创建验证码
     * @return array
     */
    protected function generate(string $scope = ''): array
    {
        $bag = '';
        if ($this->config->getMath()) {
            $this->config->setUseZh(false);
            $this->config->setLength(5);

            $x = random_int(10, 30);
            $y = random_int(1, 9);
            $bag = "{$x} + {$y} = ";
            $key = $x + $y;
            $key .= '';
        } else {
            if ($this->config->getUseZh()) {
                $characters = preg_split('/(?<!^)(?!$)/u', $this->config->getZhSet());
            } else {
                $characters = str_split($this->config->getCodeSet());
            }

            for ($i = 0; $i < $this->config->getLength(); $i++) {
                $bag .= $characters[random_int(0, count($characters) - 1)];
            }
            $key = mb_strtolower($bag, 'UTF-8');
        }

        $currentTime = time();

        $aes = $this->aesEncrypt(json_encode([
            'scope'=>$scope,
            'time'=>$currentTime,
            'last_time'=>$currentTime + $this->config->getExpire(),
            'key'=>password_hash($key, PASSWORD_BCRYPT, [
                'cost' => 10,
            ]),
        ]), $this->config->getPassword());
        return [
            'code' => $bag,
            'aes' => $aes,
        ];
    }

    /**
     * 验证验证码是否正确
     * @access public
     * @param string $code 用户验证码
     * @return bool 用户验证码是否正确
     */
    public function check(string $aes, string $code, string $scope = ''): bool
    {
        if(empty($aes)){
            return false;
        }
        $data = $this->aesDecrypt($aes, $this->config->getPassword());
        if($data === false){
            return false;
        }
        $data = json_decode($data, true);
        if($scope !== $data['scope']){
            return false;
        }
        if ($data['last_time'] < time()) {
            return false;
        }
        $key = $data['key'];
        $code = mb_strtolower($code, 'UTF-8');
        try{
            return password_verify($code, $key);
        }catch (\Throwable $e){
            return false;
        }
    }

    /**
     * 输出验证码并把验证码的值保存的session中
     * @access public
     * @param string $scope
     * @return array
     */
    public function create(string $scope = ''): array
    {
        $generator = $this->generate($scope);
        if ($this->config->getImageW()) {
            $this->config->setImageW((int)($this->config->getLength() * $this->config->getFontSize() * 1.5 + $this->config->getLength() * $this->config->getFontSize() / 2));
        }
        if ($this->config->getImageH()) {
            $this->config->setImageH((int)($this->config->getFontSize() * 2.5));
        }

        // 建立一幅 $this->$this->config->getImageW() x $this->config->getImageH() 的图像
        $this->im = imagecreate($this->config->getImageW(), $this->config->getImageH());

        $bg = $this->config->getBg();
        // 设置背景
        imagecolorallocatealpha($this->im, $bg[0], $bg[1], $bg[2], $this->config->getAlpha());

        // 验证码字体随机颜色
        $this->color = imagecolorallocate($this->im, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));

        // 验证码使用随机字体
        $ttfPath = __DIR__ . '/../../../../assets/' . ($this->config->getUseZh() ? 'zhttfs' : 'ttfs') . '/';

        if (empty($this->config->getFontttf())) {
            $dir = dir($ttfPath);
            $ttfs = [];
            while (false !== ($file = $dir->read())) {
                if (str_ends_with($file, '.ttf') || str_ends_with($file, '.otf')) {
                    $ttfs[] = $file;
                }
            }
            $dir->close();
            $this->config->setFontttf($ttfs[array_rand($ttfs)]);
        }

        $fontttf = $ttfPath . $this->config->getFontttf();

        // 添加干扰项
        foreach ($this->config->getInterfere() as $type => $method) {
            $fnName = 'get' . ucfirst($type);
            if ($method instanceof Closure) {
                $method($this->im, $this->config->getImageW(), $this->config->getImageH(), $this->config->getFontSize(), $this->color);
            } elseif ($this->config->$fnName()) {
                $this->$method();
            }
        }

        // 绘验证码
        $text = $this->config->getUseZh() ? preg_split('/(?<!^)(?!$)/u', $generator['code']) : str_split($generator['code']); // 验证码

        foreach ($text as $index => $char) {
            $x = $this->config->getFontSize() * ($index + 1) * ($this->config->getMath() ? 1 : 1.5);
            $y = $this->config->getFontSize() + mt_rand(10, 20);
            $angle = $this->config->getMath() ? 0 : mt_rand(-40, 40);

            imagettftext($this->im, (int)$this->config->getFontSize(), $angle, (int)$x, (int)$y, $this->color, $fontttf, $char);
        }

        ob_start();
        // 输出图像
        imagepng($this->im);
        $content = ob_get_clean();
        imagedestroy($this->im);
        return [
            'code' => implode('', $text),
            'aes' => $generator['aes'],
            'image' => 'data:image/png;base64,' . base64_encode($content),
        ];
    }

    /**
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数)
     *
     *      高中的数学公式咋都忘了涅，写出来
     *        正弦型函数解析式：y=Asin(ωx+φ)+b
     *      各常数值对函数图像的影响：
     *        A：决定峰值（即纵向拉伸压缩的倍数）
     *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     *        ω：决定周期（最小正周期T=2π/∣ω∣）
     *
     */
    protected function writeCurve(): void
    {
        $px = $py = 0;

        // 曲线前部分
        $A = mt_rand(1, (int)($this->config->getImageH() / 2)); // 振幅
        $b = mt_rand((int)(-$this->config->getImageH() / 4), (int)($this->config->getImageH() / 4)); // Y轴方向偏移量
        $f = mt_rand((int)(-$this->config->getImageH() / 4), (int)($this->config->getImageH() / 4)); // X轴方向偏移量
        $T = mt_rand($this->config->getImageH(), $this->config->getImageW() * 2); // 周期
        $w = (2 * M_PI) / $T;

        $px1 = 0; // 曲线横坐标起始位置
        $px2 = mt_rand((int)($this->config->getImageW() / 2), (int)($this->config->getImageW() * 0.8)); // 曲线横坐标结束位置

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->config->getImageH() / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->config->getFontSize() / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, (int)($px + $i), (int)($py + $i), $this->color); // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多
                    $i--;
                }
            }
        }

        // 曲线后部分
        $A = mt_rand(1, (int)($this->config->getImageH() / 2)); // 振幅
        $f = mt_rand((int)(-$this->config->getImageH() / 4), (int)($this->config->getImageH() / 4)); // X轴方向偏移量
        $T = mt_rand($this->config->getImageH(), $this->config->getImageW() * 2); // 周期
        $w = (2 * M_PI) / $T;
        $b = $py - $A * sin($w * $px + $f) - $this->config->getImageH() / 2;
        $px1 = $px2;
        $px2 = $this->config->getImageW();

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->config->getImageH() / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->config->getFontSize() / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, (int)($px + $i), (int)($py + $i), $this->color);
                    $i--;
                }
            }
        }
    }

    /**
     * 画杂点
     * 往图片上写不同颜色的字母或数字
     */
    protected function writeNoise(): void
    {
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        for ($i = 0; $i < 10; $i++) {
            //杂点颜色
            $noiseColor = imagecolorallocate($this->im, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
            for ($j = 0; $j < 5; $j++) {
                // 绘杂点
                imagestring($this->im, 5, mt_rand(-10, $this->config->getImageW()), mt_rand(-10, $this->config->getImageH()), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }

    /**
     * 绘制背景图片
     * 注：如果验证码输出图片比较大，将占用比较多的系统资源
     */
    protected function background(): void
    {
        $path = __DIR__ . '/../assets/bgs/';
        $dir = dir($path);

        $bgs = [];
        while (false !== ($file = $dir->read())) {
            if ('.' != $file[0] && substr($file, -4) == '.jpg') {
                $bgs[] = $path . $file;
            }
        }
        $dir->close();

        $gb = $bgs[array_rand($bgs)];

        [$width, $height] = @getimagesize($gb);
        // Resample
        $bgImage = @imagecreatefromjpeg($gb);
        @imagecopyresampled($this->im, $bgImage, 0, 0, 0, 0, $this->config->getImageW(), $this->config->getImageH(), $width, $height);
        @imagedestroy($bgImage);
    }
}