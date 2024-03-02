<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_venta', function (Blueprint $table) {
            // Eliminar la restricción de clave externa existente
            $table->dropForeign(['producto_id']);

            // Volver a definir la restricción de clave externa con onDelete('cascade')
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_venta', function (Blueprint $table) {
            // Eliminar la restricción de clave externa que agregamos en el método up()
            $table->dropForeign(['producto_id']);

            // Volver a agregar la restricción de clave externa con el comportamiento predeterminado (restrict)
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos');
        });
    }
}
