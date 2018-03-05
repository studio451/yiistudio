<?
namespace admin\behaviors;

use Yii;

/**
 * Status behavior. Adds statuses to models
 * @package admin\behaviors
 */
class StatusController extends \yii\base\Behavior
{
    public $model;

    public function changeStatus($id, $status)
    {
        $modelClass = $this->model;

        if(($model = $modelClass::findOne($id))){
            $model->status = $status;
            $model->update();
        }
        else{
            $this->error = Yii::t('admin', 'Запись не найдена');
        }

        return $this->owner->formatResponse(Yii::t('admin', 'Статус изменен'));
    }
}