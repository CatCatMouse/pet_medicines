<?php
/**
 * Created by caicai
 * Date 2022/7/21 0021
 * Time 15:07
 * Desc
 */

namespace QiNiuYun;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use \Exception;
use think\facade\Cache;

/**
 * 七牛云上传
 */
class QNOss
{
    private static $domain;
    private static $md5Str;
    private static $accessKey;
    private static $secretKey;
    private static $bucket;
    protected static $ttl = 1800;

    public static function init($accessKey = '', $secretKey = '', $bucket = '', $domain = '')
    {
        if (empty(static::$domain)) {
            static::$domain = $domain ?: (env('QI_NIU_YUN_OSS.DOMAIN', '') ?: request()->domain());
        }

        if (empty(static::$accessKey)) {
            static::$accessKey = $secretKey ?: env('QI_NIU_YUN_OSS.ACCESS_KEY_ID', '');
        }

        if (empty(static::$secretKey)) {
            static::$secretKey = $secretKey ?: env('QI_NIU_YUN_OSS.ACCESS_KEY_SECRET', '');
        }

        if (empty(static::$bucket)) {
            static::$bucket = $bucket ?: env('QI_NIU_YUN_OSS.BUCKET', '');
        }
        static::$md5Str = md5(static::$accessKey . static::$secretKey . static::$bucket);
    }


    /**
     * 获取上传Token
     * @return false|mixed|string
     */
    public static function getUpToken()
    {
        try {
            static::init();
            $key = 'api_upload_token' . (static::$md5Str);
            if (Cache::has($key)) {
                $upToken = Cache::get($key);
            } else {
                $auth = new Auth(static::$accessKey, static::$secretKey);
                $upToken = $auth->uploadToken(static::$bucket);
                Cache::set($key, $upToken, static::$ttl);
            }
        } catch (Exception $e) {
            return false;
        }
        return $upToken;
    }

    public static function uploadFile($filePath, $key = null)
    {
        $key = date('Y-m-d-') . $key;
        $token = static::getUpToken();
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        [$ret, $err] = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            throw new \think\Exception($err->getMessage());
        }
        $url = (static::$domain) . '/' . $ret['key'] . '?Etag=' . $ret['hash'];
        return $url;
    }
}