# php-brawl-sms-tencent
PHP 腾讯云短信基础服务适配项目

# 示例
```php
    $conf = array(
        'secret_id' => 'xxx',
        'secret_key' => 'xxx',
        'region' => 'ap-guangzhou',
        'app_id' => 'xxxx',
        'sign_name' => 'xxx',
    );

    // 创建配置
    $config = Factory::createConfig('Tencent');
    $config->setConfig($conf);

    // 创建客户端
    $sms = Factory::createClient($config);
    try {
        $template_id = '1529729'; // 模板ID
        $params = array('1234', '3'); // 内容参数

        // 添加号码
        $sms->addPhoneNumber('131xxx');
        $sms->addPhoneNumber('132xxx');

        // 发送
        $result = $sms->send($template_id, $params);

        echo '发送结果：';
        print_r($result);
    } catch (Throwable $e) {
        echo $e->getMessage();
        echo $sms->getErrorMessage();
    }
```