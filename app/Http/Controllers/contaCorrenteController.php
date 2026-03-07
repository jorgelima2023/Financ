<?php

namespace App\Http\Controllers;

use App\Models\ContasCorrente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class contaCorrenteController
{
    public function form(){
        $ContasCorrente = ContasCorrente::orderBy('nome', 'ASC')
        ->get();

        $ContasCorrente->push(new ContasCorrente(['id' => 0, 
                                         'nome' => '' , 'agencia' => '' , 'conta' => '' , 'cod_compe' => '' , 'carteira' => '' ]));
        $ContasCorrente->push(new ContasCorrente(['id' => 0, 
                                         'nome' => '' , 'agencia' => '' , 'conta' => '' , 'cod_compe' => '' , 'carteira' => '' ]));
        $ContasCorrente->push(new ContasCorrente(['id' => 0, 
                                         'nome' => '' , 'agencia' => '' , 'conta' => '' , 'cod_compe' => '' , 'carteira' => '' ]));

        // Carregar a VIEW
        return view('contaCorrente.form', ['ContasCorrente' => $ContasCorrente]);
    }
                    
    public function updateContaCorrente(Request $request){
        //dd($request);
        $data = $request;

        $falha = 0;

        foreach ($data['itens'] as $item) {

            if( empty( $item['id'] ) ){

                if( !empty( $item['nome'] ) )
                {
                    DB::beginTransaction();

                    try {
                        $Conta_corrente = ContasCorrente::create([
                            'nome' => $item['nome'] ,
                            'agencia' => $item['agencia'] ,
                            'conta' => $item['conta'] ,
                            'cod_compe' => $item['cod_compe'] , 
                            'carteira' => $item['carteira'] ,
                        ]);

                        DB::commit();
                        Log::info('Conta corrente incluida: {id}', ['id' => $Conta_corrente]);
                        
                    } catch (Exception $e) {
                        $falha = 1;
                        
                        // Operação não é concluída com êxito
                        DB::rollBack();
                        Log::error('Forma Pagamento não incluida/alterada.', [ 'error' => $e->getMessage()]);
                    }
                }
            }else{
                // localizar registro no banco de dados.
                $Conta_corrente = ContasCorrente::where('id', $item['id'])->first();
                
                DB::beginTransaction();

                try {
                    // registro nao foi marcado para excluir
                    if( isset($item['excluir']) == 0 ){

                        $Conta_corrente->update([
                            'nome' => $item['nome'],
                            'agencia' => $item['agencia'] ,
                            'conta' => $item['conta'] ,
                            'cod_compe' => $item['cod_compe'] , 
                            'carteira' => $item['carteira'] ,
                        ]);
                    }else{

                        $Conta_corrente->delete();
                    }

                    DB::commit();

                } catch (Exception $e) {
                    $falha = 1;

                    DB::rollBack();
                    Log::error('Conta corrente não incluida/alterada.', [ 'error' => $e->getMessage()]);
                }

            }
        }

        if( $falha == 0 ){   
            
            return redirect()->route('contaCorrente.form')->with('success', 'Conta corrente cadastrada/alterada com sucesso!');

        }else{
            return back()->withInput()->with('error', 'Conta corrente não cadastrada/alterada!');

        }

    }
}
