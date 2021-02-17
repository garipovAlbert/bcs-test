<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\UserResource;
use api\modules\v1\services\UserCrudService;
use common\models\User;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер для управления ресурсом Пользователь.
 */
class UserController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'text/html' => Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'get' => ['GET'],
                    'get-list' => ['GET'],
                    'post' => ['POST'],
                    'put' => ['PUT'],
                    'delete' => ['DELETE'],
                ],
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::class,
            ],
        ]);
    }

    /**
     * Возвращает ресурс с указанным ID.
     *
     * @param $id
     * @param UserCrudService $service
     * @return UserResource
     * @throws NotFoundHttpException
     */
    public function actionGet($id, UserCrudService $service): UserResource
    {
        $user = $this->find($id);

        return $service->get($user);
    }

    /**
     * Возвращает коллекцию.
     *
     * @param UserCrudService $service
     * @return UserResource[]
     * @throws \Throwable
     */
    public function actionGetList(UserCrudService $service): array
    {
        return $service->getList();
    }

    /**
     * Создает ресурс.
     *
     * @param UserCrudService $service
     * @return UserResource
     */
    public function actionPost(UserCrudService $service): UserResource
    {
        // Создаем объект ресурса и загружаем в него данные из запроса
        $userResource = new UserResource;
        $userResource->scenario = UserResource::SCENARIO_CREATE;
        $userResource->load($this->request->bodyParams, '');

        if ($userResource->validate()) {
            // Создаем запись в БД
            $user = $service->create($userResource);

            $this->response->setStatusCode(201);
            $this->response->getHeaders()
                ->set('Location', Url::toRoute(['get', 'id' => $user->id], true));

            // Возвращаем ресурс для созданной записи
            return $service->get($user);
        }

        // Возвращаем ресурс с ошибками валидации
        return $userResource;
    }

    /**
     * Обновляет ресурс.
     *
     * @param $id
     * @param UserCrudService $service
     * @return UserResource
     * @throws NotFoundHttpException
     */
    public function actionPut($id, UserCrudService $service): UserResource
    {
        $user = $this->find($id);

        // Создаем ресурс с предварительно загруженными в него текущими данными БД
        $userResource = $service->get($user);
        $userResource->scenario = UserResource::SCENARIO_UPDATE;
        // Загружаем в ресурс данные из запроса
        $userResource->load($this->request->bodyParams, '');

        if ($userResource->validate()) {
            // Сохраняем запись в БД
            $service->update($user, $userResource);

            // Возвращаем ресурс для созданной записи
            return $service->get($user);
        }

        // Возвращаем ресурс с ошибками валидации
        return $userResource;
    }

    /**
     * Удаляет ресурс с указанным ID.
     *
     * @param $id
     * @param UserCrudService $service
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDelete($id, UserCrudService $service): void
    {
        $user = $this->find($id);

        $service->delete($user);

        \Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * @param $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function find($id): User
    {
        /** @var User $user */
        $user = User::find()
            ->andWhere([
                'id' => $id,
                'status' => User::STATUS_ACTIVE,
            ])
            ->one();
        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }

}