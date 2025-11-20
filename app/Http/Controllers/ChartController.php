<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function pieData()
    {
        // Exemplos (depois pode alimentar via DB)
        $labels = ["Produto A", "Produto B", "Produto C"];
        $values = [45, 30, 25];
        $colors = ["#4e73df", "#1cc88a", "#36b9cc"];

        return response()->json([
            "labels" => $labels,
            "values" => $values,
            "colors" => $colors
        ]);

        // ===================================================================================
        // $produtos = \DB::table('produtos')
        // ->select('categoria', \DB::raw('count(*) as total'))
        // ->groupBy('categoria')
        // ->get();

        // return response()->json([
        //     'labels' => $produtos->pluck('categoria'),
        //     'values' => $produtos->pluck('total'),
        //     'colors' => ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'] // ou gerar dinamicamente
        // ]);

    }
}
