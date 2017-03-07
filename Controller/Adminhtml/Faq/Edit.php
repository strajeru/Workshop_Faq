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
namespace Workshop\Faq\Controller\Adminhtml\Faq;

use Workshop\Faq\Controller\Adminhtml\Faq as FaqController;
use Workshop\Faq\Controller\RegistryConstants;

class Edit extends FaqController
{
    public $_publicActions = ['edit'];
    const ACTIVE_MENU = 'Workshop_Faq::faq_faq';
    /**
     * Initialize current FAQ and set it in the registry.
     *
     * @return int
     */
    protected function initFaq()
    {
        $faqId = $this->getRequest()->getParam('faq_id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_FAQ_ID, $faqId);

        return $faqId;
    }

    /**
     * Edit or create FAQ
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $faqId = $this->initFaq();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ACTIVE_MENU);
        $resultPage->getConfig()->getTitle()->prepend(__('FAQs'));
        $resultPage->addBreadcrumb(__('FAQs'), __('FAQs'));
        $resultPage->addBreadcrumb(__('FAQs'), __('FAQs'), $this->getUrl('workshop_faq/faq'));

        if ($faqId === null) {
            $resultPage->addBreadcrumb(__('New FAQ'), __('New FAQ'));
            $resultPage->getConfig()->getTitle()->prepend(__('New FAQ'));
        } else {
            $resultPage->addBreadcrumb(__('Edit FAQ'), __('Edit FAQ'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->faqRepository->getById($faqId)->getQuestion()
            );
        }
        return $resultPage;
    }
}
