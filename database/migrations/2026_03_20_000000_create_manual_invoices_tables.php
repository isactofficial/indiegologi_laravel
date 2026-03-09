<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Manual Invoices table
        Schema::create('manual_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('counseling_date')->nullable();
            $table->string('package')->nullable();
            $table->string('session_type')->default('Offline');
            
            // Invoice details
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['Unpaid', 'Paid', 'Overdue'])->default('Unpaid');
            $table->enum('payment_type', ['Transfer', 'Cash', 'QRIS', 'Kartu Kredit'])->default('Transfer');
            
            // Company info (stored for flexibility)
            $table->string('company_name')->default('INDIEGOLOGI');
            $table->string('company_email')->default('temancerita@indiegologi.com');
            $table->string('company_phone')->default('+62 822-2095-5595');
            $table->string('company_website')->default('indiegologi.com');
            
            // Payment info
            $table->string('bank_name')->default('Bank SMBC Indonesia');
            $table->string('bank_account')->default('90110023186');
            $table->string('account_name')->default('Artwira Mahatavirya Satyagasty');
            $table->string('confirm_number')->default('0822 2095 5595');
            
            // Signature
            $table->string('signed_by')->default('Vernandika Stanley Hansen');
            $table->string('signed_title')->default('Indiegologi Team');
            
            // Totals
            $table->decimal('subtotal_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Manual Invoice Items table
        Schema::create('manual_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_invoice_id')->constrained('manual_invoices')->onDelete('cascade');
            $table->string('description');
            $table->string('subtitle')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->boolean('is_addon')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_invoice_items');
        Schema::dropIfExists('manual_invoices');
    }
};

