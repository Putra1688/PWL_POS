<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index() {

        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $stoks = StokModel::with('barang')->get();
        
        $activeMenu = 'dashboard';
        return view ('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu'=> $activeMenu,
            'stoks' => $stoks
        ]);
    }
}
