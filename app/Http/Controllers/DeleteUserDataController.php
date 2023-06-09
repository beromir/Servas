<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserDataRequest;
use App\Services\DeleteUserDataService;
use Illuminate\Support\Facades\Auth;

class DeleteUserDataController extends Controller
{
    public function delete(DeleteUserDataRequest $request, DeleteUserDataService $deleteUserDataService)
    {
        $deleteOptions = $request->validated('deleteOptions');

        $deleteUserDataService->deleteUserData($deleteOptions, Auth::user());
    }
}
