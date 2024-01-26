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

    public function search_invoice(Request $request) {
        $search = $request->get('s');
        if ($search != null){
            $invoices = Invoice::with('customer')
                ->where('id', 'LIKE', "%$search%")
                ->get();

            // SELECT invoices.*, customers.*
            // FROM invoices
            // LEFT JOIN customers ON invoices.customer_id = customers.id
            // WHERE invoices.id LIKE '%your_search_value%';
                
            return response()->json([
                'invoices' => $invoices
            ], 200);
        } else {
            return $this->get_all_invoice();
        }
    }
}
