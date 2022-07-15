<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class OrderController extends Controller
{
    public function get_data(){
        try {

            $get_data = Product::orderBy('id', 'ASC')->get();
            return $get_data;

        }catch (\Exception $e) {
            DB::rollback();
            dd("Error. " . $e->getMessage());
            return false;
        }
    }

    public function proses_order(Request $req){
        try {
            $product = $req->product;
            // $harga = $req->harga;
            $qty = $req->qty;
            $nominal_masuk = $req->nominal_masuk;

            if($nominal_masuk != 2000 && $nominal_masuk != 5000 && $nominal_masuk != 10000 && $nominal_masuk != 20000 && $nominal_masuk != 50000){
                return response()->json(['success'=>false, 'massage' => 'Pecahan tidak sesuai']);
            }

            
            if($nominal_masuk){
                $check_product = Product::where('nama', $product)->where('stok', '!=' ,0)->first();

                if($check_product){
                    if($qty > $check_product->stok){
                        return response()->json(['success'=>false, 'massage' => 'Stok tidak cukup']);
                    }

                    if($nominal_masuk >= ($check_product->harga * $qty) ){
                        $check_product->stok -= $qty;
                        $check_product->save();

                        $uang_kembali = $nominal_masuk - ($check_product->harga * $qty);
                        return response()->json(['success'=>true, 'massage' => 'Transaksi berhasil', 'data' => $uang_kembali]);
                    }else{
                        return response()->json(['success'=>false, 'massage' => 'Uang tidak cukup']);
                    }
                }else{
                    return response()->json(['success'=>false, 'massage' => 'Stok habis']);
                }

            }else{
                return response()->json(['success'=>false, 'massage' => 'Tidak ada transaksi']);
            }
    
          }catch (\Exception $e) {
            DB::rollback();
            dd("Error. " . $e->getMessage());
            return false;
          }
    
    }
}