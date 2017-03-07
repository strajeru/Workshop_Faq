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

use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Workshop\Faq\Api\Data\FaqInterface;
use Workshop\Faq\Api\Data\FaqInterfaceFactory;
use Workshop\Faq\Api\FaqRepositoryInterface;
use Workshop\Faq\Controller\Adminhtml\Faq as FaqController;

class Save extends FaqController
{
    /**
     * FAQ factory
     * 
     * @var FaqInterfaceFactory
     */
    protected $faqFactory;

    /**
     * Data Object Processor
     * 
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * Data Object Helper
     * 
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * constructor
     * 
     * @param Context $context
     * @param Registry $coreRegistry
     * @param FaqRepositoryInterface $faqRepository
     * @param PageFactory $resultPageFactory
     * @param FaqInterfaceFactory $faqFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FaqRepositoryInterface $faqRepository,
        PageFactory $resultPageFactory,
        FaqInterfaceFactory $faqFactory,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper
    )
    {
        $this->faqFactory          = $faqFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper    = $dataObjectHelper;
        parent::__construct($context, $coreRegistry, $faqRepository, $resultPageFactory);
    }

    /**
     * run the action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Workshop\Faq\Api\Data\FaqInterface $faq */
        $faq = null;
        $data = $this->getRequest()->getPostValue();
        $id = !empty($data['faq_id']) ? $data['faq_id'] : null;
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($id) {
                $faq = $this->faqRepository->getById((int)$id);
            } else {
                unset($data['faq_id']);
                $faq = $this->faqFactory->create();
            }
            $this->dataObjectHelper->populateWithArray($faq, $data, FaqInterface::class);
            $this->faqRepository->save($faq);
            $this->messageManager->addSuccessMessage(__('You saved the FAQ'));
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('workshop_faq/faq/edit', ['faq_id' => $faq->getId()]);
            } else {
                $resultRedirect->setPath('workshop_faq/faq');
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            if ($faq != null) {
                $this->storeFaqDataToSession(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $faq,
                        FaqInterface::class
                    )
                );
            }
            $resultRedirect->setPath('workshop_faq/faq/edit', ['faq_id' => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There was a problem saving the {{EntityLabelEscaped}]'));
            if ($faq != null) {
                $this->storeFaqDataToSession(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $faq,
                        FaqInterface::class
                    )
                );
            }
            $resultRedirect->setPath('workshop_faq/faq/edit', ['faq_id' => $id]);
        }
        return $resultRedirect;
    }

    /**
     * @param $faqData
     */
    protected function storeFaqDataToSession($faqData)
    {
        $this->_getSession()->setWorkshopFaqFaqData($faqData);
    }
}
