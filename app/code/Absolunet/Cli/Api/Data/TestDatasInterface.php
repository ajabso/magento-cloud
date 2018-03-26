<?php
namespace Absolunet\Cli\Api\Data;

interface TestDatasInterface
{
    /**
     * Delete specific datas by code
     *
     * @param array $code
     * @return bool|string
     */
    public function deleteByCode($code);
}
