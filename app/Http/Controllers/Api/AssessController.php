<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateAssessRequest;
use App\Http\Requests\UpdateAssessRequest;
use App\Repositories\AssessRepository;
use App\Transformers\AssessTransformer;

class AssessController extends BaseController
{
    public function lists()
    {
        return $this->response->collection(app(AssessRepository::class)->all(), new AssessTransformer());
    }

    public function store(CreateAssessRequest $request){
        return $this->response->item(app(AssessRepository::class)->create($request->only('title','score')),new AssessTransformer());
    }

    public function update(UpdateAssessRequest $request, $assessId){
        app(AssessRepository::class)->update($request->only('title','score'),['id'=>$assessId]);
        return $this->response->noContent();
    }

    public function delete($assessId){
        app(AssessRepository::class)->delete($assessId);
        return $this->response->noContent();
    }
}