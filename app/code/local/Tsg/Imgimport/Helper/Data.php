<?php

/**
 * Class Tsg_Imgimport_Helper_Data
 */

class Tsg_Imgimport_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $fileName
     * @return string
     */
    public function getBaseUploadPath($fileName = '')
    {
        if ($fileName != '') {
            return Mage::getBaseDir('media') . DS . 'tsg_imgimport' . DS . $fileName;
        } else {
            return Mage::getBaseDir('media') . DS . 'tsg_imgimport' . DS;
        }
    }

    /**
     * Getting CSV data from uploaded file
     * @param $file
     * @return array|string
     */
    public function getCsvData($file)
    {
        $csvObject = new Varien_File_Csv();

        try {
            return $csvObject->setDelimiter(',')->getData($file);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Return human readable file size
     *
     * @param $size
     * @param string $unit
     * @return string
     */
    public function humanFileSize($size, $unit = "")
    {
        if ((!$unit && $size >= 1 << 20) || $unit == "MB") {
            $fileSize = number_format($size / (1 << 20), 2) . " MB";
        } elseif ((!$unit && ($size > 0 && $size < 1 << 20) || $unit == "KB")) {
            $fileSize = number_format($size / (1 << 10), 0) . " KB";
        } else {
            $fileSize = '';
        }
        return $fileSize;
    }

    /**
     * Check for image duplicates
     *
     * @param $productId
     * @param $imgPath
     * @return bool
     */
    public function isUniqueImg($product, $imgPath)
    {
        $status = true;

        /** @var Mage_Catalog_Model_Product $product */
        $images = $product->getMediaGalleryImages();
        $imgObject = new Varien_Image($imgPath);
        $imgSize = filesize($imgPath);
        $imgX = $imgObject->getOriginalWidth();
        $imgY = $imgObject->getOriginalHeight();
        $imgType = $imgObject->getMimeType();

        foreach ($images as $key => $im) {
            $pImgObject = new Varien_Image($im['path']);
            if (filesize($im['path']) == $imgSize &&
                $pImgObject->getOriginalWidth() == $imgX &&
                $pImgObject->getOriginalHeight() == $imgY &&
                $pImgObject->getMimeType() == $imgType) {
                $status = false;
                break;
            } else {
                $status = true;
            }
        }

        return $status;
    }
}
