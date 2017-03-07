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
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Workshop\Faq\Api\Data\FaqInterface;
use Workshop\Faq\Api\FaqRepositoryInterface;
use Workshop\Faq\Controller\Adminhtml\Faq as FaqController;
use Workshop\Faq\Model\ResourceModel\Faq as FaqResourceModel;

class InlineEdit extends FaqController
{
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
     * Data object processor
     *
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * Data object helper
     *
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * JSON Factory
     *
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * FAQ resource model
     *
     * @var FaqResourceModel
     */
    protected $faqResourceModel;

    /**
     * constructor
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param FaqRepositoryInterface $faqRepository
     * @param PageFactory $resultPageFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param JsonFactory $jsonFactory
     * @param FaqResourceModel $faqResourceModel
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FaqRepositoryInterface $faqRepository,
        PageFactory $resultPageFactory,
        DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        JsonFactory $jsonFactory,
        FaqResourceModel $faqResourceModel
    )
    {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->dataObjectHelper    = $dataObjectHelper;
        $this->jsonFactory         = $jsonFactory;
        $this->faqResourceModel    = $faqResourceModel;
        parent::__construct($context, $coreRegistry, $faqRepository, $resultPageFactory);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $faqId) {
            /** @var \Workshop\Faq\Model\Faq|\Workshop\Faq\Api\Data\FaqInterface $faq */
            $faq = $this->faqRepository->getById((int)$faqId);
            try {
                $faqData = $postItems[$faqId];
                $this->dataObjectHelper->populateWithArray($faq, $faqData , FaqInterface::class);
                $this->faqResourceModel->saveAttribute($faq, array_keys($faqData));
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithFaqId($faq, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithFaqId($faq, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithFaqId(
                    $faq,
                    __('Something went wrong while saving the FAQ.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add FAQ id to error message
     *
     * @param \Workshop\Faq\Api\Data\FaqInterface $faq
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithFaqId(FaqInterface $faq, $errorText)
    {
        return '[FAQ ID: ' . $faq->getId() . '] ' . $errorText;
    }
}
