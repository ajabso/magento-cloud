<?php
namespace Absolunet\Cli\Model;

use Absolunet\Cli\Api\Data\StoreViewInterface;
use Magento\Store\Model\ScopeInterface;

class StoreView extends \Magento\Framework\Model\AbstractModel implements StoreViewInterface
{
    /** @var \Magento\Store\Api\Data\StoreInterface */
    protected $storeModel;

    /** @var \Magento\Store\Api\StoreRepositoryInterface */
    protected $storeRepository;

    /** @var \Magento\Store\Api\GroupRepositoryInterface */
    protected $groupRepository;

    /** @var \Magento\Framework\Event\ManagerInterface */
    protected $eventManager;

    /** @var \Magento\Framework\App\Config\Storage\WriterInterface */
    protected $configWriter;

    /**
     * StoreView constructor.
     * @param \Magento\Store\Api\Data\StoreInterface $storeModel
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\Store\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Api\Data\StoreInterface $storeModel,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Store\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->storeModel = $storeModel;
        $this->storeRepository = $storeRepository;
        $this->groupRepository = $groupRepository;
        $this->eventManager = $context->getEventDispatcher();
        $this->configWriter = $configWriter;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Create a new store view
     *
     * @param array $locale
     * @param int $storeID
     * @return bool|string
     */
    public function create($locale, $storeID)
    {
        $datas = $this->prepareDatas($locale, $storeID);

        $this->storeModel->setData($datas);
        $storeGroup = $this->groupRepository->get($this->storeModel->getGroupId());
        $this->storeModel->setWebsiteId($storeGroup->getWebsiteId());

        try {
            $this->storeModel->getResource()->save($this->storeModel);
            $this->groupRepository->clean();
            $this->eventManager->dispatch('store_add', ['store' => $this->storeModel]);

            $newStore = $this->storeRepository->get($this->storeModel->getCode());
            $this->configWriter->save(
                'general/locale/code',
                $locale,
                ScopeInterface::SCOPE_STORES,
                $newStore['store_id']
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }

    /**
     * Get country and region from locale
     *
     * @param string $locale
     * @return array
     */
    protected function getLocaleIdentifiers($locale)
    {
        $values = explode('_', $locale);
        $identifiers['country'] = $values[0];
        $identifiers['region'] = $values[1];

        return $identifiers;
    }

    /**
     * Get language name from country ISO 2 code
     *
     * @param string $isoLang
     * @return string
     */
    protected function getLanguageName($isoLang)
    {
        return ucfirst(\Locale::getDisplayLanguage($isoLang, $isoLang));
    }

    /**
     * Prepare store datas to save
     *
     * @param string $locale
     * @param int $storeID
     * @return array
     */
    protected function prepareDatas($locale, $storeID)
    {
        $identifiers = $this->getLocaleIdentifiers($locale);
        $datas = [];
        $datas['id'] = null;
        $datas['group_id'] = $storeID;
        $datas['code'] = $identifiers['country'];
        $datas['name'] = $this->getLanguageName($identifiers['country']);
        $datas['is_active'] = 1;

        return $datas;
    }
}
