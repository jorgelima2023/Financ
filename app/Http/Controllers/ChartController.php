<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChartController extends Controller
{
    public function index()
    {
        // Dados simulando banco
        $chartData = [
            'labels' => ['Produto A', 'Produto B', 'Produto C'],
            'values' => [120, 80, 45]
        ];

        return view('Movimentos.chartdashboard', compact('chartData'));
    }

    public function pieData()
    {
         // Dados simulando banco
        $chartData = [
            'labels' => ['Produto A', 'Produto B', 'Produto C'],
            'values' => [120, 80, 45]
        ];

        return view('Movimentos.grafico', compact('chartData'));

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
