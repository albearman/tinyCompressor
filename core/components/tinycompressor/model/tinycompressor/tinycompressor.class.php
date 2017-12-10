<?php

class tinyCompressor
{
    /** @var modX $modx */
    public $modx;

    /** @var Ilovepdf\Ilovepdf $iLovePDF
     *  @var Tinify\CrazyClient $tinyPNGCrazyClient
     *  @var Tinify\Client $tinyPNGClient
     */
    private $tinyPNGClient, $tinyPNGCrazyClient, $iLovePDF;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('tinycompressor_core_path', $config,
            $this->modx->getOption('core_path') . 'components/tinycompressor/'
        );

        $tinyUploadEnable = $this->modx->getOption('tinycompressor_tinypng_upload_enable', $config,
            false
        );

        $tinyThumbEnable = $this->modx->getOption('tinycompressor_tinypng_thumb_enable', $config,
            true
        );

        $tinyPNGApiKey = $this->modx->getOption('tinycompressor_tinypng_api_key', $config,
            ''
        );

        $iLovePDFUploadEnable = $this->modx->getOption('tinycompressor_ilovepdf_upload_enable', $config,
            false
        );

        $iLovePDFProjectID = $this->modx->getOption('tinycompressor_ilovepdf_project_id', $config,
            ''
        );
        $iLovePDFProjectKey = $this->modx->getOption('tinycompressor_ilovepdf_project_key', $config,
            ''
        );

        $iLovePDFCompressionLevel = $this->modx->getOption('tinycompressor_ilovepdf_compression_level', $config,
            'recommended'
        );

        if ( !in_array($iLovePDFCompressionLevel, array('low','recommended','extreme')) ) {
            $iLovePDFCompressionLevel = 'recommended';
        }

        $this->config = array_merge(array(
            'corePath'                  => $corePath,
            'modelPath'                 => $corePath . 'model/',
            'tinyUploadEnable'          => $tinyUploadEnable,
            'tinyThumbEnable'           => $tinyThumbEnable,
            'tinyPNGApiKey'             => $tinyPNGApiKey,
            'iLovePDFUploadEnable'      => $iLovePDFUploadEnable,
            'iLovePDFProjectID'         => $iLovePDFProjectID,
            'iLovePDFProjectKey'        => $iLovePDFProjectKey,
            'iLovePDFCompressionLevel'  => $iLovePDFCompressionLevel
        ), $config);

