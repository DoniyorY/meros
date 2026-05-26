<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\Inflector;

class GiiGeneratorController extends Controller
{
    //Ввод название таблиц в массив
    /*public function actionIndex()
    {
        $models = ['user', 'bank', 'credit_application']; //Названия таблиц
        // Генерация Models
        foreach ($models as $model) {
            $camelCase = Inflector::camelize($model);
            $this->stdout("Генерация модели: $camelCase...\n", BaseConsole::FG_GREEN);

            $result =
                \Yii::$app->runAction('gii/model', [
                    'tableName' => $model,
                    'modelClass' => $camelCase,
                    'ns' => 'common\\models',
                    'generateRelations' => 'all',
                    'interactive' => false,
                    'baseClass'=>'yii\db\ActiveRecord',
                ]);

            if ($result === ExitCode::OK) {
                $this->stdout("Модель $camelCase успешно создана!\n", BaseConsole::FG_GREEN);
            } else {
                $this->stderr("Ошибка при генерации модели $camelCase.\n", BaseConsole::FG_RED);
                return 0;
            }
        }

        // Генерация SearchModel
        foreach ($models as $model) {
            $camelCase = Inflector::camelize($model);
            $this->stdout("Генерация SearchModel: {$camelCase}Search...\n", BaseConsole::FG_GREEN);
            \Yii::$app->runAction('gii/model', [
                'tableName' => $model,
                'modelClass' => $camelCase . 'Search',
                'ns' => 'common\\models\\search',
                'baseClass' => 'yii\\base\\Model',
                'interactive' => false
            ]);
        }

        // Генерация контроллеров
        foreach ($models as $model) {
            $camelCase = Inflector::camelize($model);
            $kebabCase = Inflector::camel2id($camelCase, '-');
            $this->stdout("Генерация контроллера: {$camelCase}Controller...\n", BaseConsole::FG_GREEN);
            \Yii::$app->runAction('gii/crud', [
                'modelClass' => "common\\models\\$camelCase",
                'searchModelClass' => "common\\models\\search\\{$camelCase}Search",
                'controllerClass' => "app\\controllers\\{$camelCase}Controller",
                'viewPath' => "@app/views/".$kebabCase,
                'interactive' => false
            ]);
        }

        return ExitCode::OK;
    }*/

    //Ручной ввод название таблиц через консоль
    public function actionIndex()
    {
        //Ввод название таблиц
        $this->stdout("Введите названия таблиц через запятую: ", Console::FG_YELLOW);
        $input = trim(fgets(STDIN));
        $tableNames = array_map('trim', explode(',', $input));
        $db = \Yii::$app->db;
        $existingTables = $db->schema->tableNames;
        $models = [];

        // Проверка таблиц на существование
        foreach ($tableNames as $tableName) {
            if (!in_array($tableName, $existingTables)) {
                $this->stderr("Ошибка: Таблица '$tableName' не существует в базе данных.\n", Console::FG_RED);
                continue;
            }
            $models[$tableName] = Inflector::camelize($tableName);
        }

        if (empty($models)) {
            $this->stderr("Нет доступных таблиц для генерации.\n", Console::FG_RED);
            return ExitCode::DATAERR;
        }

        //Генерация Models
        foreach ($models as $tableName => $model) {
            $this->stdout("Генерация модели: $model (таблица: $tableName)...\n", Console::FG_GREEN);

            $result = \Yii::$app->runAction('gii/model', [
                'tableName' => $tableName,
                'modelClass' => $model,
                'ns' => 'common\\models',
                'generateRelations' => 'all',
                'interactive' => false,
                'baseClass'=>'yii\\db\\ActiveRecord',
            ]);

            if ($result === ExitCode::OK) {
                $this->stdout("Модель $model успешно создана!\n", Console::FG_GREEN);
            } else {
                $this->stderr("Ошибка при генерации модели $model.\n", Console::FG_RED);
                return  ExitCode::UNSPECIFIED_ERROR;
            }
        }

        // Генерация SearchModel
        foreach ($models as $tableName => $model) {
            $searchModelClass = $model . 'Search';
            $this->stdout("Генерация SearchModel: $searchModelClass...\n", Console::FG_GREEN);
            \Yii::$app->runAction('gii/model', [
                'tableName' => $tableName,
                'modelClass' => $searchModelClass,
                'ns' => 'common\\models\\search',
                'baseClass' => 'yii\\base\\Model',
                'interactive' => false
            ]);
        }

        // Генерация контроллеров
        foreach ($models as $tableName => $model) {
            $controllerClass = "backend\\controllers\\{$model}Controller";
            $viewPath = "@app/views/" . Inflector::camel2id($model, '-');
            $this->stdout("Генерация контроллера: $controllerClass...\n", Console::FG_GREEN);
            \Yii::$app->runAction('gii/crud', [
                'modelClass' => "common\\models\\$model",
                'searchModelClass' => "common\\models\\search\\{$model}Search",
                'controllerClass' => $controllerClass,
                'viewPath' => $viewPath,
                'interactive' => false,
                'baseControllerClass' => "yii\\web\\Controller",
            ]);
        }

        return ExitCode::OK;
    }
}