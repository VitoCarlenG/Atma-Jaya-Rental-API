<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        $promo=DB::table('promo')->select('id_promo', 'kode_promo', 'jenis_promo', 'keterangan_promo')->where('status_promo', 1)->where('aktivasi_promo', 1)->get();

        if(count($promo)>0) {
            return response([
                'message' => 'Retrieve All Success',
                'promo' => $promo
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'promo' => null
        ], 400);
    }

    public function show($id_promo)
    {
        $promo=DB::table('promo')->select('kode_promo', 'jenis_promo', 'keterangan_promo')->where('status_promo', 1)->where('aktivasi_promo', 1)->where('id_promo', $id_promo)->get();

        if(count($promo)>0) {
            return response([
                'message' => 'Retrieve Promo Success',
                'promo' => $promo
            ], 200);
        }

        return response([
            'message' => 'Promo Not Found',
            'promo' => null
        ], 404);
    }
}
