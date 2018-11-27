<?

namespace admin\assets;

use Yii;
use Symfony\Component\Yaml\Yaml;

class FontAwesomeAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $css = [
        'css/font-awesome.min.css',
    ];    
    
    public function iconsFromYaml()
    {
        $arr = Yaml::parseFile($this->sourcePath. '/src/icons.yml');
        return isset($arr['icons'])?$arr['icons']:[];       
    }  

}
