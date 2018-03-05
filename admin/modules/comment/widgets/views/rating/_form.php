<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?

$form = ActiveForm::begin([
            'options' => [
                'id' => $formId,
            ],
            'action' => Url::to(['/rating/create', 'entity' => $encryptedEntity]),
            'validateOnChange' => false,
            'validateOnBlur' => false,
        ]);
?>

<? $form->end(); ?>

