<?php
/**
 * Created by PhpStorm.
 * User: eddie
 * Date: 2018/10/17
 * Time: 上午10:38
 */

namespace JkTech\TencentIm\Tests;

use JkTech\TencentIm\Im;
use JkTech\TencentIm\Message\Bag;

class TencentImSendMessageTest extends TestCase
{
    protected $messageServ;

    const PRIVATE_KEY = 'zhidian_master_private_key';
    const PUBLIC_KEY  = 'zhidian_master_public_key';

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        /*
         * Release :
         *  IM_SDK_APPID=1400131907
         *  IM_SDK_ACOUNT=admin
         *  IM_SDK_ACOUNTTYPE=36362
         *
         * Master :
         *  IM_SDK_APPID=1400130036
         *  IM_SDK_ACOUNT=admin
         *  IM_SDK_ACOUNTTYPE=36382
         */

        $this->app['config']->set('im.appid', '1400130036');
        $this->app['config']->set('im.identifier', 'admin');
        $this->app['config']->set('im.domain', 'https://console.tim.qq.com/');
        $this->app['config']->set('im.version', 'v4');
        $this->app['config']->set('im.private_key', __DIR__ . '/zhidian_master_private_key');
        $this->app['config']->set('im.public_key', __DIR__ . '/zhidian_master_public_key');

        $this->messageServ = (new Im())->message();
    }

    public function testSendMessage()
    {
        $identifier = 'c101016';
        $fromAccount = 's13';

        $res = $this->messageServ->append(new Bag([
            'MsgType' => 'TIMTextElem',
            'MsgContent' => [
                'Text' => '测试 - 发送信息 (' . date('Y-m-d H:i:s') . ')'
            ]
        ]))->send($identifier, ['From_Account' => $fromAccount]);

        $this->assertTrue($res['ActionStatus'] == 'OK', "Tencent-IM send message to: {$identifier} --- failure.");
    }

    public function testBatchSendMessage()
    {
        $fromAccount = 's13';
        $identifiers = ['c101000', 'c101013', 'c101004', 'c101016'];

        $res = $this->messageServ->append(new Bag([
            'MsgType' => 'TIMTextElem',
            'MsgContent' => [
                'Text' => '测试 - 群发信息 (' . date('Y-m-d H:i:s') . ')'
            ]
        ]))->batchSend($identifiers, ['From_Account' => $fromAccount]);

        $this->assertTrue($res['ActionStatus'] == 'OK', "Tencent-IM batch-send message to: ".json_encode($identifiers)." --- failure.");
    }
}