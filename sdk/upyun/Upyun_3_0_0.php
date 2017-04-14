<?php
/**
 * Class Config
 *
 * @package Upyun
 */
class Config
{
    /**
     * @var string 服务名称
     */
    public $bucketName;
    /**
     * @var string 操作员名
     */
    public $operatorName;
    /**
     * @var string 操作员密码 md5 hash 值
     */
    public $operatorPassword;

    /**
     * @var bool 是否使用 https
     */
    public $useSsl;

    /**
     * @var string 上传使用的接口类型，可以设置为 `REST`：使用 rest api 上传，`AUTO` 根据文件大小自动判断，`BLOCK` 使用断点续传
     * 当上传小文件时，不推荐使用断点续传；上传时如果设置了异步预处理`withAsyncProcess=true`，将会使用表单 api 上传
     */
    public $uploadType = 'AUTO';

    /**
     * @var int 上传的接口类型设置为 `AUTO` 时，文件大小的边界值：小于该值时，使用 rest api，否则使用断点续传。 默认 30M
     */
    public $sizeBoundary = 31457280;

    /**
     * @var int request timeout seconds
     */
    public $timeout = 60;


    /**
     * @var string 异步云处理的回调通知地址
     */
    public $processNotifyUrl;

    /**
     * @var boolean curl debug
     */
    public $debug = false;

    private $version = '3.0.0';



    /**
     * @var string 表单 api 的秘钥
     */
    private $formApiKey;

    /**
     * @var string rest api 和 form api 的接口地址
     */
    public static $restApiEndPoint;


    /**
     * rest api 和 form api 接口请求地址，详见：http://docs.upyun.com/api/rest_api/
     */
    const ED_AUTO            = 'v0.api.upyun.com';
    const ED_TELECOM         = 'v1.api.upyun.com';
    const ED_CNC             = 'v2.api.upyun.com';
    const ED_CTT             = 'v3.api.upyun.com';

    /**
     * 异步云处理接口地址
     */
    const ED_VIDEO           = 'p0.api.upyun.com';

    /**
     * 刷新接口地址
     */
    const ED_PURGE           = 'http://purge.upyun.com/purge/';

    public function __construct($bucketName, $operatorName, $operatorPassword)
    {
        $this->bucketName = $bucketName;
        $this->operatorName = $operatorName;
        $this->setOperatorPassword($operatorPassword);
        $this->useSsl          = false;
        self::$restApiEndPoint = self::ED_AUTO;
    }

    public function setOperatorPassword($operatorPassword)
    {
        $this->operatorPassword = md5($operatorPassword);
    }

    public function getFormApiKey()
    {
        if (! $this->formApiKey) {
            throw new \Exception('form api key is empty.');
        }

        return $this->formApiKey;
    }

    public function setFormApiKey($key)
    {
        $this->formApiKey = $key;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getPretreatEndPoint()
    {
        return $this->getProtocol() . self::ED_VIDEO;
    }

    public function getProtocol()
    {
        return $this->useSsl ? 'https://' : 'http://';
    }
}

/**
 * Class Signature
 * @package Upyun
 */
class Signature
{
    /**
     * 获取分块上传接口的签名
     */
    const SIGN_MULTIPART     = 1;
    /**
     * 生成视频处理接口的签名
     */
    const SIGN_VIDEO         = 2;
    /**
     * 生成视频处理接口的签名（不需要操作员时使用）
     */
    const SIGN_VIDEO_NO_OPERATOR   = 3;

    /**
     * 获取 Header 签名需要的请求头
     *
     * @param Config $bucketConfig
     * @param $method 请求方法
     * @param $path  请求路径
     * @param $contentMd5 文件内容 md5
     *
     * @return array
     */
    public static function getHeaderSign($bucketConfig, $method, $path, $contentMd5 = null)
    {
        $gmtDate = gmdate('D, d M Y H:i:s \G\M\T');

        $policy = null;
        $sign = self::getBodySignature($bucketConfig, $method, $path, $gmtDate, $policy, $contentMd5);

        $headers = array(
            'Authorization' => $sign,
            'Date' => $gmtDate,
            'User-agent' => 'Php-Sdk/' . $bucketConfig->getVersion()
        );
        return $headers;
    }

