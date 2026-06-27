<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('cliente_nombre');
            $table->string('cliente_telefono');
            $table->string('cliente_email')->nullable();
            $table->text('direccion_entrega')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery', 8, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', [
                                        'recibido',
                                        'preparando',
                                        'en_camino',
                                        'entregado',
                                        'cancelado'
                                    ])->default('recibido');
            $table->text('notas')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->timestamps();
        });
    }
 
    public function down(): void {
        Schema::dropIfExists('pedidos');
    }
};
 