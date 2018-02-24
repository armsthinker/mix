<?php

namespace store\httpd\command;

use mix\console\Controller;
use store\httpd\library\Service;

/**
 * 服务控制器
 * @author 刘健 <coder.liu@qq.com>
 */
class ServiceController extends Controller
{

    // 是否后台运行
    protected $d = false;

    // 是否热更新
    protected $u = false;

    // 启动服务
    public function actionStart()
    {
        if ($pid = Service::getPid()) {
            return "mix-httpd 正在运行, PID : {$pid}." . PHP_EOL;
        }
        $server = \Mix::app()->createObject('httpServer');
        if ($this->u) {
            $server->setting['max_request'] = 1;
        }
        $server->setting['daemonize'] = $this->d;
        return $server->start();
    }

    // 停止服务
    public function actionStop()
    {
        if ($pid = Service::getPid()) {
            Service::killMaster($pid);
            while (Service::isRunning($pid)) {
            }
            return 'mix-httpd 停止完成.' . PHP_EOL;
        } else {
            return 'mix-httpd 没有运行.' . PHP_EOL;
        }
    }

    // 重启服务
    public function actionRestart()
    {
        $this->actionStop();
        $this->actionStart();
    }

}
