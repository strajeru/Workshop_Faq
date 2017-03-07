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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Workshop\Faq\Api\Data\FaqInterface;
use Workshop\Faq\Api\FaqRepositoryInterface;
use Workshop\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;

abstract class MassAction extends Action
{
    /**
     * FAQ repository
     *
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * Mass Action filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * FAQ collection factory
     *
     * @var FaqCollectionFactory
     */
    protected $collectionFactory;

    /**
     * Action success message
     *
     * @var string
     */
    protected $successMessage;

    /**
     * Action error message
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * constructor
     *
     * @param Context $context
     * @param FaqRepositoryInterface $faqRepository
     * @param Filter $filter
     * @param FaqCollectionFactory $collectionFactory
     * @param string $successMessage
     * @param string $errorMessage
     */
    public function __construct(
        Context $context,
        FaqRepositoryInterface $faqRepository,
        Filter $filter,
        FaqCollectionFactory $collectionFactory,
        $successMessage,
        $errorMessage
    )
    {
        $this->faqRepository     = $faqRepository;
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->successMessage    = $successMessage;
        $this->errorMessage      = $errorMessage;
        parent::__construct($context);
    }

    /**
     * @param \Workshop\Faq\Api\Data\FaqInterface $faq
     * @return mixed
     */
    protected abstract function massAction(FaqInterface $faq);

    /**
     * execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $faq) {
                $this->massAction($faq);
            }
            $this->messageManager->addSuccessMessage(sprintf($this->successMessage, $collectionSize));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $this->errorMessage);
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('workshop_faq/*/index');
        return $redirectResult;
    }
}
