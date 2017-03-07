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
namespace Workshop\Faq\Plugin\Block;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Tree\Node;
use Magento\Theme\Block\Html\Topmenu as TopmenuBlock;
use Magento\Framework\UrlInterface;

class Topmenu
{
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var Http
     */
    protected $request;

    /**
     * @param UrlInterface $url
     * @param Http $request
     */
    public function __construct(
        UrlInterface $url,
        Http $request
    ) {
        $this->url      = $url;
        $this->request  = $request;
    }

    /**
     * @param TopmenuBlock $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    // @codingStandardsIgnoreStart
    public function beforeGetHtml(
        TopmenuBlock $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        // @codingStandardsIgnoreEnd
        $node = new Node(
            $this->getNodeAsArray(),
            'id',
            $subject->getMenu()->getTree(),
            $subject->getMenu()
        );
        $subject->getMenu()->addChild($node);
    }

    /**
     * @return array
     */
    protected function getNodeAsArray()
    {
        return [
            'name' => __('FAQs'),
            'id' => 'faq-node',
            'url' => $this->url->getUrl('workshop_faq/faq'),
            'has_active' => false,
            'is_active' => in_array($this->request->getFullActionName(), $this->getActiveHandles())
        ];
    }

    /**
     * @return array
     */
    protected function getActiveHandles()
    {
        return [
            'workshop_faq_faq_index',
            'workshop_faq_faq_view'
        ];
    }
}
