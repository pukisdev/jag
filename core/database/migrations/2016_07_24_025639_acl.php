<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Acl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_module_mst',function(Blueprint $table){
            $table->string('id_module', 8)->primary();
            $table->string('nama_module',32);
            $table->integer('urutan', false, true)->nullable();
            $table->string('keterangan',64)->nullable();
            
            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');

        });

        Schema::create('sys_type_mst', function(Blueprint $table){
            $table->string('id_type', 8)->primary();
            $table->string('nama_type', 32);
            
            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');            
        });

        // table master status
        Schema::create('sys_status_mst', function (Blueprint $table) {
            $table->string('id_status',10)->primary();
            $table->string('keterangan',128)->nullable();

            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');            
        });

        /*
            table sys_app_mst
        */
        Schema::create('sys_app_mst', function(Blueprint $table){
            $table->increments('id_app');
            $table->integer('root',false,true)->nullable();
            $table->string('nama', 64);
            $table->string('f_type', 8);
            $table->string('f_module', 32);
            $table->integer('urutan', false, true);
            $table->string('route', 128)->nullabel();
            $table->string('link', 256)->nullable();
            $table->enum('akses_role', ['*','L'])->default('L');
            $table->string('keterangan', 256)->nullable();
            
            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');            

        });

        Schema::table('sys_app_mst', function(Blueprint $table){
            $table->foreign('f_type')->references('id_type')->on('sys_type_mst');
            $table->foreign('f_module')->references('id_module')->on('sys_module_mst');
        });

        // end of sys_app_mst

        /* table sys_group_mst */
        Schema::create('sys_group_mst', function(Blueprint $table){
            $table->string('id_group', 8)->primary();
            $table->string('nama_group', 16);
            $table->string('keterangan', 256)->nullable();
            
            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');                        
        });

        // end of sys_group_mst

        /* table sys_group_akses_det*/
        Schema::create('sys_group_akses_det', function(Blueprint $table){
            $table->integer('f_app', false, true);
            $table->string('f_group', 8);
            $table->string('akses', 16)->default('c,r,u,d');

            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');                        
        });

        Schema::table('sys_group_akses_det', function(Blueprint $table){
            $table->foreign('f_app')->references('id_app')->on('sys_app_mst');
            $table->foreign('f_group')->references('id_group')->on('sys_group_mst');
        });
        // end of sys_group_akses_det

        /* table sys_user_akses_det */
        Schema::create('sys_user_akses_det', function(Blueprint $table){
            // $table->integer('f_user', false, true);
            $table->string('f_user', 16);
            $table->integer('f_app', false, true);
            $table->string('akses', 16);

            $table->string('sys_user_created',16);
            $table->string('sys_user_updated',16)->nullable();
            $table->timestamp('sys_tgl_created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('sys_tgl_updated')->nullable();
            $table->enum('sys_status_aktif',['A','N'])->default('A');                                    
        });

        Schema::table('sys_user_akses_det', function(Blueprint $table){
            $table->foreign('f_app')->references('id_app')->on('sys_app_mst');
            $table->foreign('f_user')->references('f_nip_sys')->on('sys_user_mst');//references('id')->on('users');
        });
        // end of sys_user_akses_det

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sys_user_akses_det');
        Schema::drop('sys_group_akses_det');
        Schema::drop('sys_group_mst');
        Schema::drop('sys_app_mst');
        Schema::drop('sys_type_mst');
        Schema::drop('sys_status_mst');
        Schema::drop('sys_module_mst');
    }
}
