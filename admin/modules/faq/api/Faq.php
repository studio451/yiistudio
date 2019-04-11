<?
namespace admin\modules\faq\api;

use Yii;
use admin\helpers\Data;
use admin\modules\faq\models\Faq as FaqModel;


/**
 * FAQ module Api
 * @package admin\modules\faq\api
 *
 * @method static array items() list of all FAQ as FaqObject objects
 */

class Faq extends \admin\base\Api
{
    public function api_items()
    {
        return Data::cache(FaqModel::CACHE_KEY, 3600, function(){
            $items = [];
            foreach(FaqModel::find()->select(['id', 'question', 'answer'])->status(FaqModel::STATUS_ON)->sort()->all() as $item){
                $items[] = new FaqObject($item);
            }
            return $items;
        });
    }
}