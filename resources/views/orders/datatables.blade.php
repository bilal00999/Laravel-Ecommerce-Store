@extends('admin.layout')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="admin-content">
    <div class="card" style="max-width: 1200px; margin: 0 auto;">
        <div class="card-body">
            <!-- Bulk Action Section -->
            <form id="bulkActionForm" action="{{ route('admin.orders.bulk-update') }}" method="POST" style="display: none;">
                @csrf
                <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        <strong id="selectedCount">0</strong> order(s) selected
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="bulkStatus" name="status" style="width: auto;">
                            <option value="">Select Status...</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-primary" id="bulkUpdateBtn">Update Selected</button>
                        <button type="button" class="btn btn-sm btn-secondary" id="clearSelectionBtn">Clear Selection</button>
                    </div>
                    <input type="hidden" id="orderIds" name="order_ids" value="[]">
                </div>
            </form>

            {{ $dataTable->table(['class' => 'table table-striped table-hover']) }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- JSZip (for Excel export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- pdfMake (for PDF export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.0/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.0/vfs_fonts.min.js"></script>

{{ $dataTable->scripts() }}

<script>
    $(document).ready(function() {
        // Wait for DataTable to be initialized
        setTimeout(function() {
            // Toggle bulk action section based on checkbox selection
            function updateBulkActionSection() {
                const selectedCheckboxes = $('input.order-checkbox:checked');
                const count = selectedCheckboxes.length;
                
                if (count > 0) {
                    $('#bulkActionForm').show();
                    $('#selectedCount').text(count);
                    
                    // Collect order IDs
                    const orderIds = selectedCheckboxes.map(function() {
                        return $(this).val();
                    }).get();
                    $('#orderIds').val(JSON.stringify(orderIds));
                } else {
                    $('#bulkActionForm').hide();
                }
            }

            // Listen to checkbox changes
            $(document).on('change', 'input.order-checkbox', function() {
                updateBulkActionSection();
            });

            // Bulk update button
            $('#bulkUpdateBtn').click(function() {
                const status = $('#bulkStatus').val();
                
                if (!status) {
                    alert('Please select a status to update');
                    return;
                }
                
                const count = $('input.order-checkbox:checked').length;
                if (confirm('Are you sure you want to update ' + count + ' order(s) to ' + status + '?')) {
                    $('#bulkActionForm').submit();
                }
            });

            // Clear selection button
            $('#clearSelectionBtn').click(function() {
                $('input.order-checkbox').prop('checked', false);
                updateBulkActionSection();
            });

            // Initialize on page load
            updateBulkActionSection();

            // Re-check checkboxes after DataTable redraws (search, sort, pagination)
            try {
                let table = $('#orders-table').DataTable();
                table.on('draw.dt', function() {
                    updateBulkActionSection();
                });
            } catch(e) {
                console.log('DataTable not yet initialized');
            }
        }, 500);
    });
</script>
@endsection

