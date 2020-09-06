<?php

use Cake\Cache\Engine\FileEngine;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Error\ExceptionRenderer;
use Cake\Log\Engine\FileLog;
use Cake\Mailer\Transport\MailTransport;

return [
    /**
     * オークション商品情報の画像保存パス:
     *     */
    'Auction' => [
      'biditemImgBaseUrl' => 'auction/biditems/',
    ],
];
