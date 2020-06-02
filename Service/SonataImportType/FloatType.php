<?php


namespace Doctrs\SonataImportBundle\Service\SonataImportType;

class FloatType implements ImportInterface {

    public function getFormatValue($value) {
        return floatval($value);
    }

}