    /**
     * 获取请求缓存刷新接口需要的签名头
     *
     * @param Config $bucketConfig
     * @param $urlString
     *
     * @return array
     */
    public static function getPurgeSignHeader(Config $bucketConfig, $urlString)
    {
        $gmtDate = gmdate('D, d M Y H:i:s \G\M\T');
        $sign = md5("$urlString&{$bucketConfig->bucketName}&$gmtDate&{$bucketConfig->operatorPassword}");
        return array(
            'Authorization' => "UpYun {$bucketConfig->bucketName}:{$bucketConfig->operatorName}:$sign",
            'Date' => $gmtDate,
            'User-agent' => 'Php-Sdk/' . $bucketConfig->getVersion() . ' (purge api)'
        );
    }

    /**
     * 获取表单 API 需要的签名，依据 body 签名规则计算
     * @param Config $bucketConfig
     * @param $method 请求方法
     * @param $uri 请求路径
     * @param $date 请求时间
     * @param $policy
     * @param $contentMd5 请求 body 的 md5
     *
     * @return array
     */
    public static function getBodySignature(Config $bucketConfig, $method, $uri, $date = null, $policy = null, $contentMd5 = null)
    {
        $data = array(
            $method,
            $uri
        );
        if ($date) {
            $data[] = $date;
        }

        if ($policy) {
            $data[] = $policy;
        }

        if ($contentMd5) {
            $data[] = $contentMd5;
        }
        $signature = base64_encode(hash_hmac('sha1', implode('&', $data), $bucketConfig->operatorPassword, true));
        return 'UPYUN ' . $bucketConfig->operatorName . ':' . $signature;
    }
}


/**
 * Class Upyun
 *
 * 又拍云云存储、云处理接口
 *
 * Upyun 类实现了又拍云云存储和云处理的所有接口，通过该类可以实现文件上传、下载；图片视频等多媒体资源云处理。
 * 本文档中，提到的"服务"是指又拍云文件加速回又拍云源类型的服务（即原先的存储类空间）。
 *
 * @package Upyun
 */
class Upyun
{

    /**
     * @var Config: 服务配置
     */
    protected $config;

