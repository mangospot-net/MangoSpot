Exensions for file downloads

## What is needed
Server when support PHP files


## How to install
Copy DownloadFile.php to anywhere

## How to use

### Easy to use
```php
$downloadFile = new \FS\DownloadFile;
$downloadFile->download('data/file.exe');
```

### Advanced settings
```php
$filePath = 'data/file.exe';
$fileName = 'install.exe';
$contentType = 'application/octet-stream';
$downloadFile = new \FS\DownloadFile;
$downloadFile->speed(10); // 10 kb/s
$downloadFile->download($filePath, $fileName, $contentType);
```

