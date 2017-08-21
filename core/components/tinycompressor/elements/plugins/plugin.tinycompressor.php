<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnFileManagerUpload':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($modx->getOption('core_path') . 'components/tinycompressor/model/tinycompressor/tinycompressor.class.php');
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;
}