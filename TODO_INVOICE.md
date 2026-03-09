# Manual Invoice Feature - TODO List

## Phase 1: Database & Model
- [x] 1.1 Create migration for manual_invoices table
- [x] 1.2 Create migration for manual_invoice_items table  
- [x] 1.3 Create ManualInvoice model
- [ ] 1.4 Run migrations

## Phase 2: Controller
- [x] 2.1 Create ManualInvoiceController with CRUD methods
- [x] 2.2 Add store, update, delete methods

## Phase 3: Views
- [x] 3.1 Create admin/manual-invoices/index.blade.php (list)
- [x] 3.2 Create admin/manual-invoices/create.blade.php (form builder)
- [x] 3.3 Create admin/manual-invoices/edit.blade.php (edit)
- [x] 3.4 Create admin/manual-invoices/pdf.blade.php (PDF template)

## Phase 4: Routes & Menu
- [x] 4.1 Add routes in web.php
- [x] 4.2 Add menu item in admin.blade.php

## Phase 5: Testing
- [ ] 5.1 Run migration: php artisan migrate
- [ ] 5.2 Test creating manual invoice
- [ ] 5.3 Test PDF download

