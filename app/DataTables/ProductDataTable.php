<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'products.action')
            ->setRowId('id')
            ->editColumn('price', function ($row) {
                return '$' . number_format($row->price, 2);
            })
            ->editColumn('stock', function ($row) {
                return $row->stock > 0 
                    ? '<span class="badge bg-success">In Stock</span>' 
                    : '<span class="badge bg-danger">Out of Stock</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('M d, Y');
            })
            ->rawColumns(['stock', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with('category');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.products.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('create'),
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('category_id')
                ->title('Category')
                ->render('function(data, type, row) { return row.category.name || "N/A"; }'),
            Column::make('price'),
            Column::make('stock'),
            Column::make('created_at')
                ->title('Created'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
