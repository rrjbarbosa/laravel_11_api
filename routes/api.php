<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\seguranca\UserController;
use App\Http\Controllers\cadastro\PermissaorController;
use App\Http\Controllers\cadastro\EmpresarController;
use App\Http\Controllers\cadastro\SetorUserController;
use App\Http\Controllers\cadastro\AcessorController;
use App\Http\Controllers\cadastro\AcessorUserController;

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
    Route::post('/acesso-criar',                                [AcessorController::class, 'criar']);
    Route::put('/acesso-editar',                                [AcessorController::class, 'editar']); 
    Route::delete('/acesso-excluir/{id}',                       [AcessorController::class, 'excluir']);
    //ACESSO USER==============================================================================================
    Route::patch('/acesso-user-em-user-update-salvar/{user_id}',    [AcessorUserController::class, 'salvar']);
    Route::post('/acesso-user-create-delete',                       [AcessorUserController::class, 'createDelete']);
    //EMPRESA==================================================================================================
    Route::get('/empresas',                                             [EmpresarController::class, 'empresas']);
    Route::get('/empresa-edit/{id}',                                    [EmpresarController::class, 'edit']);
    Route::patch('/empresas-habitita-desabilita/{id}',                  [EmpresarController::class, 'habilitaDesabilita']);
    Route::post('/empresas-em-user-update-grid',                        [EmpresarController::class, 'empresaEmUserUpdateGrid']);
    Route::patch('/empresas-em-user-update-habitita-desabilita/{id}',   [EmpresarController::class, 'empresaEmUserUpdateHabilitaDesabilita']);
    //PERMISSÃO=================================================================================================
    Route::post('/permissao-por-acesso',                        [PermissaorController::class, 'permissaoPorAcesso']);
    Route::patch('/permissao-por-acesso-salvar/{acessor_id}',   [PermissaorController::class, 'permissaoPorAcessoSalvar']);
    //SETOR USER ===============================================================================================
    Route::post('/setor-user-em-user-update-grid',                   [SetorUserController::class, 'grid']);    
    Route::patch('/setor-user-em-user-update-salvar/{user_id}',      [SetorUserController::class, 'update']);    
    //USERS=====================================================================================================
    Route::post('/users-grid-pesquisa',                     [UserController::class, 'gridPesquisa']);
    Route::post('/users-create',                            [UserController::class, 'create']);
    Route::put('/users-edit',                               [UserController::class, 'edit']);
    Route::patch('/users-update/{id}',                      [UserController::class, 'update']);
    Route::patch('/users-admin/{id}',                       [UserController::class, 'admin']);
    Route::patch('/users-habilita-desabilita/{id}',         [UserController::class, 'habilitaDesabilita']);
    Route::patch('/users-update-senha/{id}',                [UserController::class, 'updateSeha']); 
});