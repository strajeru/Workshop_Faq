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
namespace Workshop\Faq\Api\Data;

/**
 * @api
 */
interface FaqInterface
{
    /**
     * Question attribute constant
     * 
     * @var string
     */
    const QUESTION = 'question';

    /**
     * Answer attribute constant
     * 
     * @var string
     */
    const ANSWER = 'answer';

    /**
     * Sort Order attribute constant
     * 
     * @var string
     */
    const SORT_ORDER = 'sort_order';

    /**
     * Is Active attribute constant
     * 
     * @var string
     */
    const IS_ACTIVE = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Question
     *
     * @return string
     */
    public function getQuestion();

    /**
     * Set Question
     *
     * @param $question
     * @return FaqInterface
     */
    public function setQuestion($question);

    /**
     * Get Answer
     *
     * @return string
     */
    public function getAnswer();

    /**
     * Set Answer
     *
     * @param $answer
     * @return FaqInterface
     */
    public function setAnswer($answer);

    /**
     * Get Sort Order
     *
     * @return string
     */
    public function getSortOrder();

    /**
     * Set Sort Order
     *
     * @param $sortOrder
     * @return FaqInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get Is Active
     *
     * @return string
     */
    public function getIsActive();

    /**
     * Set Is Active
     *
     * @param $isActive
     * @return FaqInterface
     */
    public function setIsActive($isActive);

}
