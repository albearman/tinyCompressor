<?php
/** @var modX $modx */
/** @var tinyCompressor $compressor */
switch ($modx->event->name) {
    case 'OnFileManagerUpload':

        $compressor = $modx->getService('tinycompressor', 'tinyCompressor', $modx->getOption('tinycompressor_core_path', null,
                $modx->getOption('core_path') . 'components/tinycompressor/').'model/tinycompressor/',$scriptProperties);
        $compressor->compression($files, $directory, $source);
        break;

    case 'OnFileManagerFileCreate':
        $compressor = $modx->getService('tinycompressor', 'tinyCompressor', $modx->getOption('tinycompressor_core_path', null,
                $modx->getOption('core_path') . 'components/tinycompressor/').'model/tinycompressor/',$scriptProperties);

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

        if ( $modx->getOption('tinycompressor_tinypng_thumb_enable', $config,true) == true ) {
            $compressor = $modx->getService('tinycompressor', 'tinyCompressor', $modx->getOption('tinycompressor_core_path', null,
                    $modx->getOption('core_path') . 'components/tinycompressor/') . 'model/tinycompressor/', $scriptProperties);
            $compressor->compressionImage($filename);
        }

        break;

}