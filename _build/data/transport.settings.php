<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
    'tinypng_upload_enable' => array(
        'xtype' => 'combo-boolean',
        'value' => 0,
        'name' => 'Сжимать загружаемые изображения',
        'description' => 'Компонент будет отправлять на сжатие все загружаемые изображения.',
        'area' => 'TinyPNG',
    ),
    'tinypng_thumb_enable' => array(
        'xtype' => 'combo-boolean',
        'value' => 1,
        'name' => 'Сжимать превью изображения',
        'description' => '<strong>Данная функция работает в эксперементальном режиме.</strong><br /> Компонент будет отправлять на сжатие созданные 
превью. <br /> Необходимо проверить наличие строки компонента в файле (/core/model/phpthumb/phpthumb.class.php) 
"$this->modx->invokeEvent(\'OnPhpThumbRenderToFile\', array(\'filename\' => $renderfilename));" при её отсутвиии необходимо добавить её в функцию "RenderToFile" (строка 599) перед строкой "@chmod($renderfilename, $this->getParameter(\'config_file_create_mask\'));". <br /> К сожалению, разработчики MODX, не добавили иной возможности обработки события.',
        'area' => 'TinyPNG',
    ),
    'tinypng_api_key' => array(
        'xtype' => 'textfield',
        'value' => '',
        'name' => 'Ключ к API TinyPNG',
        'description' => 'Ключ для сжатия файлов размером более 5 МБ. Получить можно по ссылке: <a href="https://tinypng.com/developers" target="_blank">tinypng.com</a>',
        'area' => 'TinyPNG',
    ),
    'ilovepdf_upload_enable' => array(
        'xtype' => 'combo-boolean',
        'value' => 0,
        'name' => 'Сжимать загружаемые PDF-файлы',
        'description' => 'Компонент будет отправлять на сжатие все загружаемые PDF-файлы',
        'area' => 'ILovePDF',
    ),
    'ilovepdf_project_id' => array(
        'xtype' => 'textfield',
        'value' => '',
        'name' => 'ID проекта в ILovePDF',
        'description' => 'ID проекта для сжатия pdf. Получить можно по ссылке: <a href="https://developer.ilovepdf.com/signup" target="_blank">ilovepdf.com</a>',
        'area' => 'ILovePDF',
    ),
    'ilovepdf_project_key' => array(
        'xtype' => 'textfield',
        'value' => '',
        'name' => 'Секретный ключ в ILovePDF',
        'description' => 'Секретный ключ проекта для сжатия pdf. Получить можно по ссылке: <a href="https://developer.ilovepdf.com/signup" target="_blank">ilovepdf.com</a>',
        'area' => 'ILovePDF',
    ),
    'ilovepdf_compression_level' => array(
        'xtype' => 'textfield',
        'value' => 'recommended',
        'name' => 'Уровень сжатия PDF-файла',
        'description' => 'Уровень сжатия pdf-файлов. Доступные значения: low, recommended, extreme',
        'area' => 'ILovePDF',
    ),
);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => 'tinycompressor_' . $k,
            'namespace' => PKG_NAME_LOWER,
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
