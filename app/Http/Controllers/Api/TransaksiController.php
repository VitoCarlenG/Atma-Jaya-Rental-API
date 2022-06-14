<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Pegawai;
use App\Models\Promo;
use App\Models\Brosur;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index($id)
    {
        $customer=DB::table('transaksi')->select('format_nomor_transaksi', 'nomor_transaksi', 'tanggal_transaksi', 'status_transaksi', 'nama_customer', 'nama_driver', 'nama_pegawai', 'kode_promo', 'nama_mobil')->leftJoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')->leftJoin('driver', 'transaksi.id_driver', '=', 'driver.id_driver')->leftJoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')->leftJoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo')->leftJoin('aset_mobil', 'transaksi.id_mobil', '=', 'aset_mobil.id_mobil')->where('customer.id', $id)->where('transaksi.status_transaksi', 'Selesai')->orWhere('transaksi.status_transaksi', 'Ditolak')->get();

        $driver=DB::table('transaksi')->select('format_nomor_transaksi', 'nomor_transaksi', 'tanggal_transaksi', 'status_transaksi', 'nama_customer', 'nama_driver', 'nama_pegawai', 'kode_promo', 'nama_mobil')->leftJoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')->leftJoin('driver', 'transaksi.id_driver', '=', 'driver.id_driver')->leftJoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')->leftJoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo')->leftJoin('aset_mobil', 'transaksi.id_mobil', '=', 'aset_mobil.id_mobil')->where('driver.id', $id)->where('transaksi.status_transaksi', 'Selesai')->orWhere('transaksi.status_transaksi', 'Ditolak')->get();

        $customerCheck=DB::table('customer')->select('id')->where('id', $id)->get();
        $driverCheck=DB::table('driver')->select('id')->where('id', $id)->get();

        if(count($customerCheck)>0) {
            return response([
                'message' => 'Retrieve All Success',
                'transaksi' => $customer,
                'temp' => 1
            ], 200);
        }else if(count($driverCheck)>0) {
            return response([
                'message' => 'Retrieve All Success',
                'transaksi' => $driver,
                'temp' => 2
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'transaksi' => null
        ], 400);
    }
}
