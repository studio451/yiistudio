<?

namespace admin\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\Response;
use admin\helpers\Image;
use admin\models\Photo;
use admin\behaviors\SortableController;

class PhotosController extends \admin\components\Controller {

    public function behaviors() {
        return [
                [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
                [
                'class' => SortableController::className(),
                'model' => Photo::className(),
            ]
        ];
    }

    public function actionUpload($class, $item_id, $save_model = false) {
        $success = null;

        $photo = new Photo;
        $photo->class = $class;
        $photo->item_id = $item_id;
        $photo->image = UploadedFile::getInstance($photo, 'image');

        $item = $class::findOne(['id' => $item_id]);

        if ($photo->image && $photo->validate(['image'])) {
            if ($item->attributes['slug']) {
                $photo->image = Image::upload($photo->image, 'photos', Photo::PHOTO_MAX_WIDTH, null, false, $item->slug . '_' . substr(uniqid(md5(rand()), true), 0, 10));
            } else {
                $photo->image = Image::upload($photo->image, 'photos', Photo::PHOTO_MAX_WIDTH);
            }

            if (empty($photo->description)) {
                if ($item->attributes['title']) {
                    $photo->description = $item->title;
                }
            }
            if ($photo->image) {
                if ($photo->save()) {
                    if ($save_model) {
                        $item->save();
                    }
                    $success = [
                        'message' => Yii::t('admin', 'Фото загружено'),
                        'photo' => [
                            'id' => $photo->primaryKey,
                            'image' => $photo->image,
                            'thumb' => Image::thumb($photo->image, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT),
                            'description' => $photo->description,
                        ]
                    ];
                } else {
                    @unlink(Yii::getAlias('@webroot') . str_replace(Url::base(true), '', $photo->image));
                    $this->error = Yii::t('admin', 'Ошибка. {0}', $photo->formatErrors());
                }
            } else {
                $this->error = Yii::t('admin', 'Ошибка загрузки изображения. Размер изображения по большей стороне должен быть не менее ' . Image::IMAGE_MIN_DIMENSION . ' пикселей. Формат изображения должен быть: jpeg, png.  Проверьте права на запись папки uploads');
            }
        } else {
            $this->error = Yii::t('admin', 'Неизвестный формат файла!');
        }

        return $this->formatResponse($success);
    }

    public function actionDescription($id) {
        if (($model = Photo::findOne($id))) {
            if (Yii::$app->request->post('description')) {
                $model->description = Yii::$app->request->post('description');
                if (!$model->update()) {
                    $this->error = Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors());
                }
            } else {
                $this->error = Yii::t('admin', 'Неверный запрос');
            }
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }

        return $this->formatResponse(Yii::t('admin', 'Описание фотографии сохранено'));
    }

    public function actionImage($id, $class, $item_id, $save_model = false) {
        $success = null;

        if (($photo = Photo::findOne($id))) {
            $oldImage = $photo->image;

            $photo->image = UploadedFile::getInstance($photo, 'image');

            if ($photo->image && $photo->validate(['image'])) {
                $photo->image = Image::upload($photo->image, 'photos', Photo::PHOTO_MAX_WIDTH);
                if ($photo->image) {
                    if ($photo->save()) {
                        @unlink(Yii::getAlias('@webroot') . $oldImage);

                        $success = [
                            'message' => Yii::t('admin', 'Фотография загружена'),
                            'photo' => [
                                'image' => $photo->image,
                                'thumb' => Image::thumb($photo->image, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT)
                            ]
                        ];
                    } else {
                        @unlink(Yii::getAlias('@webroot') . $photo->image);

                        $this->error = Yii::t('admin', 'Ошибка при обновлении записи. {0}', $photo->formatErrors());
                    }
                } else {
                    $this->error = Yii::t('admin', 'Ошибка загрузки изображения. Размер изображения по большей стороне должен быть не менее ' . Image::IMAGE_MIN_DIMENSION . ' пикселей. Формат изображения должен быть: jpeg, png.  Проверьте права на запись папки uploads');
                }
            } else {
                $this->error = Yii::t('admin', 'Некорректный файл');
            }
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }

        return $this->formatResponse($success);
    }

    public function actionDelete($id, $class, $item_id, $save_model = false) {
        if (($model = Photo::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        if ($save_model) {
            $item = $class::findOne(['id' => $item_id]);
            $item->save();
        }
        return $this->formatResponse(Yii::t('admin', 'Фотография удалена'));
    }

    public function actionUp($id, $class, $item_id, $save_model = false) {

        $response = $this->move($id, 'up', ['class' => $class, 'item_id' => $item_id]);
        if ($save_model) {
            $item = $class::findOne(['id' => $item_id]);
            $item->save();
        }
        return $response;
    }

    public function actionDown($id, $class, $item_id, $save_model = false) {

        $response = $this->move($id, 'down', ['class' => $class, 'item_id' => $item_id]);
        if ($save_model) {
            $item = $class::findOne(['id' => $item_id]);
            $item->save();
        }
        return $response;
    }

}
