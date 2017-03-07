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
namespace Workshop\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Workshop\Faq\Api\Data\FaqInterface;

/**
 * @api
 */
interface FaqRepositoryInterface
{
    /**
     * Save FAQ.
     *
     * @param \Workshop\Faq\Api\Data\FaqInterface $faq
     * @return \Workshop\Faq\Api\Data\FaqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(FaqInterface $faq);

    /**
     * Retrieve FAQ
     *
     * @param int $faqId
     * @return \Workshop\Faq\Api\Data\FaqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($faqId);

    /**
     * Retrieve FAQs matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Workshop\Faq\Api\Data\FaqSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete FAQ.
     *
     * @param \Workshop\Faq\Api\Data\FaqInterface $faq
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(FaqInterface $faq);

    /**
     * Delete FAQ by ID.
     *
     * @param int $faqId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($faqId);
}
