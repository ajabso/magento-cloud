<?php chdir('/srv/www/');
require __DIR__ . '/../../app/bootstrap.php';
$bootstrap = Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
// MAGICENTO2 ACTION: cleanmagento2cache
$obj = $bootstrap->getObjectManager(); 
try{
    $_cacheTypeList = $obj->create('Magento\Framework\App\Cache\TypeListInterface');
    $_cacheFrontendPool = $obj->create('Magento\Framework\App\Cache\Frontend\Pool');
    $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
    foreach ($types as $type) {
        $_cacheTypeList->cleanType($type);
    }
    foreach ($_cacheFrontendPool as $cacheFrontend) {
        $cacheFrontend->getBackend()->clean();
    }
}catch(Exception $e){
    echo 'Error : '.$e->getMessage();die();
}echo '1';