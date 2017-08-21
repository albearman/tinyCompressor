<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
    'api_key' => array(
        'xtype' => 'textfield',
        'value' => 'crazy',
        'area' => 'Main',
    )
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
