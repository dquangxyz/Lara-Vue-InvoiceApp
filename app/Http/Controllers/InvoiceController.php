<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function get_all_invoice() {
        // $invoices = Invoice::all(); // this means SELECT * FROM invoices

        $invoices = Invoice::with('customer')->orderBy('id','DESC')->get(); 
        // this means
        // SELECT invoices.*, customers.*
        // FROM invoices
        // LEFT JOIN customers ON invoices.customer_id = customers.id
        // ORDER BY invoices.id DESC;
        
        return response()->json([
            'invoices' => $invoices
        ], 200);
    }
}
