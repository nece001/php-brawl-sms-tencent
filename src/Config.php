<?php

namespace Nece\Brawl\Sms\Tencent;

use Nece\Brawl\ConfigAbstract;

/**
 * 配置类
 *
 * @Author nece001@163.com
 * @DateTime 2023-06-16
 */
class Config extends ConfigAbstract
{
    /**
     * 构建配置模板
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-16
     *
     * @return void
     */
    public function buildTemplate()
    {
        $this->addTemplate(true, 'secret_id', 'SecretId', 'SecretId 从腾讯云控制台的访问控制获取');
        $this->addTemplate(true, 'secret_key', 'SecretKey', 'SecretKey 从腾讯云控制台的访问控制获取');
        $this->addTemplate(true, 'region', '地域', '支持参考:https://cloud.tencent.com/document/api/382/52071#.E5.9C.B0.E5.9F.9F.E5.88.97.E8.A1.A8');
        $this->addTemplate(true, 'app_id', '应用ID', '可前往 [短信控制台](https://console.cloud.tencent.com/smsv2/app-manage) 查看');
        $this->addTemplate(true, 'sign_name', '短信签名内容', '必须填写已审核通过的签名');

        $this->addTemplate(false, 'endpoint', '接入地域域名', '默认就近接入');
        $this->addTemplate(false, 'sign_method', '签名算法', '默认为TC3-HMAC-SHA256', 'TC3-HMAC-SHA256');
        $this->addTemplate(false, 'proxy', '代理', '配置代理（无需要直接忽略），例：https://ip:port');
        $this->addTemplate(false, 'req_method', '请求方式', 'HTTP请求方法,默认：POST', 'POST');
        $this->addTemplate(false, 'timeout', '请求超时', 'HTTP请求超时', '60');
        $this->addTemplate(false, 'extend_code', '短信码号扩展号', '短信码号扩展号（无需要可忽略）: 默认未开通，如需开通请联系 [腾讯云短信小助手]');
        $this->addTemplate(false, 'sender_id', 'SenderId', '国内短信无需填写该项；国际/港澳台短信已申请独立 SenderId 需要填写该字段，默认使用公共 SenderId，无需填写该字段。注：月度使用量达到指定量级可申请独立 SenderId 使用，详情请联系 [腾讯云短信小助手](https://cloud.tencent.com/document/product/382/3773#.E6.8A.80.E6.9C.AF.E4.BA.A4.E6.B5.81)。');
    }
}
