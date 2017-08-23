<?php

/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX|xPDO $modx */
if ($transport->xpdo) {

    $modx =& $transport->xpdo;
    $phpThumbPath = $modx->getOption('core_path') . 'model/phpthumb/phpthumb.class.php';
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:

            $phpThFile = file_get_contents($phpThumbPath);

            $phpThFile = str_replace(
                'if (file_put_contents($renderfilename, $this->outputImageData)) {',
                'if (file_put_contents($renderfilename, $this->outputImageData)) {
                $this->modx->invokeEvent(\'OnPhpThumbRenderToFile\', array(\'filename\' => $renderfilename));',
                $phpThFile);

            file_put_contents($phpThumbPath, $phpThFile);

            /** @var modEvent $Event */
            $Event = $modx->newObject('modEvent');
            $Event->set('name', 'OnPhpThumbRenderToFile');
            $Event->set('service',1);
            $Event->set('groupname', 'phpThumb');
            $Event->save();

            break;

        case xPDOTransport::ACTION_UPGRADE:

            break;

        case xPDOTransport::ACTION_UNINSTALL:

            $phpThFile = file_get_contents($phpThumbPath);
            $phpThFile = str_replace(
                '$this->modx->invokeEvent(\'OnPhpThumbRenderToFile\', array(\'filename\' => $renderfilename));',
                '',
                $phpThFile);
            file_put_contents($phpThumbPath, $phpThFile);

            /** @var modEvent $Event */
            $Event = $modx->getObject('modEvent', 'OnPhpThumbRenderToFile');
            $Event->remove();

            break;
    }
}

return true;

?>