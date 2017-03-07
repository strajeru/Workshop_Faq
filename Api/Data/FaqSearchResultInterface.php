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
interface FaqSearchResultInterface
{
    /**
     * Get FAQs list.
     *
     * @return \Workshop\Faq\Api\Data\FaqInterface[]
     */
    public function getItems();

    /**
     * Set FAQs list.
     *
     * @param \Workshop\Faq\Api\Data\FaqInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
