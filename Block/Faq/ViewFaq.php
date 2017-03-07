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

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Workshop\Faq\Model\Faq;
use Workshop\Faq\Model\Output;

class ViewFaq extends Template
{
    /**
     * @var Registry
     */
    protected $coreRegistry;
    /**
     * @var Output
     */
    protected $outputProcessor;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Output $outputProcessor,
        array $data = []
    )
    {
        $this->coreRegistry = $registry;
        $this->outputProcessor = $outputProcessor;
        parent::__construct($context, $data);
    }

    /**
     * get current FAQ
     *
     * @return \Workshop\Faq\Model\Faq
     */
    public function getCurrentFaq()
    {
        return $this->coreRegistry->registry('current_faq');
    }

    /**
     * @param Faq $faq
     * @param $field
     * @return string
     */
    public function getProcessedValue(Faq $faq, $field)
    {
        return $this->outputProcessor->filterOutput($faq->getDataUsingMethod($field));
    }
}
