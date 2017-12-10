<?php

//Helpers
require_once __DIR__ . '/src/JWT.php';
require_once __DIR__ . '/src/File.php';
require_once __DIR__ . '/src/Method.php';
require_once __DIR__ . '/src/Response.php';
require_once __DIR__ . '/src/Request.php';
require_once __DIR__ . '/src/Request/Body.php';

//Exceptions
require_once __DIR__ . '/src/Exceptions/ExtendedException.php';
require_once __DIR__ . '/src/Exceptions/DownloadException.php';
require_once __DIR__ . '/src/Exceptions/ProcessException.php';
require_once __DIR__ . '/src/Exceptions/UploadException.php';
require_once __DIR__ . '/src/Exceptions/StartException.php';
require_once __DIR__ . '/src/Exceptions/AuthException.php';
require_once __DIR__ . '/src/Exceptions/PathException.php';

//Ilovepdf
require_once __DIR__ . '/src/Ilovepdf.php';
require_once __DIR__ . '/src/Task.php';

//Specific processes
require_once __DIR__ . '/src/CompressTask.php';