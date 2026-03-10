# TODO: Implementasi Discount Satuan pada Invoice

## Manual Invoice System - ✅ COMPLETED

### 1. Database Migration - ✅ DONE
- [x] Tambah kolom `discount_amount` di tabel `manual_invoice_items`

### 2. Model Updates - ✅ DONE
- [x] Update `ManualInvoiceItem.php` - tambahkan field `discount_amount` ke fillable dan casts
- [x] Update `ManualInvoice.php` - update method `calculateTotals()` untuk menghitung total discount dari items

### 3. Controller Updates - ✅ DONE
- [x] Update `ManualInvoiceController.php` - update store() dan update() untuk handle item discount
- [x] Update logic perhitungan total (subtotal - item discounts = total)

### 4. View - Create Form - ✅ DONE
- [x] Tambah input field discount per unit di setiap item row
- [x] Update JavaScript calculateTotals() untuk menghitung discount per item

### 5. View - Edit Form - ✅ DONE  
- [x] Tambah input field discount per unit dengan existing value
- [x] Update JavaScript calculateTotals()

### 6. View - Show (Preview) - ✅ DONE
- [x] Tambah kolom Discount di service table
- [x] Tampilkan Total Discount dengan warna #FFC107 (30% opacity) = rgba(255, 193, 7, 0.3)
- [x] Update perhitungan total

### 7. View - PDF - ✅ DONE
- [x] Tambah kolom Discount di service table
- [x] Tampilkan Total Discount dengan warna #FFC107 (30% opacity) = rgba(255, 193, 7, 0.3)

---

## Regular Invoice System (Consultation Booking) - ✅ ALREADY EXISTS

### 8. Existing Discount Field - ✅ ALREADY EXISTS
- [x] Field `discount_amount_at_booking` di booking_service table SUDAH ADA
- [x] Field `auto_discount_amount` di invoice table SUDAH ADA

### 9. View - invoice/show.blade.php - ✅ ALREADY EXISTS
- [x] Discount sudah ditampilkan via auto_discount_amount

### 10. View - invoice/pdf.blade.php - ✅ ALREADY EXISTS
- [x] Discount sudah ditampilkan via auto_discount_amount

---

## Color Reference
- Discount color: #FFC107 dengan opacity 30% = rgba(255, 193, 7, 0.3)
- CSS: `background-color: rgba(255, 193, 7, 0.3);`

