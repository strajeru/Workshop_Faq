<?php
/**
 * Workshop_Faq extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Workshop
 * @package   Workshop_Faq
 * @copyright Copyright (c) 2017
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 */
namespace Workshop\Faq\Model\Faq;

use Workshop\Faq\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Workshop\Faq\Model\ResourceModel\Faq\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Workshop\Faq\Model\Faq $faq */
        foreach ($items as $faq) {
            $this->loadedData[$faq->getId()] = $faq->getData();
        }

        $data = $this->dataPersistor->get('workshop_faq_faq');
        if (!empty($data)) {
            $faq = $this->collection->getNewEmptyItem();
            $faq->setData($data);
            $this->loadedData[$faq->getId()] = $faq->getData();
            $this->dataPersistor->clear('workshop_faq_faq');
        }

        return $this->loadedData;
    }
}
