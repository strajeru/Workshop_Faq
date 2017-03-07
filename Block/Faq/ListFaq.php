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
namespace Workshop\Faq\Block\Faq;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager;
use Workshop\Faq\Model\Faq;
use Workshop\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;

class ListFaq extends Template
{
    /**
     * @var FaqCollectionFactory
     */
    protected $faqCollectionFactory;
    /**
     * @var \Workshop\Faq\Model\ResourceModel\Faq\Collection
     */
    protected $faqs;
    /**
     * @param Context $context
     * @param FaqCollectionFactory $faqCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        FaqCollectionFactory $faqCollectionFactory,
        array $data = []
    ) {
        $this->faqCollectionFactory = $faqCollectionFactory;
        parent::__construct($context, $data);
    }
    /**
     * @return \Workshop\Faq\Model\ResourceModel\Faq\Collection
     */
    public function getFaqs()
    {
        if (is_null($this->faqs)) {
            $this->faqs = $this->faqCollectionFactory->create()
                ->addFieldToSelect('*')
                ->addFieldToFilter('is_active', Faq::STATUS_ENABLED)
                ->setOrder('sort_order', 'ASC');
        }
        return $this->faqs;
    }

    /**
     * @param Faq $faq
     * @return string
     */
    public function getFaqUrl(Faq $faq)
    {
        return $this->getUrl('workshop_faq/faq/view', ['id' => $faq->getId()]);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var \Magento\Theme\Block\Html\Pager $pager */
        $pager = $this->getLayout()->createBlock(Pager::class, 'workshop_faq.faq.list.pager');
        $pager->setCollection($this->getFaqs());
        $this->setChild('pager', $pager);
        $this->getFaqs()->load();
        return $this;
    }
    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
