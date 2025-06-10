<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom yang akan dipindahkan ke order_items
            $table->dropForeign(['product_id']); // Hapus foreign key dulu
            $table->dropColumn('product_id');
            $table->dropColumn('quantity');

            // Tambahkan kolom total_amount ke tabel orders
            $table->decimal('total_amount', 10, 2)->after('user_id')->default(0);

            // Jika kamu ingin menambahkan order_number/invoice_number unik per transaksi
            $table->string('order_number')->unique()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback: tambahkan kembali kolom yang dihapus
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->integer('quantity')->default(1);
            $table->foreign('product_id')->references('id')->on('products'); // Tambahkan kembali foreign key

            // Hapus kolom yang baru ditambahkan
            $table->dropColumn('total_amount');
            $table->dropColumn('order_number');
        });
    }
};