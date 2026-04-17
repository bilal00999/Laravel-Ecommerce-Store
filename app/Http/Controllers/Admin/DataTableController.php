<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\ProductDataTable;
use App\DataTables\OrderDataTable;

class DataTableController extends Controller
{
    /**
     * Display Products DataTable.
     *
     * @param ProductDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function products(ProductDataTable $dataTable)
    {
        return $dataTable->render('products.datatables');
    }

    /**
     * Display Orders DataTable.
     *
     * @param OrderDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function orders(OrderDataTable $dataTable)
    {
        return $dataTable->render('orders.datatables');
    }
}
