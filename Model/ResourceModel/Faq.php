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
namespace Workshop\Faq\Model\ResourceModel;

use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Faq extends AbstractDb
{
    /**
     * Event manager
     * 
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * constructor
     * 
     * @param EventManagerInterface $eventManager
     * @param Context $context
     */
    public function __construct(
        EventManagerInterface $eventManager,
        Context $context,
        $connectionName = null
    )
    {
        $this->eventManager = $eventManager;
        parent::__construct($context, $connectionName);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('workshop_faq_faq', 'faq_id');
    }

    /**
     * Retrieves FAQ Question from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getFaqQuestionById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'question')
            ->where('faq_id = :faq_id');
        $binds = ['faq_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }

    /**
     * @param AbstractModel $object
     * @param $attribute
     * @return $this
     * @throws \Exception
     */
    public function saveAttribute(AbstractModel $object, $attribute)
    {
        if (is_string($attribute)) {
            $attributes = [$attribute];
        } else {
            $attributes = $attribute;
        }
        if (is_array($attributes) && !empty($attributes)) {
            $this->getConnection()->beginTransaction();
            $data = array_intersect_key($object->getData(), array_flip($attributes));
            try {
                $this->beforeSaveAttribute($object, $attributes);
                if ($object->getId() && !empty($data)) {
                    $this->getConnection()->update(
                        $object->getResource()->getMainTable(),
                        $data,
                        [$object->getResource()->getIdFieldName() . '= ?' => (int)$object->getId()]
                    );
                    $object->addData($data);
                }
                $this->afterSaveAttribute($object, $attributes);
                $this->getConnection()->commit();
            } catch (\Exception $e) {
                $this->getConnection()->rollBack();
                throw $e;
            }
        }
        return $this;
    }

    /**
     * @param AbstractModel $object
     * @param $attribute
     * @return $this
     */
    protected function beforeSaveAttribute(AbstractModel $object, $attribute)
    {
        if ($object->getEventObject() && $object->getEventPrefix()) {
            $this->eventManager->dispatch(
                $object->getEventPrefix() . '_save_attribute_before',
                [
                    $object->getEventObject() => $this,
                    'object' => $object,
                    'attribute' => $attribute
                ]
            );
        }
        return $this;
    }

    /**
     * After save object attribute
     *
     * @param AbstractModel $object
     * @param string $attribute
     * @return $this
     */
    protected function afterSaveAttribute(AbstractModel $object, $attribute)
    {
        if ($object->getEventObject() && $object->getEventPrefix()) {
            $this->eventManager->dispatch(
                $object->getEventPrefix() . '_save_attribute_after',
                [
                    $object->getEventObject() => $this,
                    'object' => $object,
                    'attribute' => $attribute
                ]
            );
        }
        return $this;
    }
}
