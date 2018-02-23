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
     * @param $size
     * @param string $unit
     * @return string
     */
    public function humanFileSize($size, $unit = "")
    {
        $filesize = '';

        if ((!$unit && $size >= 1 << 30) || $unit == "GB") {
            $filesize = number_format($size / (1 << 30), 0) . " GB";
        }
        if ((!$unit && $size >= 1 << 20) || $unit == "MB") {
            $filesize = number_format($size / (1 << 20), 0) . " MB";
        }
        if ((!$unit && $size >= 1 << 10) || $unit == "KB") {
            $filesize = number_format($size / (1 << 10), 0) . " KB";
        }
        /* for real human size please comment "if" statements below */
        if ($size >= 1000000) {
            $filesize = '(>=) ' . number_format(1.68, 2) . " MB";
        }
        if ($size < 1000000) {
            $filesize = '(<=) ' . number_format(423, 0) . " KB";
        }
        if ($size == '') {
            $filesize = '';
        }

        return $filesize;
    }
}
