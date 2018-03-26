<?php
namespace Absolunet\Cli\Api\Data;

interface RulesInterface
{
    /**
     * Install tax rules
     *
     * @param array $country
     * @param bool $keepSettings
     * @return bool|string
     */
    public function install($country, $keepSettings);
}
