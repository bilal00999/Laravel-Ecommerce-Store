<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'orders.action')
            ->setRowId('id')
            ->editColumn('customer_name', function ($row) {
                return $row->user->name ?? 'Guest';
            })
            ->editColumn('total', function ($row) {
                return '$' . number_format($row->total, 2);
            })
            ->editColumn('status', function ($row) {
                $statusColors = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                ];
                $color = $statusColors[$row->status] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('M d, Y H:i');
            })
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()->with('user');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('customer_name')
                ->title('Customer'),
            Column::make('total')
                ->title('Amount'),
            Column::make('status'),
            Column::make('created_at')
                ->title('Order Date'),
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
        return 'Orders_' . date('YmdHis');
    }
}
