<?php

namespace App\Http\Controllers;

use App\Models\Forma_pgto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class formaPagamentoscontroller extends Controller
{
    public function form(){
        $formaPagamentos = Forma_pgto::orderBy('nome', 'ASC')
        ->get();

        $formaPagamentos->push(new Forma_pgto(['id' => 0, 
                                         'nome' => '']));
        $formaPagamentos->push(new Forma_pgto(['id' => 0, 
                                         'nome' => '']));
        $formaPagamentos->push(new Forma_pgto(['id' => 0, 
                                         'nome' => '']));

        // Carregar a VIEW
        return view('formaPagamentos.form', ['formaPagamentos' => $formaPagamentos]);
    }
//public function updateCategorias(Request $request){
    public function updateFormaPgto(Request $request){
        $data = $request;

        $falha = 0;

        foreach ($data['itens'] as $item) {

            if( empty( $item['id'] ) ){

                if( !empty( $item['nome'] ) )
                {
                    DB::beginTransaction();

                    try {
                        $Forma_pgto = Forma_pgto::create([
                            'nome' => $item['nome'] , 
                        ]);

                        DB::commit();
                        Log::info('Forma Pagamento incluida: {id}', ['id' => $Forma_pgto]);
                        
                    } catch (Exception $e) {
                        $falha = 1;
                        
                        // Operação não é concluída com êxito
                        DB::rollBack();
                        Log::error('Forma Pagamento não incluida/alterada.', [ 'error' => $e->getMessage()]);
                    }
                }
            }else{
                // localizar registro no banco de dados.
                $Forma_pgto = Forma_pgto::where('id', $item['id'])->first();
                
                DB::beginTransaction();

                try {
                    // registro nao foi marcado para excluir
                    if( isset($item['excluir']) == 0 ){

                        $Forma_pgto->update([
                            'nome' => $item['nome'],
                        ]);
                    }else{

                        $Forma_pgto->delete();
                    }

                    DB::commit();

                } catch (Exception $e) {
                    $falha = 1;

                    DB::rollBack();
                    Log::error('Forma Pagamento não incluida/alterada.', [ 'error' => $e->getMessage()]);
                }

            }
        }

        if( $falha == 0 ){   
            
            return redirect()->route('formaPagamentos.form')->with('success', 'Forma Pagamento cadastrada/alterada com sucesso!');

        }else{
            return back()->withInput()->with('error', 'Forma Pagamento não cadastrada/alterada!');

        }

    }
}
