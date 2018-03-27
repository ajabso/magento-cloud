<?php
namespace Absolunet\Cli\Api\Data;

interface SettingsInterface
{
    /**
     * Install default settings
     *
     * @param array $args
     * @param array $settings
     * @return bool|string
     */
    public function install($args, $settings);
}
