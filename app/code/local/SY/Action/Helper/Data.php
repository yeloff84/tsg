<?php

/**
 * Class Sy_Action_Helper_Data
 */
class Sy_Action_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param int $id
     * @return string
     */
    public function getImagePath($id = 0)
    {
        $path = Mage::getBaseDir('media') . '/actions';
        if ($id) {
            return "{$path}/{$id}.jpg";
        } else {
            return $path;
        }
    }

    /**
     * @param int $id
     * @return string
     */
    public function getImageUrl($id = 0)
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'actions/';
        if ($id) {
            return $url . $id . '.jpg';
        } else {
            return $url;
        }
    }


    /**
     * @param $imgName
     * @return string
     */
    public function getBaseUploadPath($imgName = '')
    {

        if ($imgName != '') {
            return Mage::getBaseDir('media') . DS . 'actions' . DS . $imgName;
        } else {
            return Mage::getBaseDir('media') . DS . 'actions' . DS;
        }
    }

    /**
     * @param $imageName
     * @param null $width
     * @param null $height
     * @param bool $withMedia
     * @return string
     */
    public function resizeImage($imageName, $width = null, $height = null, $withMedia = true)
    {

        $imagePathFull = $this->getBaseUploadPath($imageName);

        if ($width == null && $height == null) {
            $width = 100;
            $height = 100;
        }

        $resizePath = $width . 'x' . $height;

        $resizePathFull = Mage::getBaseDir('media') . DS . 'actions' . DS . $resizePath . DS . $imageName;

        if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
            $imageObj = new Varien_Image($imagePathFull);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepFrame(true);
            $imageObj->keepTransparency(true);
            $imageObj->constrainOnly(true);
            $imageObj->backgroundColor(array(255, 255, 255));
            $imageObj->quality(90);
            $imageObj->resize($width, $height);
            $imageObj->save($resizePathFull);
        }


        if($withMedia == true) {
            return '/media/actions/' . $resizePath . '/' . $imageName;
        } else {
            return 'actions/' . $resizePath . '/' . $imageName;
        }
    }
}
