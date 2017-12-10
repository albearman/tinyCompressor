<?php

$_lang['area_tinycompressor_TinyPNG'] = 'TinyPNG service settings';

$_lang['setting_tinycompressor_tinypng_upload_enable'] = 'Compress uploaded images';
$_lang['setting_tinycompressor_tinypng_upload_enable_desc'] = 'The component will be sent to the compression of all uploaded image.';

$_lang['setting_tinycompressor_tinypng_thumb_enable'] = 'Compress thumbnail images';
$_lang['setting_tinycompressor_tinypng_thumb_enable_desc'] = '<strong>This function works in the experimental mode.</strong><br /> The component will send the previews for compression. <br /> It is necessary to check the presence of the component string in the file (/core/model/phpthumb/phpthumb.class.php) "$this->modx->invokeEvent(\'OnPhpThumbRenderToFile\', array(\'filename\' => $renderfilename));". If it is missing, you must add it to the function "RenderToFile" (line 599) before the line "@chmod($renderfilename, $this->getParameter(\'config_file_create_mask\'));". <br /> Unfortunately, the developers of MODX did not add any other possibility of processing the event.';

$_lang['setting_tinycompressor_tinypng_api_key'] = 'The key to the TinyPNG API';
$_lang['setting_tinycompressor_tinypng_api_key_desc'] = 'The key for compressing files larger than 5 MB. To receive it is possible under the link: <a href="https://tinypng.com/developers" target="_blank">tinypng.com</a>.';

$_lang['area_tinycompressor_TinyPNG'] = 'ILovePDF service settings';

$_lang['setting_tinycompressor_ilovepdf_upload_enable'] = 'Compress uploaded PDFs';
$_lang['setting_tinycompressor_ilovepdf_upload_enable_desc'] = 'The component will send all uploaded PDFs for compression. Only with project_id and project_key.';

$_lang['setting_tinycompressor_ilovepdf_project_id'] = 'Project ID in ILovePDF';
$_lang['setting_tinycompressor_ilovepdf_project_id_desc'] = 'Project ID for pdf compression. To receive it is possible under the link: <a href="https://developer.ilovepdf.com/signup" target="_blank">ilovepdf.com</a>.';

$_lang['setting_tinycompressor_ilovepdf_project_key'] = 'Secret key in ILovePDF';
$_lang['setting_tinycompressor_ilovepdf_project_key_desc'] = 'Secret project key for pdf compression. To receive it is possible under the link: <a href="https://developer.ilovepdf.com/signup" target="_blank">ilovepdf.com</a>.';

$_lang['setting_tinycompressor_ilovepdf_compression_level'] = 'PDF compression level';
$_lang['setting_tinycompressor_ilovepdf_compression_level_desc'] = 'Level of compression of pdf-files.<br />Available Values: low, recommended, extreme';