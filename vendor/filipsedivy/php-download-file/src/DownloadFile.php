<?php

/**
 * Class for download file
 * 
 * @author Filip Šedivý <mail@filipsedivy.cz>
 * @version 1.0.2
 */

namespace FS;

class DownloadFile{

    /**
     * If you want to add a file, you can add it to the list 
     * http://www.freeformatter.com/mime-types-list.html
     */
    private $contentType = array(
						
        /* Archives */
        'zip'       => 'application/zip',
        '7z'        => 'application/x-7z-compressed',

        /* Documents */
        'txt'       => 'text/plain',
        'pdf'       => 'application/pdf',
        'doc'       => 'application/msword',
        'xls'       => 'application/vnd.ms-excel',
        'ppt'       => 'application/vnd.ms-powerpoint',
        'csv'       => 'text/csv',
        'eml'       => 'message/rfc822',

        /* Executables */
        'exe'       => 'application/octet-stream',
        'swf'       => 'application/x-shockwave-flash',
        'torrent'   => 'application/x-bittorrent',
        
        /* Images */
        'gif'       => 'image/gif',
        'png'       => 'image/png',
        'jpg'       => 'image/jpeg',
        'jpeg'      => 'image/jpeg',

        /* Audio */
        'mp3'       => 'audio/mpeg',
        'wav'       => 'audio/x-wav',

        /* Video */
        'mpeg'      => 'video/mpeg',
        'mpg'       => 'video/mpeg',
        'mpe'       => 'video/mpeg',
        'mov'       => 'video/quicktime',
        'avi'       => 'video/x-msvideo',
        
        /* Source code */
        'c'         => 'text/x-c',
        'csh'       => 'application/x-csh',
        'css'       => 'text/css',
        'cml'       => 'chemical/x-cml',
        'html'      => 'text/html'
    );    
    
    
    /**
     * The download speed in kb/s
     */
    private $speed = null;
    
    
    /**
     * Set the download speed
     * 
     * @param double $rate Download speed in kb/s
     */
    public function speed($rate){
        if(is_numeric($rate)){
            $this->speed = $rate;
        }
    }
    
    
    /**
     * Downloading files with automatic detection of the type
     * 
     * @param string $file File name with path
     * @param string $name Set file name
     * @param string $contentType Set content type
     */
    public function download($file, $name = null, $contentType = null){
        if(file_exists($file)){
            $extension = pathinfo($file, PATHINFO_EXTENSION);
           
            if(is_null($name)){
                $name = basename($file);
            }
            
            if(is_null($contentType)){
                if(in_array($extension, $this->contentType)){
                    $contentType = $this->contentType[$extension];
                }else{
                    $contentType = 'application/octet-stream';
                }
            }
             
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $contentType);
            header('Content-Disposition: attachment; filename=' . $name);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            
            // Download speed
            if(is_null($this->speed)){
                readfile($file);
            }else{
                $file = fopen($file, 'r');
                while(!feof($file)){
                    print fread($file, round($this->speed * 1024));
                    flush();
                    sleep(1);
                }
                fclose($file);
            }
            
            exit;
        }else{
            throw new FileNotFound();
        }
    }
}

class FileNotFound extends \Exception{}
