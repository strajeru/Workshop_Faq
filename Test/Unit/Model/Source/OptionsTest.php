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
namespace Workshop\Faq\Test\Unit\Model\Source;

use Workshop\Faq\Model\Source\Options;

class OptionsTest extends \PHPUnit_Framework_TestCase
{
    protected $options;
    protected function setUp()
    {
        $this->options = [
            [
                'value' => 'value1',
                'label' => 'label1',
            ],
            [
                'value' => 'value2',
                'label' => 'label2',
            ],
            [
                'value' => 'value3',
                'label' => 'label3',
            ],
        ];
    }

    /**
     * @test Options::getOptionText()
     */
    public function testGetOptionText()
    {
        $options = new Options($this->options);
        $this->assertEquals('label1, label3', $options->getOptionText('value1,value3'));
        $this->assertEquals('label1', $options->getOptionText('value1,value4'));
    }

    /**
     * @test Options::getOptions()
     */
    public function testGetOptions()
    {
        $options = new Options($this->options);
        $expected = [
            'value1' => 'label1',
            'value2' => 'label2',
            'value3' => 'label3',
        ];
        $this->assertEquals($expected, $options->getOptions());
    }
}
