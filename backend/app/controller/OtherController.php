<?php

namespace app\controller;
use app\BaseController;
use app\common\CommonUtil;
use app\common\Status;
class OtherController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    public function getIpInfo($ip = '') {
        if($ip === '') {
            $ip = CommonUtil::getClientIp();
        }
        $location = CommonUtil::getIpLocationV2($ip);
        if($location === null) {
            return result()::error(Status::IP_PARSE_ERR());
        }
        return result()::success($location);
    }
}
