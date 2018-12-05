<?php

namespace Modules\User\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

use Modules\User\Entities\EmployeeFiles;

class UserController extends Controller
{
    //<editor-fold desc="CRUD Operations">
    /**
     *  *** LIST ***
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = User::all();
        return new Response($data);
    }

    /**
     *  *** CREATE ***
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->post();
		$data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return new Response([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     *  *** RETRIEVE ***
     * Show the specified resource.
     *
     * @param  User $user
     * @return Response
     */
    public function show(User $user)
    {
		$user->getFiles;
		$user->getLeaves;
		$user->getClaims;
        return new Response($user);
    }

    /**
     *  *** UPDATE ***
     * Update the specified resource in storage.
     *
     * @param  User $user
     * @param  Request $request
     * @return Response
     */
    public function update(User $user, Request $request)
    {
        // Load new data
        $data = $request->post();

        // Massage data
        unset($data['api_token_expires']);
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Update data
        $user->update($data);

        return new Response([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     *  *** DELETE ***
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        // Delete the user
        try {
            $user->delete();
        }
        catch(\Exception $e) {
            $response = new Response([
                'message' => $e->getMessage(),
                'user' => $user
            ]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $response;
        }

        return new Response([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }
    //</editor-fold>
    //<editor-fold desc="Utility Operations">
    /**
     * Change users password
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function changePassword(User $user, Request $request)
    {
        $data = $request->post();
		/* $count = User::where([['password','=',Hash::make($data['my_password'])],['id','=',$user->id]])->count();
		if($count > 0){ */
			$user->password = Hash::make($data['new_password']);
			$user->save();
			return new Response([
				'message' => 'Password updated successfully',
				'user' => $user,
				'error' => false
			]);
		/* } else {
			return new Response([
				'message' => 'Current password is wrong',
				'user' => $user,
				'error' => true
			]);
		}  */
    }

    /**
     * Logout any user in the system
     *
     * @param User $user
     * @return Response
     */
    public function logoutUser(User $user) {
        $user->api_token = '';
        $user->save();

        return new Response([
            'message' => 'User logged out successfully',
            'user' => $user
        ]);
    }

    /**
     * Disable any user in the system
     *
     * @param User $user
     * @return Response
     */
    public function disableUser(User $user) {
        // Disable user
        $user->api_token = '';
        $user->role = 'disabled';
        $user->save();

        return new Response([
            'message' => 'User Disabled',
            'user' => $user
        ]);
    }

    /**
     * Enables any user in the system
     *
     * @param User $user
     * @return Response
     */
    public function enableUser(User $user) {
        // Disable user
        $user->api_token = '';
        $user->role = 'subscriber';
        $user->save();

        return new Response([
            'message' => 'User Enabled',
            'user' => $user
        ]);
    }
    //</editor-fold>
	
	/**
     * Check Email Already Exist in DB
     * @param  Request $request
     * @return Response
     */
    public function checkIfEmailExist(Request $request)
    {
		$data = $request->post();
		return new Response(User::where([['email','=',$data['email']],['id','!=',$data['id']]])->count());
    }	
	
	/**
     * Check Department HOD Already Exist in DB
     * @param  Request $request
     * @return Response
     */
    public function checkIfDepartmentHODExist(Request $request)
    {
		$data = $request->post();
		return new Response(User::where([['department','=',$data['department']],['designation','=',$data['designation']],['id','!=',$data['id']]])->count());
    }
	
	public function updatedocuments(Request $request) {
		$id = $request->post('id');
		$files = $request->post('files');
		$data = [];		
		foreach($files as $file){
			$row['name'] = $file['name'];
			$row['original_name'] = $file['original_name'];
			$row['user_id'] =$id;
			$row['created_at'] = $row['updated_at'] = date('Y-m-d H:i:s');
			$data[] = $row;
		}		
		EmployeeFiles::insert($data);       
		return new Response([
            'message' => 'Employee docs saved successfully',
        ]);
	}
	
	public function uploadDocuments(Request $request) {
		$destinationPath = public_path('/employee');
		$output = [];
		foreach($request->file('documents') as $image){
			$name = time().'.'.$image->getClientOriginalExtension();			
			$image->move($destinationPath, $name);
			$output[] = array('name'=>$name,'original_name'=>$image->getClientOriginalName());
		}
		return new Response([
            'message' => 'Files uploaded successfully',
            'upoadedfiles' => $output
        ]);
	}
	
	public function deleteDocument(Request $request) {
		$user_id = $request->post('id');
		$file = $request->post('file');
		$destinationPath = public_path('employee');
		try{
			unlink($destinationPath."/".$file['name']);
			//EmployeeFiles::where(['user_id',$user_id])->delete();
			EmployeeFiles::where([['user_id','=',$user_id],['name','=',$file['name']]])->delete();
			return new Response([
				'message' => 'File deleted successfully',
			]);
		}catch(\Exception $e) {
            $response = new Response([
                'message' => $e->getMessage()
            ]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $response;
        }
		
	}	
}
