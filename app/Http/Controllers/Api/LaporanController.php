<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Transaksi;
use App\Models\Brosur;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index($pointer, $month, $year)
    {
        if($pointer==1) {
            
            $laporan=DB::table('transaksi')->select('TIPE_MOBIL', 'NAMA_MOBIL', 'HARGA_SEWA_MOBIL', DB::raw('COUNT(NAMA_MOBIL) "JUMLAH_PEMINJAMAN"'), DB::raw('SUM((DATE(TANGGAL_WAKTU_SELESAI_SEWA)-DATE(TANGGAL_WAKTU_MULAI_SEWA))) AS TOTAL_DURASI_SEWA'), DB::raw('SUM(HARGA_SEWA_MOBIL*(DATE(TANGGAL_WAKTU_SELESAI_SEWA)-DATE(TANGGAL_WAKTU_MULAI_SEWA))) AS PENDAPATAN_DARI_MOBIL'))->join('aset_mobil', 'transaksi.id_mobil', '=', 'aset_mobil.id_mobil')->where('transaksi.status_transaksi', 'Selesai')->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$year."-".$month."-01", $year."-".$month."-31"])->groupBy('NAMA_MOBIL')->orderByDesc('PENDAPATAN_DARI_MOBIL')->get();

            if(count($laporan)>0) {
                return response([
                    'message' => 'Retrieve All Success',
                    'laporan' => $laporan
                ], 200);
            }
    
            return response([
                'message' => 'Tidak Ada Data Pada Bulan Dan Tahun Ini',
                'laporan' => null
            ], 400);
        }else if($pointer==2) {

            $laporan=DB::table('transaksi')->select('NAMA_CUSTOMER', 'NAMA_MOBIL', 'JENIS_TRANSAKSI', DB::raw('COUNT(NOMOR_TRANSAKSI) "JUMLAH_TRANSAKSI"'), DB::raw('SUM(TOTAL_PEMBAYARAN) AS PENDAPATAN_YANG_DIPEROLEH_DARI_CUSTOMER'))->join('aset_mobil', 'transaksi.id_mobil', '=', 'aset_mobil.id_mobil')->join('customer', 'transaksi.id_customer', '=', 'customer.id_customer')->where('transaksi.status_transaksi', 'Selesai')->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$year."-".$month."-01", $year."-".$month."-31"])->groupBy('NAMA_CUSTOMER', 'NAMA_MOBIL', 'JENIS_TRANSAKSI')->get();

            if(count($laporan)>0) {
                return response([
                    'message' => 'Retrieve All Success',
                    'laporan' => $laporan
                ], 200);
            }
    
            return response([
                'message' => 'Tidak Ada Data Pada Bulan Dan Tahun Ini',
                'laporan' => null
            ], 400);
        }else if($pointer==3) {

            $laporan=DB::table('transaksi')->select('FORMAT_ID_DRIVER', 'driver.ID_DRIVER', 'NAMA_DRIVER', DB::raw('COUNT(NOMOR_TRANSAKSI) "JUMLAH_TRANSAKSI"'))->join('driver', 'transaksi.id_driver', '=', 'driver.id_driver')->where('transaksi.status_transaksi', 'Selesai')->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$year."-".$month."-01", $year."-".$month."-31"])->groupBy('ID_DRIVER')->orderByDesc('JUMLAH_TRANSAKSI')->limit(5)->get();

            if(count($laporan)>0) {
                return response([
                    'message' => 'Retrieve All Success',
                    'laporan' => $laporan
                ], 200);
            }
    
            return response([
                'message' => 'Tidak Ada Data Pada Bulan Dan Tahun Ini',
                'laporan' => null
            ], 400);
        }else if($pointer==4) {

            $laporan=DB::table('transaksi')->select('FORMAT_ID_DRIVER', 'driver.ID_DRIVER', 'NAMA_DRIVER', DB::raw('COUNT(NOMOR_TRANSAKSI) "JUMLAH_TRANSAKSI"'), 'RERATA_RATING_DRIVER')->join('driver', 'transaksi.id_driver', '=', 'driver.id_driver')->where('transaksi.status_transaksi', 'Selesai')->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$year."-".$month."-01", $year."-".$month."-31"])->groupBy('ID_DRIVER')->orderByDesc('JUMLAH_TRANSAKSI')->limit(5)->get();

            if(count($laporan)>0) {
                return response([
                    'message' => 'Retrieve All Success',
                    'laporan' => $laporan
                ], 200);
            }
    
            return response([
                'message' => 'Tidak Ada Data Pada Bulan Dan Tahun Ini',
                'laporan' => null
            ], 400);
        }else if($pointer==5) {

            $laporan=DB::table('transaksi')->select('NAMA_CUSTOMER', DB::raw('COUNT(NOMOR_TRANSAKSI) "JUMLAH_TRANSAKSI"'))->join('customer', 'transaksi.id_customer', '=', 'customer.id_customer')->where('transaksi.status_transaksi', 'Selesai')->whereBetween(DB::raw('DATE(transaksi.tanggal_transaksi)'), [$year."-".$month."-01", $year."-".$month."-31"])->groupBy('NAMA_CUSTOMER')->orderByDesc('JUMLAH_TRANSAKSI')->limit(5)->get();

            if(count($laporan)>0) {
                return response([
                    'message' => 'Retrieve All Success',
                    'laporan' => $laporan
                ], 200);
            }
    
            return response([
                'message' => 'Tidak Ada Data Pada Bulan Dan Tahun Ini',
                'laporan' => null
            ], 400);
        }

        return response([
            'message' => 'Empty',
            'laporan' => null
        ], 400);
    }
}
