<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function show($id)
    {
        $customer=DB::table('customer')->select('format_id_customer', 'id_customer', 'nama_customer', 'alamat_customer', 'email_customer', 'nomor_telepon_customer', 'password_customer')->where('id', $id)->get();

        if(count($customer)>0) {
            return response([
                'message' => 'Retrieve Data Success',
                'customer' => $customer
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'customer' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $users=User::find($id);

        if(is_null($users)) {
            return response([
                'message' => 'Data Not Found',
                'customer' => null
            ], 404);
        }

        $updateData=$request->all();
        $validate=Validator::make($updateData, [
            'nama_customer' => 'required',
            'alamat_customer' => 'required',
            'email_customer' => 'required|email:rfc,dns|unique:users,email,'.$id,
            'nomor_telepon_customer' => 'required|numeric',
            'password_customer' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        DB::table('customer')
        ->where('id', $id)
        ->update([
            'nama_customer'     => $updateData['nama_customer'],
            'alamat_customer'     => $updateData['alamat_customer'],
            'email_customer'     => $updateData['email_customer'],
            'nomor_telepon_customer'     => $updateData['nomor_telepon_customer'],
            'password_customer'     => bcrypt($request->password_customer)
        ]);

        $users->name=$updateData['nama_customer'];
        $users->email=$updateData['email_customer'];
        $users->password=bcrypt($request->password_customer);
        $users->updated_at=Carbon::now()->timestamp;

        if($users->save()) {
            $customer=DB::table('customer')->select('format_id_customer', 'id_customer', 'nama_customer', 'alamat_customer', 'email_customer', 'nomor_telepon_customer', 'password_customer')->where('id', $id)->get();

            return response([
                'message' => 'Update Profile Success',
                'customer' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Profile Failed',
            'customer' => null,
        ], 400);
    }
}
