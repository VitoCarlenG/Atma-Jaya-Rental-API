<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function show($id)
    {
        $driver=DB::table('driver')->select('format_id_driver', 'id_driver', 'nama_driver', 'alamat_driver', 'email_driver', 'nomor_telepon_driver', 'rerata_rating_driver', 'status_ketersediaan_driver', 'password_driver')->where('id', $id)->get();

        if(count($driver)>0) {
            return response([
                'message' => 'Retrieve Data Success',
                'driver' => $driver
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'driver' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $users=User::find($id);

        if(is_null($users)) {
            return response([
                'message' => 'Data Not Found',
                'driver' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_driver' => 'required',
            'alamat_driver' => 'required',
            'email_driver' => 'required|email:rfc,dns|unique:users,email,'.$id,
            'nomor_telepon_driver' => 'required|numeric',
            'status_ketersediaan_driver' => 'required|numeric|min:0|max:1',
            'password_driver' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        DB::table('driver')
        ->where('id', $id)
        ->update([
            'nama_driver'     => $updateData['nama_driver'],
            'alamat_driver'     => $updateData['alamat_driver'],
            'email_driver'     => $updateData['email_driver'],
            'nomor_telepon_driver'     => $updateData['nomor_telepon_driver'],
            'status_ketersediaan_driver'     => $updateData['status_ketersediaan_driver'],
            'password_driver'     => bcrypt($request->password_driver)
        ]);

        $users->name=$updateData['nama_driver'];
        $users->email=$updateData['email_driver'];
        $users->password=bcrypt($request->password_driver);
        $users->updated_at=Carbon::now()->timestamp;

        if($users->save()) {
            $driver=DB::table('driver')->select('format_id_driver', 'id_driver', 'nama_driver', 'alamat_driver', 'email_driver', 'nomor_telepon_driver', 'rerata_rating_driver', 'status_ketersediaan_driver', 'password_driver')->where('id', $id)->get();

            return response([
                'message' => 'Update Profile Success',
                'driver' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Profile Failed',
            'driver' => null,
        ], 400);
    }
}