    /**
     * Upyun constructor.
     *
     * @param Config $config 服务配置
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
    }

    /**
     * 配置服务信息
     *
     * 当需要操作的新的服务时，使用该方法传入新的服务配置即可
     *
     * @param Config $config 服务配置
     *
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 上传一个文件到又拍云存储
     *
     * 上传的文件格式支持文件流或者字符串方式上传。除简单的文件上传外，针对多媒体资源（图片、音视频），还可以设置同步/异步预处理多媒体资源，例如：图片的裁剪缩放，音视频的转码截图等等众多又拍云强大的云处理功能
     *
     * @param string $path 被上传的文件在又拍云存储服务中保存的路径
     * @param string|resource $content 被上传的文件内容（字符串），或者打开该文件获得的文件句柄（文件流）。当上传本地大文件时，推荐使用文件流的方式上传
     * @param array $params 上传文件时，附加的自定义参数。支持 Content-MD5 Content-Type Content-Secret 等，详见 [上传参数](http://docs.upyun.com/api/rest_api/#_2)，例如：
     * - 设置文件[保护秘钥](http://docs.upyun.com/api/rest_api/#Content-Secret) `write($path, $content, array('Content-Secret' => 'my-secret'))`；
     * - 添加[文件元信息](http://docs.upyun.com/api/rest_api/#metadata) `write($path, $content, array('X-Upyun-Meta-Foo' =>
     * 'bar'))`
     * - [图片同步预处理](http://docs.upyun.com/cloud/image/#_5) `write($path, $content, array('x-gmkerl-thumb' => '/format/png'))`
     * @param bool $withAsyncProcess  默认为 `false`，当上传图片或者音视频资源时，可以设置该参数为 `true`，开启图片音视频的[异步处理功能](http://docs.upyun.com/api/form_api/#_6) ，例如：
     *```
     * // 以下参数会将新上传的图片，再异步生成另一份 png 格式的图片，原图不受影响
     * write($path, $content, array(
     *    'apps' => array(
     *        array(
     *            'name' => 'thumb',         //异步图片处理任务
     *            'x-gmkerl-thumb' => '/format/png', // 格式化图片为 png 格式
     *            'save_as': '/iamge/png/new.png',   // 处理成功后的图片保存路径
     *            'notify_url': 'http://your.notify.url'  // 异步任务完成后的回调地址
     *        )
     *    )
     * ), true);
     *```
     *
     *
     *
     * @return array|bool 若文件是图片则返回图片基本信息，如：`array('x-upyun-width' => 123, 'x-upyun-height' => 50, 'x-upyun-frames'
     * => 1, 'x-upyun-file-type' => 'JPEG')`，否则返回空数组。当使用异步预处理功能时，返回结果为布尔值，成功为 `true`。
     *
     * @throws \Exception 上传失败时，抛出异常
     */
    public function write($path, $content, $params = array(), $withAsyncProcess = false)
    {
        if (!$content) {
            throw new \Exception('write content can not be empty.');
        }

        $upload = new Uploader($this->config);
        $response = $upload->upload($path, $content, $params, $withAsyncProcess);
        if ($withAsyncProcess) {
            return $response;
        }
        return Util::getHeaderParams($response->getHeaders());
    }

    /**
     * 读取云存储文件/目录内容
     *
     * @param string $path 又拍云存储中的文件或者目录路径
     * @param resource $saveHandler 文件内容写入本地文件流。例如 `$saveHandler = fopen('/local/file', 'w')
     * `。当设置该参数时，将以文件流的方式，直接将又拍云中的文件写入本地的文件流，或其他可以写入的流
     * @param array $params  可选参数，读取目录内容时，需要设置三个参数： `X-List-Iter` 分页开始位置（第一页不需要设置），`X-List-Limit` 获取的文件数量（默认 100，最大
     * 10000），`X-List-Order` 结果以时间正序或者倒序
     *
     * @return mixed $return 当读取文件且没有设置 `$saveHandler` 参数时，返回一个字符串类型，表示文件内容；设置了 `$saveHandler` 参数时，返回布尔值
     * `true`。当读取目录时，返回一个数组，表示目录下的文件列表。目录下文件内容过多时，需要通过判断返回数组中的 `is_end` 属性，进行分页读取内容
     *
     * @throws \Exception
     */
    public function read($path, $saveHandler = null, $params = array())
    {
        $req = new Rest($this->config);
        $response = $req->request('GET', $path)
            ->withHeaders($params)
            ->send();


        $params = Util::getHeaderParams($response->getHeaders());


        if (! isset($params['x-upyun-list-iter'])) {
            if (is_resource($saveHandler)) {
                Psr7\copy_to_stream($response->getBody(), Psr7\stream_for($saveHandler));
                return true;
            } else {
                return $response->getBody()->getContents();
            }
        } else {
            $files = Util::parseDir($response->getBody()->getContents());
            return array('files' => $files, 'is_end' => $params['x-upyun-list-iter'] === 'g2gCZAAEbmV4dGQAA2VvZg', 'iter' => $params['x-upyun-list-iter']);
        }
    }

    /**
     * 判断文件是否存在于又拍云存储
     *
     * 注意: 对刚删除的文件, 立即调用该方法可能会返回 true, 因为服务端执行删除操作后可能会有很短暂的延迟.
     *
     * @param string $path 云存储的文件路径
     *
     * @return bool 存在时返回 `true`，否则返回 `false`
     * @throws \Exception
     */
    public function has($path)
    {
        $req = new Rest($this->config);
        try {
            $req->request('HEAD', $path)
                            ->send();
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode === 404) {
                return false;
            } else {
                throw $e;
            }
        }

