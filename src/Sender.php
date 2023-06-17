<?php

namespace Nece\Brawl\Sms\Tencent;

use Nece\Brawl\Sms\SenderAbstract;
use Nece\Brawl\Sms\SendResult;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Sms\V20210111\SmsClient;

class Sender extends SenderAbstract
{

    private $client;

    /**
     * 短信发送
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @param string $template_id
     * @param array $params
     * @param string $session_context
     *
     * @return SendResult
     */
    public function send($template_id, array $params, $session_context = ''): SendResult
    {
        $result = new SendResult();

        $req = $this->buildRequest($template_id, $params);
        $res = $this->getClient()->SendSms($req);

        $result->setRaw($res->toJsonString());
        $data = $res->serialize();
        if (isset($data['SendStatusSet'])) {
            foreach ($data['SendStatusSet'] as $row) {
                $result->addResult($row['SerialNo'], $row['PhoneNumber'], $row['Fee'], $row['SessionContext'], $row['Code'], $row['Message'], $row['IsoCode'], $row['Code'] == 'Ok');
            }
        }
        return $result;
    }

    /**
     * 获取客户端
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @return \TencentCloud\Sms\V20210111\SmsClient
     */
    private function getClient()
    {
        if (!$this->client) {
            $secret_id = $this->getConfigValue('secret_id');
            $secret_key = $this->getConfigValue('secret_key');
            $region = $this->getConfigValue('region');

            $cred = new Credential($secret_id, $secret_key);
            $profile = $this->buildClientProfile();
            $this->client = new SmsClient($cred, $region, $profile);
        }

        return $this->client;
    }

    /**
     * 构建设置
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @return \TencentCloud\Common\Profile\ClientProfile
     */
    private function buildClientProfile()
    {

        $proxy = $this->getConfigValue('proxy');
        $req_method = $this->getConfigValue('req_method');
        $timeout = $this->getConfigValue('timeout');
        $endpoint = $this->getConfigValue('endpoint');
        $sign_method = $this->getConfigValue('sign_method', 'TC3-HMAC-SHA256');

        $httpProfile = new HttpProfile();
        // 配置代理（无需要直接忽略）
        if ($proxy) {
            $httpProfile->setProxy($proxy);
        }

        if ($req_method) {
            $httpProfile->setReqMethod($req_method);  // post请求(默认为post请求)
        }

        if ($timeout) {
            $httpProfile->setReqTimeout($timeout);    // 请求超时时间，单位为秒(默认60秒)
        }

        if ($endpoint) {
            $httpProfile->setEndpoint($endpoint);  // 指定接入地域域名(默认就近接入)
        }

        // 实例化一个client选项，可选的，没有特殊需求可以跳过
        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        if ($sign_method) {
            $clientProfile->setSignMethod($sign_method);  // 指定签名算法(默认为HmacSHA256)
        }

        return $clientProfile;
    }

    /**
     * 构建请求
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @param string $template_id
     * @param array $params
     * @param string $session_context
     *
     * @return \TencentCloud\Sms\V20210111\Models\SendSmsRequest
     */
    private function buildRequest($template_id, array $params, $session_context = '')
    {
        $app_id =  $this->getConfigValue('app_id');
        $sign_name = $this->getConfigValue('sign_name');
        $extend_code = $this->getConfigValue('extend_code', '');
        $sender_id = $this->getConfigValue('sender_id', '');

        $req = new SendSmsRequest();
        $req->SmsSdkAppId = $app_id;
        $req->SignName = $sign_name;
        $req->TemplateId = $template_id;
        $req->TemplateParamSet = $this->formatParams($params);
        $req->PhoneNumberSet = $this->formatPhoneNumbers();
        $req->SessionContext = $session_context;
        $req->ExtendCode = $extend_code;
        $req->SenderId = $sender_id;

        return $req;
    }

    /**
     * 格式化参数
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @param array $params
     *
     * @return array
     */
    private function formatParams(array $params)
    {
        return array_values($params);
    }

    /**
     * 获取格式化后的号码
     *
     * @Author nece001@163.com
     * @DateTime 2023-06-17
     *
     * @return array
     */
    private function formatPhoneNumbers()
    {
        $data = array();
        foreach ($this->phone_numbers as $row) {
            $data[] = '+' . $row['code'] . $row['phone_number'];
        }

        $this->phone_numbers = array();

        return $data;
    }
}
