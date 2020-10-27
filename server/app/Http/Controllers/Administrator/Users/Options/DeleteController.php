<?php

namespace App\Http\Controllers\Administrator\Users\Options;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Auth\JobAccountTerminated;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-users');
    }


    /**
     * Delete seletcetd users
     *
     * @param Request $request
     * @param User $users
     * @return void
     */
    public function delete(Request $request, User $users)
    {
    	// Loop trough users
        foreach ($request->users as $user) {

            $account = $users->isNotSuperAdmin()->whereUniqueId($user['unique_id'])->first();

            if ($account) {

                // Delete account
                $account->delete();

                // Send notification
                JobAccountTerminated::dispatch($account);

            }
        }

        // Success
        return response()->json([], 200);
    }
}
