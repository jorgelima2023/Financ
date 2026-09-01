<?php

namespace App\View\Components;

use App\Models\Categoria;
use Illuminate\View\Component;
use Illuminate\View\View;

class SelectCategoria extends Component
{
    public $categorias;

    public function __construct()
    {
        $this->categorias = Categoria::orderBy('nome')->get();
    }

    public function render(): View
    {
        return view('components.select-categoria');
    }
}