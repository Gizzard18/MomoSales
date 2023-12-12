<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;
use App\Models\categoria_producto;
use App\Models\categoria_servicio;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{

    function getCategories(Request $request)
    {
        $text2Search = $request->get('q');

        $results = categoria_producto::where('name', 'like', "%{$text2Search}%")->orderBy('name', 'asc')
            ->get()->take(10);

        return response()->json($results);
    }
    function getCategoriesS(Request $request)
    {
        $text2Search = $request->get('q');

        $results = categoria_servicio::where('name', 'like', "%{$text2Search}%")->orderBy('name', 'asc')
            ->get()->take(10);

        return response()->json($results);
    }

    function getCustomers(Request $request)
    {

        $text2Search = $request->get('q');

        $results = cliente::where('first_name', 'like', "%{$text2Search}%")
            ->orWhere('last_name', 'like', "%{$text2Search}%")
            ->orWhere('email', 'like', "%{$text2Search}%")
            ->select('id', 'first_name', 'last_name', 'email', DB::raw(" concat(first_name , ' ' , last_name)as text "))
            ->orderBy('first_name', 'asc')
            ->get()->take(10);


        return response()->json($results);
    }
}

