<?php

return [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@app/mail',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'xxxxxxx.com',
        'password' => 'xxxxx',
        'port' => '465',
        'encryption' => 'ssl',
    ],
];
