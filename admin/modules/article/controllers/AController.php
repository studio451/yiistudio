<?
namespace admin\modules\article\controllers;

use admin\components\CategoryController;

class AController extends CategoryController
{
    public $categoryClass = 'admin\modules\article\models\Category';
    public $moduleName = 'article';

}