        return true;
    }

    /**
     * 获取云存储文件/目录的基本信息
     *
     * @param string $path 云存储的文件路径
     *
     * @return array 返回一个数组，包含以下 key
     * - `x-upyun-file-type` 当 $path 是目录时，值为 *folder*，当 $path 是文件时，值为 *file*，
     * - `x-upyun-file-size` 文件大小
     * - `x-upyun-file-date` 文件的创建时间
     */
    public function info($path)
    {
        $req = new Rest($this->config);
        $response = $req->request('HEAD', $path)
                        ->send();
        return Util::getHeaderParams($response->getHeaders());
    }

    /**
     * 删除文件或者目录
     *
     * @param string $path 文件或目录在又拍云存储的路径
     * @param bool $async 是否异步删除，默认为 false，表示同步删除。当需要批量删除大量文件时，必须选择异步删除
     *
     * @return bool 删除成功返回 true，否则 false
     * @throws \Exception 删除不存在的文件将会抛出异常
     */
    public function delete($path, $async = false)
    {
        $req = new Rest($this->config);
        $req->request('DELETE', $path);
        if ($async) {
            $req->withHeader('x-upyun-async', 'true');
        }
        $res = $req->send();
        return $res->getStatusCode() === 200;
    }

    /**
     * 创建目录
     *
     * @param string $path 需要在又拍云存储创建的目录路径
     *
     * @return bool 创建成功返回 true，否则返回 false
     * @throws \Exception
     */
    public function createDir($path)
    {
        $path = rtrim($path, '/') . '/';
        $req = new Rest($this->config);
        $res = $req->request('POST', $path)
            ->withHeader('folder', 'true')
            ->send();
        return $res->getStatusCode() === 200;
    }

    /**
     * 删除文件或者目录
     *
     * @param string $path 需要被删除的云存储文件或目录路径
     *
     * @return bool 成功返回 true，否则 false
     * @throws \Exception
     */
    public function deleteDir($path)
    {
        return $this->delete($path);
    }

    /**
     * 获取目录下存储使用量
     *
     * @param string $path 云存储目录路径，默认为根目录，表示整个云存储服务使用的空间大小
     * @return string 存储使用量，单位字节
     * @throws \Exception
     */
    public function usage($path = '/')
    {
        $path = rtrim($path, '/') . '/';
        $req = new Rest($this->config);
        $response = $req->request('GET', $path . '?usage')
            ->send();

        return $response->getBody()->getContents();
    }

