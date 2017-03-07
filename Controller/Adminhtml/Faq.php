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
namespace Workshop\Faq\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Workshop\Faq\Api\FaqRepositoryInterface;

abstract class Faq extends Action
{
    protected $_publicActions = ['new'];
    const ADMIN_RESOURCE = 'workshop_faq_faq';
    /**
     * Core registry
     * 
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * FAQ repository
     * 
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * Page factory
     * 
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * constructor
     * 
     * @param Context $context
     * @param Registry $coreRegistry
     * @param FaqRepositoryInterface $faqRepository
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FaqRepositoryInterface $faqRepository,
        PageFactory $resultPageFactory
    )
    {
        $this->coreRegistry      = $coreRegistry;
        $this->faqRepository     = $faqRepository;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

}
