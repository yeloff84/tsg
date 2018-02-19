<?php

/**
 * Class Sy_Action_Block_List
 */
class Sy_Action_Block_List extends Mage_Core_Block_Template
{
    /**
     * @return mixed
     */
    public function getActionsList() {

        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');
        $dateHelper = Mage::helper('core');

        $collection = Mage::getModel('action/action')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('start_datetime', array('lteq' => $curdate))
            ->addFieldToFilter('end_datetime', array(
                array('gteq' => $curdate),
                array('null' => true)
            ))
            ->addFieldToFilter('is_active', 1)
            ->setOrder('start_datetime','DESC');

        foreach ($collection as $col) {

            $col->setResizedImage(Mage::helper('sy_action')->resizeImage($col->getImage(), 200, 200));

            if (!$col->getEndDatetime()) {
                $col->setDuration('never ending');
            } else {
                $col->setDuration(
                    'untill ' . $dateHelper->formatDate($col->getEndDatetime(), 'short', false)
                );
            }
        }

        return $collection;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();

        return $this;
    }

    public function getPagerHtml()
    {
        if ($this->getCollection()->getSize() > 10) {
            return $this->getChildHtml('pager');
        } else {
            return '';
        }
    }

    public function getCollection()
    {
        $limit = 1;
        $curr_page = 10;

        if (Mage::app()->getRequest()->getParam('p')) {
            $curr_page = Mage::app()->getRequest()->getParam('p');
        }

        $offset = ($curr_page - 1) * $limit;

        $collection = $this->getActionsList();

        $collection->getSelect()->limit($limit, $offset);

        return $collection;
    }
}
