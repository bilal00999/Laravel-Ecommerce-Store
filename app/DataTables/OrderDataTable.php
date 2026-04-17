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
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="order-checkbox" value="' . $row->id . '">';
            })
            ->addColumn('customer_name', function ($row) {
                return $row->user ? $row->user->name : 'Guest';
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.orders.show', $row->id);
                return '<div style="display: flex; gap: 5px; justify-content: center;">
                    <a href="' . $detailUrl . '" class="btn btn-sm btn-info" title="View Details">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>';
            })
            ->setRowId('id')
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
            ->rawColumns(['checkbox', 'status', 'action']);
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
            ->minifiedAjax(route('admin.orders.datatable'))
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
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
            Column::computed('checkbox')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(50)
                ->addClass('text-center'),
            Column::make('id')
                ->searchable(false),
            Column::computed('customer_name')
                ->title('Customer'),
            Column::make('total')
                ->title('Amount'),
            Column::make('status')
                ->searchable(true),
            Column::make('created_at')
                ->title('Order Date'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
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
