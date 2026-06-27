<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pedido_id')
                ->constrained('pedidos')
                ->onDelete('cascade');

            $table->string('codigo_operacion');

            $table->decimal('monto', 8, 2);

            $table->string('metodo')->default('yape');

            $table->string('estado')->default('verificando');

            $table->string('comprobante_imagen')->nullable();

            $table->text('notas')->nullable();

            $table->timestamp('fecha_pago')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};