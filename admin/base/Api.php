<?
namespace admin\base;

use Yii;

/**
 * Base Api component. Used by all modules
 * @package admin\base
 */
class Api extends \yii\base\BaseObject
{
    /** @var  array */
    static $classes;
    /** @var  string module name */
    public $module;

    public function init()
    {
        parent::init();
        $this->module = \admin\base\Module::getModuleName(self::className());
    }

    public static function __callStatic($method, $params)
    {
        $name = (new \ReflectionClass(self::className()))->getShortName();
        if (!isset(self::$classes[$name])) {
            self::$classes[$name] = new static();
        }
        return call_user_func_array([self::$classes[$name], 'api_' . $method], $params);
    }

    /**
     * Wrap text with liveEdit tags, which later will fetched by jquery widget
     * @param $text
     * @param $path
     * @param string $tag
     * @return string
     */
    public static  function liveEdit($text, $path, $tag = 'span')
    {
        return $text ? '<'.$tag.' class="cms-edit" data-edit="'.$path.'">'.$text.'</'.$tag.'>' : '';
    }
}
