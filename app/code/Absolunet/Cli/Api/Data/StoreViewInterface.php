<?php
namespace Absolunet\Cli\Api\Data;

interface StoreViewInterface
{
    /**
     * Create a new store view
     *
     * @param array $locale
     * @param int $storeID
     * @return bool|string
     */
    public function create($locale, $storeID);
}
