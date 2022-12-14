<?php

namespace App\Http\Controllers;

use App\Exceptions\AbnormalResponseException;
use App\Exceptions\ResultCode;
use App\Http\Responder\ApptSlotResponder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RsvForm\Usecase\Form\AcceptReservation;
use RsvForm\Usecase\Management\ApptSlotCreate;
use RsvForm\Usecase\Management\ApptSlotDelete;
use RsvForm\Usecase\Management\ApptSlotIndex;
use RsvForm\Usecase\Management\ApptSlotUpdate;

class ApptSlotController
{
    /**
     * @var ApptSlotResponder
     */
    private $responder;

    /**
     * コンストラクタ
     *
     * @param  ApptSlotResponder  $responder
     */
    public function __construct(ApptSlotResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * 予約枠一覧の取得
     *
     * @param  ApptSlotIndex  $usecase
     * @param  int|null  $courseId
     * @return JsonResponse
     */
    public function index(ApptSlotIndex $usecase, ?int $courseId = null): JsonResponse
    {
        // throw new AbnormalResponseException(ResultCode::Failed, 'test error');

        try {
            $apptSlots = $usecase->__invoke($courseId);
        } catch (Exception $e) {
            logs()->error($e);

            return $this->responder->error($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responder->withEntityCollection($apptSlots);
    }

    /**
     * 予約枠の新規作成
     *
     * @param  Request  $request
     * @param  ApptSlotCreate  $usecase
     * @return JsonResponse
     */
    public function new(Request $request, ApptSlotCreate $usecase): JsonResponse
    {
        $posts = $request->input();

        try {
            $apptSlot = $usecase->__invoke($posts);
        } catch (Exception $e) {
            logs()->error($e);

            return $this->responder->error($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responder->withEntity($apptSlot, Response::HTTP_CREATED);
    }

    public function update(Request $request, ApptSlotUpdate $usecase): JsonResponse
    {
        $posts = $request->input();

        try {
            $apptSlot = $usecase->__invoke($posts);
        } catch (Exception $e) {
            logs()->error($e);

            return $this->responder->error($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responder->withEntity($apptSlot, Response::HTTP_OK);
    }

    public function delete(Request $request, ApptSlotDelete $usecase): JsonResponse
    {
        $posts = $request->input();

        try {
            $isDeleted = $usecase->__invoke($posts);
            if (! $isDeleted) {
                throw new Exception();
            }
        } catch (Exception $e) {
            logs()->error($e);

            return $this->responder->error($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(
            [],
            Response::HTTP_OK
        );
    }

    public function reserve(Request $request, AcceptReservation $usecase): JsonResponse
    {
        $posts = $request->input();

        try {
            $apptSlot = $usecase->__invoke($posts);
        } catch (Exception $e) {
            logs()->error($e);

            return $this->responder->error($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->responder->withEntity($apptSlot, Response::HTTP_OK);
    }
}
