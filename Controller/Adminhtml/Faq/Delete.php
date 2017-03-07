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

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Workshop\Faq\Controller\Adminhtml\Faq as FaqController;

class Delete extends FaqController
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('faq_id');
        if ($id) {
            try {
                $this->faqRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The FAQ has been deleted.'));
                $resultRedirect->setPath('workshop_faq/*/');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The FAQ no longer exists.'));
                return $resultRedirect->setPath('workshop_faq/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('workshop_faq/faq/edit', ['faq_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the FAQ'));
                return $resultRedirect->setPath('workshop_faq/faq/edit', ['faq_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a FAQ to delete.'));
        $resultRedirect->setPath('workshop_faq/*/');
        return $resultRedirect;
    }
}
