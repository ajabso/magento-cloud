<?php

/**
 * @author     Alexandre Poirier <apoirier@absolunet.com>
 * @copyright  Copyright (c) 2016 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */
namespace Absolunet\Cli\Logger;

use Magento\Framework\App\Filesystem\DirectoryList;
use Monolog\Logger as Log;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /** @var DirectoryList */
    protected $dir;

    /** @var int */
    protected $loggerType = Log::INFO;

    /** @var string */
    protected $fileName = '/var/log/dataScript.log';
}
