<?

namespace admin\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use admin\helpers\GD;

class Image {

    const IMAGE_MIN_DIMENSION = 360;

    public static function upload_from_url($url, $fileName, $dir = '', $resizeWidth = null, $resizeHeight = null, $resizeCrop = false) {
        $tempName = Upload::getUploadPath('tmp') . DIRECTORY_SEPARATOR . $fileName;
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . $fileName;

        if (false) {
            file_put_contents($tempName, file_get_contents($url));
        } else {
            $ch = curl_init($url);
            $fp = fopen($tempName, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }
        if (!file_exists($tempName)) {
            return false;
        }

        $imageData = getimagesize($tempName);

        switch (image_type_to_mime_type($imageData[2])) {
            case 'image/jpeg':
                $ext = '.jpg';
                break;
            case 'image/png':
                $ext = '.png';
                break;
            default :
                return false;
        }

        if ($imageData[0] < self::IMAGE_MIN_DIMENSION && $imageData[1] < self::IMAGE_MIN_DIMENSION) {
            return false;
        }
        $fileName = $fileName . $ext;
        if ($resizeWidth) {
            self::copyResizedImage($tempName, $fileName, $resizeWidth, $resizeHeight, $resizeCrop);
        } else {
            move_uploaded_file($tempName, $fileName);
        }

        return Upload::getLink($fileName);
    }

    public static function upload(UploadedFile $fileInstance, $dir = '', $resizeWidth = null, $resizeHeight = null, $resizeCrop = false, $fileName = '') {
        if ($fileName != '') {
            $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . $fileName;
        } else {
            $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance, true, false);
        }

        if (!file_exists($fileInstance->tempName)) {
            return false;
        }
        $imageData = getimagesize($fileInstance->tempName);

        switch (image_type_to_mime_type($imageData[2])) {
            case 'image/jpeg':
                $ext = '.jpg';
                break;
            case 'image/png':
                $ext = '.png';
                break;
            default :
                return false;
        }

        if ($imageData[0] < self::IMAGE_MIN_DIMENSION && $imageData[1] < self::IMAGE_MIN_DIMENSION) {
            return false;
        }
        $fileName = $fileName . $ext;
        $uploaded = $resizeWidth ? self::copyResizedImage($fileInstance->tempName, $fileName, $resizeWidth, $resizeHeight, $resizeCrop) : $fileInstance->saveAs($fileName);

        if (!$uploaded) {
            throw new HttpException(500, 'Cannot upload file "' . $fileName . '". Please check write permissions.');
        }

        return Upload::getLink($fileName);
    }

    static function thumb($filename, $width = null, $height = null, $crop = true) {
        if ($filename && file_exists(($filename = Yii::getAlias('@webroot') . $filename))) {
            $info = pathinfo($filename);
            $thumbName = $info['filename'] . '-' . md5(filemtime($filename) . (int) $width . (int) $height . (int) $crop) . '.' . $info['extension'];
            $thumbFile = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . $thumbName;
            $thumbWebFile = '/' . Upload::$UPLOADS_DIR . '/thumbs/' . $thumbName;
            if (file_exists($thumbFile)) {
                return $thumbWebFile;
            } elseif (FileHelper::createDirectory(dirname($thumbFile), 0777) && self::copyResizedImage($filename, $thumbFile, $width, $height, $crop)) {
                return $thumbWebFile;
            }
        }
        return '';
    }

    static function copyResizedImage($inputFile, $outputFile, $width, $height = null, $crop = true) {
        if (extension_loaded('gd')) {
            $image = new GD($inputFile);

            if ($height) {
                if ($width && $crop) {
                    $image->cropThumbnail($width, $height);
                } else {
                    $image->resize($width, $height);
                }
            } else {
                $image->resize($width);                
            }
            return $image->save($outputFile);
        } elseif (extension_loaded('imagick')) {
            $image = new \Imagick($inputFile);

            if ($height && !$crop) {
                $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
            } else {
                $image->resizeImage($width, null, \Imagick::FILTER_LANCZOS, 1);
            }

            if ($height && $crop) {
                $image->cropThumbnailImage($width, $height);
            }

            return $image->writeImage($outputFile);
        } else {
            throw new HttpException(500, 'Please install GD or Imagick extension');
        }
    }

}
