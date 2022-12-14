<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Services\Account\UserAccountService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    private $userAccount;
    public function __construct(UserService $userAccount){
        $this->userAccount = $userAccount;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $users = $this->userAccount->userWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'users' => $users,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->userAccount->userWithRelations()->findOrFail($id);
            return response()->json([
                'success' => true,
                'user' => $user,
                'pets' => $user->pets,
                'appointments' => $user->appointments,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->userAccount->verifyUser($id);
            return response()->json([
                'success' => true,
                'user' => $data['user'],
                'message' => $data['message'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
