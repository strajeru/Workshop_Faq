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
namespace Workshop\Faq\Block\Adminhtml\Faq\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Workshop\Faq\Api\FaqRepositoryInterface;

class Generic
{
    /**
     * Widget Context
     * 
     * @var Context
     */
    protected $context;

    /**
     * FAQ Repository
     * 
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * constructor
     * 
     * @param Context $context
     * @param FaqRepositoryInterface $faqRepository
     */
    public function __construct(
        Context $context,
        FaqRepositoryInterface $faqRepository
    )
    {
        $this->context       = $context;
        $this->faqRepository = $faqRepository;
    }

    /**
     * Return FAQ ID
     *
     * @return int|null
     */
    public function getFaqId()
    {
        $requestId = $this->context->getRequest()->getParam('faq_id');
        if (!$requestId) {
            return null;
        }
        try {
            return $this->faqRepository->getById($requestId)->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
