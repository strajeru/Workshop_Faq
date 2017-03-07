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
namespace Workshop\Faq\Test\Unit\Model;

use Workshop\Faq\Model\Output;

class OutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | \Zend_Filter_Interface
     */
    protected $templateProcessor;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->templateProcessor = $this->getMockBuilder(\Zend_Filter_Interface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->templateProcessor = null;
        parent::tearDown();
    }

    /**
     * @return string
     */
    public function filterContentMock()
    {
        $args = func_get_args();
        $value = (isset($args[0])) ? $args[0] : '';
        $value .= '_processed';
        return $value;
    }

    /**
     * @test Output::filterOutput
     */
    public function testFilterOutput()
    {
        $this->templateProcessor->method('filter')->willReturnCallback([$this, 'filterContentMock']);
        $ouputProcessor = new Output($this->templateProcessor);
        $this->assertEquals('string_processed', $ouputProcessor->filterOutput('string'));
    }
}
