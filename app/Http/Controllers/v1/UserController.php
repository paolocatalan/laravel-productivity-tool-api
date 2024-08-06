<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user();
    }

    public function approve(Request $request)
    {
        Gate::authorize('approve-user');

        $user = User::findOrfail($request->id);

        $user->update(['approved' => 1]);

        return $this->success('', 'Team member has been approved.', 200);

    }
}
