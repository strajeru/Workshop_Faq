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

class Index extends FaqController
{
    const ACTIVE_MENU = 'Workshop_Faq::faq_faq';
    /**
     * FAQs list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ACTIVE_MENU);
        $resultPage->getConfig()->getTitle()->prepend(__('FAQs'));
        $resultPage->addBreadcrumb(__('FAQs'), __('FAQs'));
        $resultPage->addBreadcrumb(__('FAQs'), __('FAQs'));
        return $resultPage;
    }
}
