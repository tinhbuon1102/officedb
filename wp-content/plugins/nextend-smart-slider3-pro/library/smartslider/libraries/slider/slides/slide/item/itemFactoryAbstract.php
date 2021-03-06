<?php

N2Loader::import('libraries.parse.parse');

abstract class N2SSPluginItemFactoryAbstract extends N2PluginBase {

    public $type = 'identifier';

    public $_title = '';

    protected $layerProperties = array();

    protected $priority = 1;

    protected $group = 'Basic';

    protected $class = '';

    public function onLoadItems(&$list) {
        require_once $this->getPath() . 'item.php';
        $list[$this->type] = $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }


    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getGroup() {
        return $this->group;
    }

    public function getClass() {
        return $this->class;
    }

    public function getLayerProperties() {
        return $this->layerProperties;
    }

    public function isLegacy() {
        return false;
    }

    /*
     * Default values, which will be parsed by JS on the admin for default values. It should contain only the fields from the configuration.xml.
     */
    public function getValues() {
        return array(
            'nothing' => 'Abstract'
        );
    }

    public function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR;
    }

    /**
     * @param $slide N2SmartSliderSlide
     * @param $data  N2Data
     *
     * @return N2Data
     */
    public function getFilled($slide, $data) {
        return $data;
    }

    /**
     * @param N2SmartSliderExport      $export
     * @param                          $data
     */
    public function prepareExport($export, $data) {
    }

    /**
     * @param N2SmartSliderImport $import
     * @param N2Data              $data
     *
     * @return N2Data
     */
    public function prepareImport($import, $data) {
        return $data;
    }

    public function prepareSample($data) {
        return $data;
    }

    public function fixImage($image) {
        return N2ImageHelper::fixed($image);
    }

    public function fixLightbox($url) {
        preg_match('/^([a-zA-Z]+)\[(.*)](.*)/', $url, $matches);
        if (!empty($matches) && $matches[1] == 'lightbox') {
            $images    = explode(',', $matches[2]);
            $newImages = array();
            foreach ($images AS $image) {
                $newImages[] = N2ImageHelper::fixed($image);
            }
            $url = 'lightbox[' . implode(',', $newImages) . ']' . $matches[3];
        }

        return $url;
    }

    public function loadResources($slider) {
        N2JS::addInlineFile($this->getPath() . "/" . $this->type . ".min.js");
    
    }
}