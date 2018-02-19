<?php

/**
 * Class Sy_Action_Block_List
 */
class Sy_Action_Block_List extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
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
            ->setCurPage($curr_page)
            ->setPageSize($limit);

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
        $pager->setCollection($this->getActionsList());
        $this->setChild('pager', $pager);
        $this->getActionsList()->load();

        return $this;
    }

    public function getPagerHtml()
    {
        if ($this->getActionsList()->getSize() > 10) {
            return $this->getChildHtml('pager');
        } else {
            return '';
        }
    }
}
