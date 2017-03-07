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
namespace Workshop\Faq\Model;

use Magento\Framework\Model\AbstractModel;
use Workshop\Faq\Api\Data\FaqInterface;
use Workshop\Faq\Model\ResourceModel\Faq as FaqResourceModel;

/**
 * @method FaqResourceModel _getResource()
 * @method FaqResourceModel getResource()
 */
class Faq extends AbstractModel implements FaqInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'workshop_faq_faq';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'workshop_faq_faq';

    /**
     * Event object
     * 
     * @var string
     */
    protected $_eventObject = 'faq';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(FaqResourceModel::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    /**
     * set Question
     *
     * @param $question
     * @return FaqInterface
     */
    public function setQuestion($question)
    {
        return $this->setData(FaqInterface::QUESTION, $question);
    }

    /**
     * get Question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->getData(FaqInterface::QUESTION);
    }
    /**
     * set Answer
     *
     * @param $answer
     * @return FaqInterface
     */
    public function setAnswer($answer)
    {
        return $this->setData(FaqInterface::ANSWER, $answer);
    }

    /**
     * get Answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->getData(FaqInterface::ANSWER);
    }
    /**
     * set Sort Order
     *
     * @param $sortOrder
     * @return FaqInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(FaqInterface::SORT_ORDER, $sortOrder);
    }

    /**
     * get Sort Order
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getData(FaqInterface::SORT_ORDER);
    }
    /**
     * set Is Active
     *
     * @param $isActive
     * @return FaqInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(FaqInterface::IS_ACTIVE, $isActive);
    }

    /**
     * get Is Active
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->getData(FaqInterface::IS_ACTIVE);
    }
}
