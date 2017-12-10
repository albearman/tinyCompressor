<?php

$_lang['area_tinycompressor_TinyPNG'] = 'Настройки сервиса TinyPNG';

$_lang['setting_tinycompressor_tinypng_upload_enable'] = 'Сжимать загружаемые изображения';
$_lang['setting_tinycompressor_tinypng_upload_enable_desc'] = 'Компонент будет отправлять на сжатие все загружаемые изображения.';

$_lang['setting_tinycompressor_tinypng_thumb_enable'] = 'Сжимать превью изображения';
$_lang['setting_tinycompressor_tinypng_thumb_enable_desc'] = '<strong>Данная функция работает в эксперементальном режиме.</strong><br /> Компонент будет отправлять на сжатие созданные превью. <br /> Необходимо проверить наличие строки компонента в файле (/core/model/phpthumb/phpthumb.class.php) "$this->modx->invokeEvent(\'OnPhpThumbRenderToFile\', array(\'filename\' => $renderfilename));" при её отсутвиии необходимо добавить её в функцию "RenderToFile" (строка 599) перед строкой "@chmod($renderfilename, $this->getParameter(\'config_file_create_mask\'));". <br /> К сожалению, разработчики MODX, не добавили иной возможности обработки события.';

$_lang['setting_tinycompressor_tinypng_api_key'] = 'Ключ к API TinyPNG';
$_lang['setting_tinycompressor_tinypng_api_key_desc'] = 'Ключ для сжатия файлов размером более 5 МБ. Получить можно по ссылке: <a href="https://tinypng.com/developers" target="_blank">tinypng.com</a>.';

$_lang['area_tinycompressor_TinyPNG'] = 'Настройки сервиса ILovePDF';

$_lang['setting_tinycompressor_ilovepdf_upload_enable'] = 'Сжимать загружаемые PDF-файлы';
$_lang['setting_tinycompressor_ilovepdf_upload_enable_desc'] = 'Компонент будет отправлять на сжатие все загружаемые PDF-файлы. Только при наличии project_id и project_key.';

$_lang['setting_tinycompressor_ilovepdf_project_id'] = 'ID проекта в ILovePDF';
$_lang['setting_tinycompressor_ilovepdf_project_id_desc'] = 'ID проекта для сжатия pdf. Получить можно по ссылке: <a href="https://developer.ilovepdf.com/signup" target="_blank">ilovepdf.com</a>.';

$_lang['setting_tinycompressor_ilovepdf_project_key'] = 'Секретный ключ в ILovePDF';
$_lang['setting_tinycompressor_ilovepdf_project_key_desc'] = 'Секретный ключ проекта для сжатия pdf. Получить можно по ссылке: <a href="https://developer.ilovepdf.com/signup" target="_blank">ilovepdf.com</a>.';

$_lang['setting_tinycompressor_ilovepdf_compression_level'] = 'Уровень сжатия PDF-файлов';
$_lang['setting_tinycompressor_ilovepdf_compression_level_desc'] = 'Уровень сжатия pdf-файлов.<br />Доступные значения: low, recommended, extreme';