<?php

namespace App\Http\Controllers\Api;

use App\Events\AuditedTask;
use App\Events\TaskSaved;
use App\Http\Requests\AllotTaskRequest;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\TaskScoreRequest;
use App\Repositories\TaskProgressRepository;
use App\Repositories\TaskRepository;
use Auth;
use Carbon\Carbon;

class TaskController extends BaseController
{
    protected $taskRepository;

    public function __construct(TaskRepository $repository)
    {
        $this->taskRepository = $repository;
    }

    /**
     * 创建任务
     * @param CreateTaskRequest $taskRequest
     * @return \Dingo\Api\Http\Response
     */
    public function createTask(CreateTaskRequest $taskRequest)
    {
        if ($this->allowCreateTask()) {
            event(new TaskSaved($this->taskRepository->createTask($taskRequest->all())));
        }
        return $this->response->noContent();
    }

    /**
     * 任务审核
     * @param $taskId
     */
    public function auditTask($taskId)
    {
        $data['status'] = 'publish';
        if ($this->allowAuditTask()) {
            if ($this->taskRepository->hasRecord(['status' => 'draft', 'id' => $taskId])) {
                $this->taskRepository->update($data, ['id' => $taskId]);
                event(new AuditedTask($taskId));
            }
        }
    }

    /**
     * 软删除任务
     * @param $taskId
     */
    public function deleteTask($taskId)
    {
        if ($this->allowDeleteTask()) {
            if ($taskId != null) {
                $this->taskRepository->deleteTask($taskId);
            }
        }
        return $this->response->noContent();
    }

    /**
     * 恢复被软删除的任务
     * @param $taskId
     */
    public function restoreTask($taskId)
    {
        if ($this->allowRestoreTask()) {
            if ($taskId != null) {
                $this->taskRepository->restoreTask($taskId);
            }
        }
        return $this->response->noContent();
    }

    /**
     * 分配任务（指派责任人）
     * 因为只有各二级学院有权利对任务分配责任人，所以这里的学院id直接填当前用户的学院id
     * @param AllotTaskRequest $allotTaskRequest
     * @return \Dingo\Api\Http\Response
     */
    public function allotTask(AllotTaskRequest $allotTaskRequest)
    {
        if ($this->allowAllotTask()) {
            $allotTaskRequest->offsetSet('college_id', Auth::user()->college_id);
            app(TaskProgressRepository::class)->allotTask($allotTaskRequest);
        }
        return $this->response->noContent();
    }


    /**
     * 完成任务
     * @param $taskId  任务id
     * @param null $college_id 学院id
     * @return \Dingo\Api\Http\Response
     */
    public function submitTask($taskId)
    {
        if ($this->allowSubmitTask()) {
            $data['college_id'] = Auth::user()->college_id;
            $data['status'] = Carbon::now();
            $data['task_id'] = $taskId;
            app(TaskProgressRepository::class)->submitTask($data);
        }
        return $this->response->noContent();
    }

    /**
     * 任务评分
     * @param $taskId
     * @param TaskScoreRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function taskScore($taskId, TaskScoreRequest $request)
    {
        if ($this->allowScore()) {
            $request->offsetSet('task_id', $taskId);
            app(TaskProgressRepository::class)->submitTask($request);
        }
        return $this->response->noContent();
    }

    private function allowCreateTask()
    {
        return $this->validatePermission('admin.create_task');
    }

    private function allowAuditTask()
    {
        return $this->validatePermission('admin.audit_task');
    }

    private function allowDeleteTask()
    {
        return $this->validatePermission('admin.delete_task');
    }

    private function allowRestoreTask()
    {
        return $this->allowDeleteTask();
    }

    private function allowAllotTask()
    {
        return $this->validatePermission('admin.add_person_liable');
    }

    private function allowSubmitTask()
    {
        return $this->validatePermission('admin.submit_task');
    }

    private function allowScore()
    {
        return $this->validatePermission('admin.quality_assessment');
    }

    /*public function tasks()
    {
        return $this->response->collection($this->taskRepository->lists(), new TaskTransformer());
    }*/
}