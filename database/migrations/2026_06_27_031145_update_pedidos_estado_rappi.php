<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado_nuevo')->default('recibido')->after('estado');
        });

        DB::statement("UPDATE pedidos SET estado_nuevo = CASE
            WHEN estado IN ('pendiente', 'confirmado') THEN 'recibido'
            WHEN estado = 'preparando'                 THEN 'preparando'
            WHEN estado = 'listo'                      THEN 'en_camino'
            WHEN estado = 'entregado'                  THEN 'entregado'
            WHEN estado = 'cancelado'                  THEN 'cancelado'
            ELSE 'recibido'
        END");

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->renameColumn('estado_nuevo', 'estado');
        });
    }

    public function down(): void {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado_old')->default('pendiente')->after('estado');
        });

        DB::statement("UPDATE pedidos SET estado_old = CASE
            WHEN estado = 'recibido'    THEN 'pendiente'
            WHEN estado = 'preparando'  THEN 'preparando'
            WHEN estado = 'en_camino'   THEN 'listo'
            WHEN estado = 'entregado'   THEN 'entregado'
            WHEN estado = 'cancelado'   THEN 'cancelado'
            ELSE 'pendiente'
        END");

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->renameColumn('estado_old', 'estado');
        });
    }
};