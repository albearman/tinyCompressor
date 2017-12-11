<?php
/** @var modX $modx */
/** @var tinyCompressor $compressor */

$tinyCompressorDefaultPath = MODX_CORE_PATH . 'components/tinycompressor/model/tinycompressor/';

switch ($modx->event->name) {
    case 'OnFileManagerUpload':

        $compressor = $modx->getService('tinycompressor', 'tinyCompressor', $tinyCompressorDefaultPath,
            $scriptProperties);
        $compressor->compression($files, $directory, $source);
        break;

    case 'OnFileManagerFileCreate':
        $compressor = $modx->getService('tinycompressor', 'tinyCompressor', $tinyCompressorDefaultPath,
            $scriptProperties);

        $file_path_info = pathinfo($path);
        $ext = strtolower($file_path_info['extension']);
        if ( in_array($ext, array('jpeg', 'jpg', 'png', 'pdf')) ) {

            if (in_array($ext, array('jpeg', 'jpg', 'png'))) {
                $type = 'image/jpeg';
            }

            if ($ext == 'pdf') {
                $type = 'application/pdf';
            }

            $file = array(
                array(
                    'type' => $type,
                    'name' => $file_path_info['basename'],
                    'error' => 0
                )
            );
            $compressor->compression($file, $file_path_info['dirname']);
        }
        break;

    case 'OnPhpThumbRenderToFile':

        if ( $modx->getOption('tinycompressor_tinypng_thumb_enable', null,true) == true ) {
            $compressor = $modx->getService('tinycompressor', 'tinyCompressor', $tinyCompressorDefaultPath,
                $scriptProperties);
            $compressor->compressionImage($filename);
        }

        break;

}