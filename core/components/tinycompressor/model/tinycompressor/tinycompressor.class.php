<?php
/**
 * The base class for tinycompressor.
 *
 * @package tinycompressor
 */

class tinycompressor
{
    /** @var modX $modx */
    public $modx;

    /** @var  \Tinify\Client $TinifyClient */
    private $TinifyClient = null;


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

        $api_key = $this->modx->getOption('tinycompressor_api_key', $config,
            'crazy'
        );

        $this->config = array_merge(array(
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'api_key' => $api_key
        ), $config);

        $this->modx->addPackage('tinycompressor', $this->config['modelPath']);
        $this->modx->lexicon->load('tinycompressor:default');

    }

    function connector()
    {
        if ( is_null($this->TinifyClient) ) {
            require_once($this->config['modelPath'] . 'tinycompressor/lib/Tinify/Exception.php');
            require_once($this->config['modelPath'] . 'tinycompressor/lib/Tinify/ResultMeta.php');
            require_once($this->config['modelPath'] . 'tinycompressor/lib/Tinify/Result.php');
            require_once($this->config['modelPath'] . 'tinycompressor/lib/Tinify/Source.php');
            require_once($this->config['modelPath'] . 'tinycompressor/lib/Tinify/Client.php');
            require_once($this->config['modelPath'] . 'tinycompressor/lib/Tinify.php');

            $mod = ($this->config['api_key'] == 'crazy') ? 'crazy' : 'api';

            $this->TinifyClient = new Tinify\Client($this->config['api_key'], null, null, $mod);
            \Tinify\setKey($this->config['api_key']);
            \Tinify\Tinify::setClient($this->TinifyClient);
        }
        return true;
    }


    /**
     * @param $path Path to file
     * @param string $type file|buffer|url
     */
    private function compressImage($path, $type = 'file')
    {
        $this->connector();

        switch ($type) {
            case 'file':
                $source = \Tinify\fromFile($path);
                $source->toFile($path);
                break;
            case 'buffer':
                break;
            case 'url':
                break;
        }

    }

    /**
     * @param array $objects
     */
    function compress($objects, $directory = '')
    {
        $this->connector();
        foreach ($objects as $file) {
            $limit_size = ($this->config['api_key'] == 'crazy') ? true : false;

            if (
                ($limit_size === true && $file['size'] < '5242880')
                ||  $limit_size === false
            ) {
                $allow_types = array(
                    'image/jpeg','image/png'
                );
                if ( in_array($file['type'],$allow_types) ) {
                    $path =
                        $this->modx->getOption('base_path')
                        . $directory
                        . $file['name'];
                    $this->compressImage($path);
                }

            }
        }
    }


}