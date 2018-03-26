<?php

/**
 * @author     Alexandre Poirier <apoirier@absolunet.com>
 * @author     Cyril Ekoule <cekoule@absolunet.com>
 * @copyright  Copyright (c) 2017 Absolunet (http://www.absolunet.com)
 * @link       http://www.absolunet.com
 */

namespace Absolunet\Cli\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /** @var  DirectoryList */
    protected $directoryList;

    /**
     * Data constructor.
     *
     * @param Context       $context
     * @param DirectoryList $directoryList
     */
    public function __construct(Context $context, DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;

        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getCsvImportPath()
    {
        return $this->directoryList->getPath(DirectoryList::VAR_DIR)
            . DIRECTORY_SEPARATOR
            . 'import'
            . DIRECTORY_SEPARATOR
            . 'csv'
            . DIRECTORY_SEPARATOR;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        $mtime  = microtime();
        $mtime  = explode(" ", $mtime);
        $result = $mtime[1] + $mtime[0];

        return $result;
    }

    /**
     * @param float $time
     *
     * @return string
     */
    public function formatTime($time)
    {
        return sprintf(
            '%02d h %02d m %02d s',
            ($time / 3600),
            ($time / 60 % 60),
            $time % 60
        );
    }
}
