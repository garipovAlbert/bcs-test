<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\AccessTokenResource;
use api\modules\v1\services\AccessTokenCrudService;
use common\models\AccessToken;
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
 * Контроллер для токена доступа (bearer).
 */
class AccessTokenController extends Controller
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
                    'delete' => ['DELETE'],
                ],
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'only' => ['get', 'get-list', 'delete'],
            ],
        ]);
    }

    /**
     * Возвращает ресурс с указанным ID.
     *
     * @param $id
     * @param AccessTokenCrudService $service
     * @return AccessTokenResource
     * @throws NotFoundHttpException
     */
    public function actionGet($id, AccessTokenCrudService $service): AccessTokenResource
    {
        $token = $this->find($id);

        return $service->get($token);
    }

    /**
     * Возвращает коллекцию.
     *
     * @param AccessTokenCrudService $service
     * @return AccessTokenResource[]
     * @throws \Throwable
     */
    public function actionGetList(AccessTokenCrudService $service): array
    {
        /** @var User $currentUser */
        $currentUser = \Yii::$app->user->getIdentity();

        return $service->getList($currentUser);
    }

    /**
     * Создает ресурс.
     *
     * @param AccessTokenCrudService $service
     * @return AccessTokenResource
     * @throws \yii\base\Exception
     */
    public function actionPost(AccessTokenCrudService $service): AccessTokenResource
    {
        // Создаем объект ресурса и загружаем в него данные из запроса
        $tokenResource = new AccessTokenResource;
        $tokenResource->load($this->request->bodyParams, '');

        if ($tokenResource->validate()) {
            // Создаем запись в БД
            $token = $service->create($tokenResource);

            $this->response->setStatusCode(201);
            $this->response->getHeaders()
                ->set('Location', Url::toRoute(['get', 'id' => $token->id], true));

            // Возвращаем ресурс для созданной записи
            return $service->get($token);
        }

        // Возвращаем ресурс с ошибками валидации
        return $tokenResource;
    }

    /**
     * Удаляет ресурс с указанным ID.
     *
     * @param $id
     * @param AccessTokenCrudService $service
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, AccessTokenCrudService $service): void
    {
        $token = $this->find($id);

        $service->delete($token);

        \Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * @param $id
     * @return AccessToken
     * @throws NotFoundHttpException
     */
    private function find($id): AccessToken
    {
        /** @var AccessToken $token */
        $token = AccessToken::find()
            ->andWhere([
                'id' => $id,
                'user_id' => \Yii::$app->user->id,
            ])
            ->one();
        if ($token === null) {
            throw new NotFoundHttpException('Token not found.');
        }

        return $token;
    }

}