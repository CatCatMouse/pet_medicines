<?php
/**
 * Created by caicai
 * Date 2022/7/8 0008
 * Time 17:10
 * Desc
 */


namespace app\api\controller;

use app\api\validate\CaseValidate;
use app\common\controller\Api;
use app\common\ResponseJson as RJ;
use think\response\Json as J;
use app\api\model\Cases as CM;
use app\common\enum\UserEnumType as T;

class Cases extends Api
{
    /**
     * 病例库列表
     * @return J
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lists(): J
    {
        $data = $this->request->post();
        $params = [
            'is_top' => 1,
            'case_type_id' => $data['case_type_id'] ?? [],
            'case_subject_id' => $data['case_subject_id'] ?? [],
            'search' => $data['search'] ?? '',
        ];
        return RJ::success(CM::lists($params));
    }

    /**
     * 器械相关病例信息
     * @return J
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function apparatus_cases(): J
    {
        return RJ::success(CM::apparatus_cases($this->request->post()));
    }

    /**
     * 病例详情
     * @return J
     */
    public function detail(): J
    {
        return RJ::success(CM::detail($this->request->post()));
    }

    /**
     * 新增病例
     * @param CaseValidate $va
     * @return J
     */
    public function add(CaseValidate $va): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YISHENG, T::YIYUAN])) {
            return RJ::fail('非法操作');
        }

        if (!$va->scene(__FUNCTION__)->check($this->request->post())) {
            return RJ::fail($va->getError());
        }

        $res = CM::add($this->request->post());

        if (true !== $res) {
            return RJ::fail($res);
        }

        return RJ::success([], '添加成功');
    }

    /**
     * 编辑病例
     * @param CaseValidate $va
     * @return J
     */
    public function edit(CaseValidate $va): J
    {
        if (!in_array($this->request->userInfo['type'], [T::XIAOSHOU, T::YISHENG, T::YIYUAN])) {
            return RJ::fail('非法操作');
        }

        if (!$va->scene(__FUNCTION__)->check($this->request->post())) {
            return RJ::fail($va->getError());
        }

        $res = CM::edit($this->request->post());

        if (true !== $res) {
            return RJ::fail($res);
        }

        return RJ::success([], '编辑成功');
    }
}