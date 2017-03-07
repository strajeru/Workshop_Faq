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
namespace Workshop\Faq\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Workshop\Faq\Api\Data\FaqInterface;
use Workshop\Faq\Api\Data\FaqInterfaceFactory;
use Workshop\Faq\Api\Data\FaqSearchResultInterfaceFactory;
use Workshop\Faq\Api\FaqRepositoryInterface;
use Workshop\Faq\Model\ResourceModel\Faq as FaqResourceModel;
use Workshop\Faq\Model\ResourceModel\Faq\Collection;
use Workshop\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;

class FaqRepository implements FaqRepositoryInterface
{
    /**
     * Cached instances
     * 
     * @var array
     */
    protected $instances = [];

    /**
     * FAQ resource model
     * 
     * @var FaqResourceModel
     */
    protected $resource;

    /**
     * FAQ collection factory
     * 
     * @var FaqCollectionFactory
     */
    protected $faqCollectionFactory;

    /**
     * FAQ interface factory
     * 
     * @var FaqInterfaceFactory
     */
    protected $faqInterfaceFactory;

    /**
     * Data Object Helper
     * 
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var FaqSearchResultInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * constructor
     * 
     * @param FaqResourceModel $resource
     * @param FaqCollectionFactory $faqCollectionFactory
     * @param FaqInterfaceFactory $faqInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        FaqResourceModel $resource,
        FaqCollectionFactory $faqCollectionFactory,
        FaqInterfaceFactory $faqInterfaceFactory,
        FaqSearchResultInterfaceFactory $faqSearchResultsInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    )
    {
        $this->resource             = $resource;
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->faqInterfaceFactory  = $faqInterfaceFactory;
        $this->searchResultsFactory = $faqSearchResultsInterfaceFactory;
        $this->dataObjectHelper     = $dataObjectHelper;
    }

    /**
     * Save FAQ.
     *
     * @param \Workshop\Faq\Api\Data\FaqInterface $faq
     * @return \Workshop\Faq\Api\Data\FaqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(FaqInterface $faq)
    {
        /** @var FaqInterface|\Magento\Framework\Model\AbstractModel $faq */
        try {
            $this->resource->save($faq);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the FAQ: %1',
                $exception->getMessage()
            ));
        }
        return $faq;
    }

    /**
     * Retrieve FAQ.
     *
     * @param int $faqId
     * @return \Workshop\Faq\Api\Data\FaqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($faqId)
    {
        if (!isset($this->instances[$faqId])) {
            /** @var FaqInterface|\Magento\Framework\Model\AbstractModel $faq */
            $faq = $this->faqInterfaceFactory->create();
            $this->resource->load($faq, $faqId);
            if (!$faq->getId()) {
                throw new NoSuchEntityException(__('Requested FAQ doesn\'t exist'));
            }
            $this->instances[$faqId] = $faq;
        }
        return $this->instances[$faqId];
    }

    /**
     * Retrieve FAQs matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Workshop\Faq\Api\Data\FaqSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Workshop\Faq\Api\Data\FaqSearchResultInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Workshop\Faq\Model\ResourceModel\Faq\Collection $collection */
        $collection = $this->faqCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var \Magento\Framework\Api\Search\FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? SortOrder::SORT_ASC : SortOrder::SORT_DESC
                );
            }
        } else {
            // set a default sorting order since this method is used constantly in many
            // different blocks
            $field = 'faq_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        /** @var FaqInterface[] $faqs */
        $faqs = [];
        /** @var \Workshop\Faq\Model\Faq $faq */
        foreach ($collection as $faq) {
            /** @var FaqInterface $faqDataObject */
            $faqDataObject = $this->faqInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $faqDataObject,
                $faq->getData(),
                FaqInterface::class
            );
            $faqs[] = $faqDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($faqs);
    }

    /**
     * Delete FAQ.
     *
     * @param FaqInterface $faq
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(FaqInterface $faq)
    {
        /** @var FaqInterface|\Magento\Framework\Model\AbstractModel $faq */
        $id = $faq->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($faq);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new StateException(
                __('Unable to remove FAQ %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete FAQ by ID.
     *
     * @param int $faqId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($faqId)
    {
        $faq = $this->getById($faqId);
        return $this->delete($faq);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    )
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }
}
