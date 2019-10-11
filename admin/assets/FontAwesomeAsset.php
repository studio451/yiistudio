<?

namespace admin\assets;

use Yii;
use Symfony\Component\Yaml\Yaml;

class FontAwesomeAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $css = [
        'css/all.min.css'
    ];    
    
    public function iconsFromYaml()
    {
        $arr = Yaml::parse(file_get_contents($this->sourcePath. '/metadata/categories.yml'));
        return $arr;       
    }  

}
