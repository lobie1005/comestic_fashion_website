</div><!-- End Main Content Container -->

<!-- Scripts -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- Custom Scripts -->
<script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
    // Initialize DataTables
    $(document).ready(function() {
        // Add active class to current nav item
        const currentPath = window.location.pathname;
        $('.nav-link').each(function() {
            if ($(this).attr('href') === currentPath) {
                $(this).addClass('active');
            }
        });

        // Initialize DataTables for tables
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search..."
                }
            });
        }

        // Delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const deleteUrl = $(this).attr('href');
            
            if (confirm('Are you sure you want to delete this item?')) {
                window.location.href = deleteUrl;
            }
        });

        // Order status update
        $('.order-status-select').on('change', function() {
            const orderId = $(this).data('order-id');
            const status = $(this).val();
            
            $.post(BASE_URL + '/admin/update_order_status.php', {
                order_id: orderId,
                status: status
            }, function(response) {
                if (response.success) {
                    alert('Order status updated successfully!');
                } else {
                    alert('Error updating order status: ' + response.error);
                }
            });
        });
    });
</script>
</body>
</html>