<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('manual_invoice_items', function (Blueprint $table) {
            $table->decimal('discount_amount', 15, 2)->default(0)->after('unit_price');
        });
    }

    public function down()
    {
        Schema::table('manual_invoice_items', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};

