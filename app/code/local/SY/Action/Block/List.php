<?php

/**
 * Class Sy_Action_Block_List
 */
class Sy_Action_Block_List extends Mage_Core_Block_Template
{

    /**
     * Sy_Action_Block_List constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCollection($this->getActionsList());
    }

    /**
     * @return mixed
     */
    public function getActionsList() {

        $limit = 10;
        $curr_page = 1;

        if (Mage::app()->getRequest()->getParam('p')) {
            $curr_page = Mage::app()->getRequest()->getParam('p');
        }

        if (is_numeric(Mage::app()->getRequest()->getParam('limit'))
            && Mage::app()->getRequest()->getParam('limit') <= 50) {
            $limit = Mage::app()->getRequest()->getParam('limit');
        }

        /** @var Mage_Core_Model_Date $datemodel */
        $datemodel = Mage::getModel('core/date');
        $curdate = $datemodel->gmtDate('Y-m-d H:i:s');
        $dateHelper = Mage::helper('core');

        /** @var Sy_Action_Model_Action $collection */
        $collection = Mage::getModel('action/action')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('start_datetime', array('lteq' => $curdate))
            ->addFieldToFilter('end_datetime', array(
                array('gteq' => $curdate),
                array('null' => true)
            ))
            ->addFieldToFilter('is_active', 1)
            ->setOrder('start_datetime','DESC')
            ->setCurPage((int)$curr_page)
            ->setPageSize((int)$limit);

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

        /** @var Mage_Page_Block_Html_Pager $pager */
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);

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
}
