<?php
namespace Absolunet\Cli\Api\Data;

interface RatesInterface
{
    /**
     * Install tax rates
     *
     * @param array $country
     * @param bool $keepSettings
     * @return bool|string
     */
    public function install($country, $keepSettings);
}
