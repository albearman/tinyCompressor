<?php
/** @var modX $modx */

$tcFile = $modx->getOption('core_path') . 'components/tinycompressor/model/tinycompressor/tinycompressor.class.php';
switch ($modx->event->name) {
    case 'OnFileManagerUpload':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;

    case 'OnFileManagerFileCreate':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;

    case 'OnPhpThumbRenderToFile':
        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compressImage($filename);
        break;
}