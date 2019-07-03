<?php
return array(
    'disabled' => (isset($_COOKIE['BSK_API'])? false : true),
    'uploadURL' => "../media/upload/".md5(base64_encode($_COOKIE['BSK_API'])),
    'uploadDir' => "",
    'theme' => "default",

    'types' => array(
        'files'   =>  "pdf txt zip rar ppt pptx doc xls xlsx docx bz2 tgz",
        'flash'   =>  "swf fla",
        'images'  =>  "*img",
        'file'    =>  "pdf txt zip rar ppt pptx doc xls xlsx docx bz2 tgz",
        'media'   =>  "flv avi mpg mpeg qt wmv wav mp3 mid midi mkv mov mp4",
        'image'   =>  "*img",
    ),
    'imageDriversPriority' => "imagick gmagick gd",
    'jpegQuality' => 90,
    'thumbsDir' => ".thumbs",
    'maxImageWidth' => 0,
    'maxImageHeight' => 0,
    'thumbWidth' => 100,
    'thumbHeight' => 100,
    'watermark' => "",
    'denyZipDownload' => false,
    'denyUpdateCheck' => false,
    'denyExtensionRename' => false,

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(

        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy'   => true,
            'move'   => true,
            'rename' => true
        ),

        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    'deniedExts' => "exe com msi bat cgi pl php phps phtml php3 php4 php5 php6 py pyc pyo pcgi pcgi3 pcgi4 pcgi5 pchi6",

    'filenameChangeChars' => array(
        ' ' => "_",
        ':' => "."
    ),

    'dirnameChangeChars' => array(
        ' ' => "_",
        ':' => "."
    ),

    'mime_magic' => "",

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',

    '_sessionVar' => "KCFINDER",
    '_check4htaccess' => true,
    '_normalizeFilenames' => false,
    '_dropUploadMaxFilesize' => 50485760,
    '_tinyMCEPath' => "/tiny_mce",
);
