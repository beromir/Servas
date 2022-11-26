<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\WebpageData;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class LinkController extends Controller
{
    protected function rules(): array
    {
        return [
            'title' => 'string|min:2|nullable',
            'link' => 'url|required',
            'groups' => 'array',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        if (!Request::user()->tokenCan('create')) {
            return response('Missing permissions.', status: 403, headers: ['Content-Type' => 'text/plain']);
        }

        $validator = Validator::make(Request::all(), $this->rules());

        if ($validator->fails()) {
            return response('Wrong data.', status: 422, headers: ['Content-Type' => 'text/plain']);
        }

        Request::validate($this->rules());

        $link = Link::make();

        $link->link = Request::get('link');
        $link->title = Request::get('title');
        $link->user_id = Auth::id();

        if (empty($link->title)) {
            $link->title = WebpageData::getWebPageTitle($link->link);
        }

        $link->save();

        return response('The link was added.', headers: ['Content-Type' => 'text/plain']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
