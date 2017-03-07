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

use Workshop\Faq\Api\Data\FaqInterface;
use Workshop\Faq\Controller\Adminhtml\Faq\MassAction;

class MassDelete extends MassAction
{
    /**
     * @param \Workshop\Faq\Api\Data\FaqInterface $faq
     * @return $this
     */
    protected function massAction(FaqInterface $faq)
    {
        $this->faqRepository->delete($faq);
        return $this;
    }
}