        $this->modx->lexicon->load('tinycompressor:default');
        $this->createClients();

    }

    private function createClients() {

        $this->createTinyPNG();
        $this->createILovePDF();

    }

    /**
     * @var modMediaSource $source
     * @var array $objects
     * @var string $container
     */
    function compression($objects, $container, $source = null) {

        /* Remove the time limit for running the script */
        set_time_limit(0);

        if ( !is_array($objects) ) {
            return false;
        }

        foreach ( $objects as $file ) {

            /* Get the path based on the variable $container */
            $path = $this->modx->getOption('base_path') . $container . $file['name'];

            if ($file['error'] != 0) {
                continue;
            }

            if (
                in_array( $file['type'],array('image/jpeg','image/png') )
                    &&
                $this->config('tinyUploadEnable') == false
            ) {
                continue;
            }

            if (
                $file['type'] == 'application/pdf'
                    &&
                $this->config('iLovePDFUploadEnable') == false
            ) {
                continue;
            }

            if ( !is_null($source) && ($source instanceof modMediaSource) ) {

                /* Correcting a path based on a variable $source */
                $basePath = $source->getBasePath();
                $sourceFilePath = $basePath . $file['name'];
                if ( file_exists($sourceFilePath) ) { // [mixedImage] fix when uploading through [MIGX]
                    $path = $sourceFilePath;
                } else {
                    $basePath = $this->modx->getOption('base_path') . $container;
                }
                unset($sourceFilePath);

                $pathInfo = pathinfo($path);
                $oldPath = $path;

                /* translate the name of the file */
                $fat = $this->modx->getOption('friendly_alias_translit');
                $friendly_alias_translit = (empty($fat) || $fat == 'none') ? false : true;
                $filename = modResource::filterPathSegment($this->modx, $pathInfo['filename']); //cleanAlias(translate)
                if ($friendly_alias_translit)
                {
                    $filename = preg_replace('/[^A-Za-z0-9_-]/', '', $filename); // restrict segment toalphanumeric characters only
                }
                $filename = preg_replace('/-{2,}/','-',$filename); // remove double symbol "-"
                $filename = trim($filename, '-'); // remove first symbol "-"

                $newPath = strtolower($filename . '.' . $pathInfo['extension']);
                if ($newPath !== $file['name']) {
                    $path = $basePath . $newPath;
                    $source->renameObject($oldPath, $newPath);
                }
            }


            if ( in_array( $file['type'],array('image/jpeg','image/png') ) ) {
                $this->compressionImage($path);
            }

            if ( $file['type'] == 'application/pdf' ) {
                $this->compressionPDF($path);
            }


        }

        /* Return the time limit for running the script */
        set_time_limit(30);

        return true;

    }



    function compressionImage($path, $mode = 'file', $new_path = false) {

        switch ($mode) {
            case 'file':

                if ( !file_exists($path) ) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('tinycompressor_error_file_not_found', array('path' => $path)));
                    return false;
                }

                if (filesize($path) < '5242880') {
                    \Tinify\Tinify::setClient($this->tinyPNGCrazyClient);
                } else {
                    if ($this->tinyPNGClient != false) {
                        \Tinify\Tinify::setClient($this->tinyPNGClient);
                    } else {
                        return false;
                    }
                }
                try {
                    $source = \Tinify\fromFile($path);
                } catch (Exception $e)
                {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('tinycompressor_error_compress', array('msg' => $e->getMessage())));
                    return false;
                }

                if ( $new_path != false ) {
                    $path = $new_path;
                }

                try {
                    $source->toFile($path);
                } catch (Exception $e)
                {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('tinycompressor_error_save', array('msg' => $e->getMessage())));
                    return false;
                }



                return $path;

            case 'url':

                if ($this->tinyPNGClient != false) {
                    \Tinify\Tinify::setClient($this->tinyPNGClient);
                } else {
                    return false;
                }

                $source = \Tinify\fromUrl($path);

                $source->toFile($new_path);

                return $new_path;

            default:
                return false;
        }

    }

    function compressionPDF($path) {

        /** @var Ilovepdf\Task $task */

        if ( $this->iLovePDF == false ) {
            return false;
        }

        if ( !file_exists($path) ) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('tinycompressor_error_file_not_found', array('path' => $path)));
            return false;
        }

        try {
            $task = $this->iLovePDF->newTask('compress');
            $task->setCompressionLevel($this->config['iLovePDFCompressionLevel']);
            $task->addFile($path);
            $task->execute();
            $task->download(pathinfo($path, PATHINFO_DIRNAME ));
        } catch (Exception $e) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR,$this->modx->lexicon('tinycompressor_error_compress', array('msg' => $e->getMessage())));
        }


        return $path;

    }

    function createTinyPNG() {

        require_once $this->config['modelPath'] . '/tinycompressor/lib/tinify/init_tinify.php';
        $this->tinyPNGClient = (empty(trim($this->config['tinyPNGApiKey'])) ) ? false : new Tinify\Client
        ($this->config['tinyPNGApiKey']);
        $this->tinyPNGCrazyClient = new Tinify\CrazyClient();
        if ($this->tinyPNGClient == false){
            $this->config['tinyPNGApiKey'] == 'crazy';
        }
        \Tinify\setKey($this->config['tinyPNGApiKey']);
        return true;
    }

    function createILovePDF()
    {
        require_once $this->config['modelPath'] . '/tinycompressor/lib/ilovepdf/init_ilovepdf.php';
        $this->iLovePDF = (empty(trim($this->config['iLovePDFProjectID'])) || empty(trim($this->config['iLovePDFProjectKey'])) ) ? false : new Ilovepdf\Ilovepdf($this->config['iLovePDFProjectID'], $this->config['iLovePDFProjectKey']);

        return true;

    }

}