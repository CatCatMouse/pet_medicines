<?php
/**
 * Created by caicai
 * Date 2022/7/21 0021
 * Time 15:31
 * Desc
 */


namespace app\api\controller;

use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use QiNiuYun\QNOss as Oss;

class Upload extends Api
{
    public $middleware = [];

    /**
     * 获取上传token
     * @return J
     */
    public function getToken(): J
    {
        $token = Oss::getUpToken();
        if (!$token) {
            return RJ::fail();
        }
        return RJ::success(['upToken' => $token]);
    }

    public function upload()
    {
        $file = $this->request->file('file');
        if (is_null($file)) {
            return RJ::fail('请上传文件');
        }

        if (is_array($file)) {
           return RJ::fail('只支持单文件上传');
        }

        $filePath = $file->getPath() . DIRECTORY_SEPARATOR .$file->getFilename();

        $res = Oss::uploadFile($filePath, $file->getOriginalName());

        return RJ::success(['url' => $res]);
    }
}