    /**
     * 刷新缓存
     *
     * @param array|string $urls 需要刷新的文件 url 列表
     *
     * @return array 刷新失败的 url 列表，若全部刷新成功则为空数组
     */
    public function purge($urls)
    {
        $urlString = $urls;
        if (is_array($urls)) {
            $urlString = implode("\n", $urls);
        }

        $client = new Client([
            'timeout' => $this->config->timeout
        ]);
        $response = $client->request('POST', Config::ED_PURGE, [
            'headers' =>  Signature::getPurgeSignHeader($this->config, $urlString),
            'form_params' => ['purge' => $urlString]
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        return $result['invalid_domain_of_url'];
    }

    /**
     * 异步云处理
     *
     * 该方法是基于[又拍云云处理](http://docs.upyun.com/cloud/) 服务实现，可以实现音视频的转码、切片、剪辑；文件的压缩解压缩；文件拉取功能
     * 所有需要调用该方法处理的资源，必须已经上传到云存储服务，未上传到云存储的文件，同时需要云处理功能，请使用 `write` 方法。
     * 例如视频转码：
     * ```
     *  process($source, array(
     *    array(
     *        'type' => 'video',  // video 表示视频任务, audio 表示音频任务
     *        'avopts' => '/s/240p(4:3)/as/1/r/30', // 处理参数，`s` 表示输出的分辨率，`r` 表示视频帧率，`as` 表示是否自动调整分辨率
     *        'save_as' => '/video/240/new.mp4', // 新视频在又拍云存储的保存路径
     *    ),
     *    ... // 同时还可以添加其他任务
     * ))
     * ```
     * 注意，被处理的资源需要已经上传到又拍云云存储
     *
     * @param string $source 需要预处理的图片、音视频资源在又拍云存储的路径
     * @param array $tasks 需要处理的任务
     *
     * @return array 任务 ID，提交了多少任务，便会返回多少任务 ID，与提交任务的顺序保持一致。可以通过任务 ID 查询处理进度。格式如下：
     * ```
     * array(
     *     '35f0148d414a688a275bf915ba7cebb2',
     *     '98adbaa52b2f63d6d7f327a0ff223348',
     * )
     * ```
     */
    public function process($source, $tasks)
    {
        $video = new Api\Pretreat($this->config);
        return $video->process($source, $tasks);
    }

    /**
     * 查询异步云处理任务进度
     *
     * 根据 `process` 方法返回的任务 ID，通过该访问查询处理进度
     *
     * @param array $taskIds 任务 ID
     *
     * @return bool|array 查询失败返回布尔值 `false`，否则返回每个任务的百分比进度信息，格式如下：
     * ```
     * array(
     *     '35f0148d414a688a275bf915ba7cebb2' => 100,  // 100 表示任务完成
     *     'c3103189fa906a5354d29bd807e8dc51' => 35,
     *     '98adbaa52b2f63d6d7f327a0ff223348' => null, // null 表示任务未开始，或异常
     * )
     * ```
     */
    public function queryProcessStatus($taskIds)
    {
        $video = new Api\Pretreat($this->config);
        return $video->query($taskIds, '/status/');
    }

    /**
     *  查询异步云处理任务结果
     *
     * 根据 `process` 方法返回的任务 ID，通过该访问查询处理结果，会包含每个任务详细信息
     * @param array $taskIds 任务 ID
     *
     * @return bool|mixed 查询失败返回 `false`，否则返回每个任务的处理结果，格式如下：
     * ```
     * array(
     *    '9d9c32b63a1034834e77672c6f51f661' => array(
     *         'path' => array('/v2.mp4'),
     *         'signature' => '4042c1f07f546d28',
     *         'status_code' => 200,
     *         'bucket_name' => 'your_storage_bucket',
     *         'description' => 'OK',
     *         'task_id' => '9d9c32b63a1034834e77672c6f51f661',
     *         'timestamp' => 1472010684
     *    )
     * )
     * ```
     */
    public function queryProcessResult($taskIds)
    {
        $video = new Api\Pretreat($this->config);
        return $video->query($taskIds, '/result/');
    }
}



class Util
{
    public static function trim($str)
    {
        if (is_array($str)) {
            return array_map(array('Util', 'trim'), $str);
        } else {
            return trim($str);
        }
    }

    public static function getHeaderParams($headers)
    {
        $params = [];
        foreach ($headers as $header => $value) {
            $header = strtolower($header);
            if (strpos($header, 'x-upyun-') !== false) {
                $params[$header] = $value[0];
            }
        }
        return $params;
    }

    public static function parseDir($body)
    {
        $files = array();
        if (!$body) {
            return array();
        }

        $lines = explode("\n", $body);
        foreach ($lines as $line) {
            $file = [];
            list($file['name'], $file['type'], $file['size'], $file['time']) = explode("\t", $line, 4);
            $files[] = $file;
        }

        return $files;
    }

    public static function base64Json($params)
    {
        return base64_encode(json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    public static function stringifyHeaders($headers)
    {
        $return = array();
        foreach ($headers as $key => $value) {
            $return[] = "$key: $value";
        }
        return $return;
    }

    public static function md5Hash($resource)
    {
        rewind($resource);
        $ctx = hash_init('md5');
        hash_update_stream($ctx, $resource);
        $md5 = hash_final($ctx);
        return $md5;
    }

    /**
     * GuzzleHttp\Psr\Uri use `parse_url`，not good for utf-8,
     * So should `encodeURI` first, before `new Psr7\Request`
     * @see http://stackoverflow.com/questions/4929584/encodeuri-in-php
     */
    public static function encodeURI($url)
    {
        $unescaped = array(
            '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
            '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
        );
        $reserved = array(
            '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
            '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
        );
        $score = array(
            '%23'=>'#'
        );
        return strtr(rawurlencode($url), array_merge($reserved, $unescaped, $score));
    }
}



class Uploader
{
    /**
     * @var Config
     */
    protected $config;

    protected $useBlock = false;


    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function upload($path, $file, $params, $withAsyncProcess)
    {
        $stream = Psr7\stream_for($file);
        $size = $stream->getSize();
        $useBlock = $this->needUseBlock($size);

        if ($withAsyncProcess) {
            $req = new Form($this->config);
            return $req->upload($path, $stream, $params);
        }

        if (! $useBlock) {
            $req = new Rest($this->config);
            return $req->request('PUT', $path)
                       ->withHeaders($params)
                       ->withFile($stream)
                       ->send();
        } else {
            return $this->pointUpload($path, $stream, $params);
        }
    }

    /**
     *  断点续传
     * @param $path
     * @param $stream
     * @param $params
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    private function pointUpload($path, $stream, $params)
    {
        $req = new Rest($this->config);
        $headers = array();
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                $headers['X-Upyun-Meta-' . $key] = $val;
            }
        }
        $res = $req->request('PUT', $path)
            ->withHeaders(array_merge(array(
                'X-Upyun-Multi-Stage' => 'initiate',
                'X-Upyun-Multi-Type' => Psr7\mimetype_from_filename($path),
                'X-Upyun-Multi-Length' => $stream->getSize(),
            ), $headers))
            ->send();
        if ($res->getStatusCode() !== 204) {
            throw new \Exception('init request failed when poinit upload!');
        }

        $init      = Util::getHeaderParams($res->getHeaders());
        $uuid      = $init['x-upyun-multi-uuid'];
        $blockSize = 1024 * 1024;
        $partId    = 0;
        do {
            $fileBlock = $stream->read($blockSize);
            $res = $req->request('PUT', $path)
                ->withHeaders(array(
                    'X-Upyun-Multi-Stage' => 'upload',
                    'X-Upyun-Multi-Uuid' => $uuid,
                    'X-Upyun-Part-Id' => $partId
                ))
                ->withFile(Psr7\stream_for($fileBlock))
                ->send();

            if ($res->getStatusCode() !== 204) {
                throw new \Exception('upload request failed when poinit upload!');
            }
            $data   = Util::getHeaderParams($res->getHeaders());
            $partId = $data['x-upyun-next-part-id'];
        } while ($partId != -1);

        $res = $req->request('PUT', $path)
            ->withHeaders(array(
                'X-Upyun-Multi-Uuid' => $uuid,
                'X-Upyun-Multi-Stage' => 'complete'
            ))
            ->send();

        if ($res->getStatusCode() != 204 && $res->getStatusCode() != 201) {
            throw new \Exception('end request failed when poinit upload!');
        }
        return $res;
    }

    private function needUseBlock($fileSize)
    {
        if ($this->config->uploadType === 'BLOCK') {
            return true;
        } elseif ($this->config->uploadType === 'AUTO' &&
                  $fileSize >= $this->config->sizeBoundary) {
            return true;
        } else {
            return false;
        }
    }
}




class Rest
{
    /**
     * @var Config
     */
    protected $config;

    protected $endpoint;
    protected $method;
    protected $storagePath;
    public $headers = [];

    /**
     * @var Psr7\Stream
     */
    protected $file;


    public function __construct(Config $config)
    {
        $this->config   = $config;
        $this->endpoint = $config->getProtocol() . Config::$restApiEndPoint . '/' . $config->bucketName;
    }

    public function request($method, $storagePath)
    {
        $this->method = strtoupper($method);
        $this->storagePath = '/' . ltrim($storagePath, '/');
        return $this;
    }


    /**
     * @param string|resource $file
     *
     * @return $this
     */
    public function withFile($file)
    {
        $stream = Psr7\stream_for($file);
        $this->file = $stream;

        return $this;
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send()
    {
        $client = new Client([
            'timeout' => $this->config->timeout,
        ]);

        $url = $this->endpoint . $this->storagePath;
        $body = null;
        if ($this->file && $this->method === 'PUT') {
            $body = $this->file;
        }

        $request = new Psr7\Request(
            $this->method,
            Util::encodeURI($url),
            $this->headers,
            $body
        );
        $authHeader = Signature::getHeaderSign($this->config,
            $this->method,
            $request->getUri()->getPath()
        );
        foreach ($authHeader as $head => $value) {
            $request = $request->withHeader($head, $value);
        }
        $response = $client->send($request, [
            'debug' => $this->config->debug
        ]);

        return $response;
    }

    public function withHeader($header, $value)
    {
        $header = strtolower(trim($header));

        $this->headers[$header] = $value;
        return $this;
    }

    public function withHeaders($headers)
    {
        if (is_array($headers)) {
            foreach ($headers as $header => $value) {
                $this->withHeader($header, $value);
            }
        }
        return $this;
    }
}


class Form extends Rest
{
    public function upload($path, $stream, $params)
    {
        $params['save-key'] = $path;
        $params['bucket'] = $this->config->bucketName;
        if (!isset($params['expiration'])) {
            $params['expiration'] = time() + 30 * 60 * 60; // 30 分钟
        }

        $policy = Util::base64Json($params);
        $method = 'POST';
        $signature = Signature::getBodySignature($this->config, $method, '/' . $params['bucket'], null, $policy);
        $client = new Client([
            'timeout' => $this->config->timeout,
        ]);

        $response = $client->request($method, $this->endpoint, array(
            'multipart' => array(
                array(
                    'name' => 'policy',
                    'contents' => $policy,
                ),
                array(
                    'name' => 'authorization',
                    'contents' => $signature,
                ),
                array(
                    'name' => 'file',
                    'contents' => $stream,
                )
            )
        ));
        return $response->getStatusCode() === 200;
    }
}




class Pretreat
{

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function process($source, $tasks)
    {
        $encodedTasks = Util::base64Json($tasks);

        $client = new Client([
            'timeout' => $this->config->timeout,
        ]);

        $params = array(
            'bucket_name' => $this->config->bucketName,
            'notify_url' => $this->config->processNotifyUrl,
            'source' => $source,
            'tasks' => $encodedTasks,
            'accept' => 'json'
        );

        $path = '/pretreatment/';
        $method = 'POST';
        $signedHeaders = Signature::getHeaderSign($this->config, $method, $path);

        $url = $this->config->getPretreatEndPoint() . $path;
        $response = $client->request($method, $url, [
            'headers' => $signedHeaders,
            'form_params' => $params
        ]);

        $body = $response->getBody()->getContents();
        return json_decode($body, true);
    }


    public function query($taskIds, $path)
    {
        $client = new Client([
            'timeout' => $this->config->timeout,
        ]);

        $params = array(
            'service' => $this->config->bucketName,
            'task_ids' => implode(',', $taskIds)
        );
        $path = $path . '?' . http_build_query($params);

        $method = 'GET';
        $url = $this->config->getPretreatEndPoint() . $path;
        $signedHeaders = Signature::getHeaderSign($this->config, $method, $path);
        $response = $client->request($method, $url, [
            'headers' => $signedHeaders
        ]);

        if ($response->getStatusCode() === 200) {
            $body = $response->getBody()->getContents();
            $result = json_decode($body, true);
            if (is_array($result)) {
                return $result['tasks'];
            }
        }
        return false;
    }
}
