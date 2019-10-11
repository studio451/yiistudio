Базовые (родительские) классы Yii Studio
=====================

    admin/
        base/                   базовые (родительские) классы Yii Studio (Controller, Module, ActiveRecord, ...) 
            admin/              базовые (родительские) классы Yii Studio, для использования только в Панели управления
                CategoryController  `admin\base\admin\CategoryController`
                Controller          `admin\base\admin\Controller`
            api/                базовые (родительские) классы Yii Studio, для использования в приложениях
                Controller          `admin\base\api\Controller`
        ActiveQuery         `admin\base\ActiveQuery`
        ActiveQueryNS       `admin\base\ActiveQueryNS`
        ActiveRecord        `admin\base\ActiveRecord`
        ActiveRecordData    `admin\base\ActiveRecordData`
        Api                 `admin\base\Api`
        ApiObject           `admin\base\ApiObject`
        Asset               `admin\base\Asset`              Автоматически подключает main.js для текущего роута действия
        CategoryModel       `admin\base\CategoryModel`
        Controller          `admin\base\CategoryModel`      Проверяет установлена ли система, предоставляет методы для установки ReturnUrl
        ModerationQuery     `admin\base\ModerationQuery`
        Module              `admin\base\Module`             Наследуются все модули системы
