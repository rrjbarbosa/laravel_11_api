<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\seguranca\UserController;
use App\Http\Controllers\comercial\ComercialEmailController;
use App\Http\Controllers\cadastro\PermissaorController;
use App\Http\Controllers\cadastro\EmpresarController;
use App\Http\Controllers\cadastro\SetorController;
use App\Http\Controllers\cadastro\SetorUserController;
use App\Http\Controllers\cadastro\EstoquerUserController; 
use App\Http\Controllers\cadastro\EstoquerController;
use App\Http\Controllers\cadastro\AcessorController;
use App\Http\Controllers\cadastro\AcessorUserController;
use App\Http\Controllers\cadastro\AcessorPermissaorController;
use App\Http\Controllers\cadastro\EmailrController;

#**************************************************************************************************************
#**************************************************************************************************************
    # GET
        //-Para buscar dados (nunca altera nada)
    # POST
        //-Para criar recursos novos
    # PUT
        //-Para atualizar um recurso existente por completo.. Normalmente exige todos os campos do recurso.
    # PATCH
        //-Para atualizar parcialmente um recurso existente... Só manda os campos que você quer alterar.
#**************************************************************************************************************
#**************************************************************************************************************    

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //ACESSO===================================================================================================
    Route::post('/acesso-pesquisa-campos-head',                 [AcessorController::class, 'pesquisaCamposHead']);
    Route::post('/acesso-selecionado-edit',                     [AcessorController::class, 'edit']);
    Route::post('/acesso-criar',                                [AcessorController::class, 'criar']);
    Route::put('/acesso-editar',                                [AcessorController::class, 'editar']); 
    Route::post('/acesso-excluir',                              [AcessorController::class, 'excluir']);
    //ACESSO-PERMISSÕES========================================================================================
    Route::post('/acesso-permissoes-create_delete',             [AcessorPermissaorController::class, 'createDelete']);
    Route::post('/acesso-permissoes-modulo-create',             [AcessorPermissaorController::class, 'permissoesModuloCreate']);
    //ACESSO USER==============================================================================================
    Route::patch('/acesso-user-em-user-update-salvar/{user_id}',    [AcessorUserController::class, 'salvar']);
    Route::post('/acesso-user-create-delete',                       [AcessorUserController::class, 'createDelete']);
    //Comercial Email===========================================================================================
    Route::post('/comercial-email-grid',                        [ComercialEmailController::class, 'pesquisaCamposHead']);
    
    //EMPRESA==================================================================================================
    Route::post('/empresa-grid',                                [EmpresarController::class, 'grid']);
    Route::post('/empresa-create',                              [EmpresarController::class, 'create']);
    Route::put('/empresa-update',                               [EmpresarController::class, 'update']);
    Route::post('/empresa-habilita-desabilita',                 [EmpresarController::class, 'habilitaDesabilita']);
    Route::post('/empresa-sub-forms',                           [EmpresarController::class, 'dadosSubForms']);
    Route::post('/empresas-em-user-update-grid',                 [EmpresarController::class, 'empresaEmUserUpdateGrid']);
    Route::patch('/empresas-em-user-update-habitita-desabilita/{id}',  [EmpresarController::class, 'empresaEmUserUpdateHabilitaDesabilita']);
    Route::post('/empresa-em-user-update-permissoes',           [EmpresarController::class, 'empresaEmUserUpdatePermissoes']);
    Route::post('/empresa-estoques',                            [EmpresarController::class, 'estoques']);
    //EMAIL===================================================================================================
    Route::post('/email-grid',                                  [EmailrController::class, 'grid']);
    Route::post('/email-create',                                [EmailrController::class, 'create']);
    Route::put('/email-update',                                 [EmailrController::class, 'update']);
    Route::post('/email-habilita-desabilita',                   [EmailrController::class, 'habilitaDesabilita']);
    //ESTOQUE===================================================================================================
    Route::post('/estoque-grid',                            [EstoquerController::class, 'grid']);
    Route::post('/estoque-create',                          [EstoquerController::class, 'create']);
    Route::put('/estoque-update',                           [EstoquerController::class, 'update']);
    Route::post('/estoque-habilita-desabilita',             [EstoquerController::class, 'habilitaDesabilita']);
    //ESTOQUE USER =============================================================================================
    Route::post('/estoque-user-grid',                       [EstoquerUserController::class, 'grid']);
    Route::put('/estoque-user-create_delete',               [EstoquerUserController::class, 'createDelete']);
    //PERMISSÃO=================================================================================================
    Route::post('/permissao-pesquisa-campos-head',          [PermissaorController::class, 'pesquisaCamposHead']);
    
    //SETOR USER ===============================================================================================
    Route::post('/setor-user-em-user-update-grid',                   [SetorUserController::class, 'grid']);    
    Route::patch('/setor-user-em-user-update-salvar/{user_id}',      [SetorUserController::class, 'update']);    
    //SETOR=====================================================================================================
    Route::post('/setor-grid',                              [SetorController::class, 'grid']);
    Route::post('/setor-create',                            [SetorController::class, 'create']); 
    Route::put('/setor-update',                             [SetorController::class, 'update']);
    Route::post('/setor-habilita-desabilita',               [SetorController::class, 'habilitaDesabilita']);
    Route::post('/setor-user-update-pesquisa',              [SetorController::class, 'setorUserUpdatePesquisa']);
    //USERS=====================================================================================================
    //Route::post('register',                                 [UserController::class, 'register']);
    Route::post('/users-ativa-desativa-multiplas-telas',    [UserController::class, 'ativaDesativaMultiplasTelas']);
    Route::post('/users-grid-pesquisa',                     [UserController::class, 'gridPesquisa']);
    Route::post('/users-create',                            [UserController::class, 'create']);
    Route::put('/users-edit',                               [UserController::class, 'edit']);
    Route::patch('/users-update/{id}',                      [UserController::class, 'update']);
    Route::patch('/users-admin/{id}',                       [UserController::class, 'admin']);
    Route::patch('/users-habilita-desabilita/{id}',         [UserController::class, 'habilitaDesabilita']);
    Route::patch('/users-update-senha/{id}',                [UserController::class, 'updateSeha']); 

    // Route::get('/users-menu',                             [UserController::class, 'menu']);
    //TESTES===================================================================================================
    Route::post('/user-email-lista',                        [UserController::class, 'emailLista']);
    Route::post('/user-email-lista-dois',                   [UserController::class, 'emailListaDois']);  
});




/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/
