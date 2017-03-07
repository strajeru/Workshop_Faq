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
namespace Workshop\Faq\Controller\Faq;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;
use Workshop\Faq\Api\FaqRepositoryInterface;
//use Sample\News\Model\Author\Url as UrlModel;

class View extends Action
{
    /**
     * @var string
     */
    const BREADCRUMBS_CONFIG_PATH = 'workshop_faq/faq/breadcrumbs';
    /**
     * @var \Workshop\Faq\Api\FaqRepositoryInterface
     */
    protected $faqRepository;
    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param FaqRepositoryInterface $faqRepository
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        FaqRepositoryInterface $faqRepository,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->faqRepository        = $faqRepository;
        $this->resultPageFactory    = $resultPageFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->scopeConfig          = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        try {
            $faqId = (int)$this->getRequest()->getParam('id');
            $faq = $this->faqRepository->getById($faqId);

            if (!$faq->getIsActive()) {
                throw new \Exception();
            }
        } catch (\Exception $e){
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');
            return $resultForward;
        }

        $this->coreRegistry->register('current_faq', $faq);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($faq->getQuestion());
        if ($this->scopeConfig->isSetFlag(self::BREADCRUMBS_CONFIG_PATH, ScopeInterface::SCOPE_STORE)) {
            /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbsBlock */
            $breadcrumbsBlock = $resultPage->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbsBlock) {
                $breadcrumbsBlock->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'link'  => $this->_url->getUrl('')
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'authors',
                    [
                        'label' => __('FAQs'),
                        'link'  => $this->_url->getUrl('workshop_faq/faq')
                    ]
                );
                $breadcrumbsBlock->addCrumb(
                    'faq-'.$faq->getId(),
                    [
                        'label' => $faq->getQuestion()
                    ]
                );
            }
        }

        return $resultPage;
    }
}
