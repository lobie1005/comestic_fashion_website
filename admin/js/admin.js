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

    // Image preview for product form
    $('#productImage').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

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
        
        $.post('/admin/update_order_status.php', {
            order_id: orderId,
            status: status
        })
        .done(function(response) {
            if (response.success) {
                alert('Order status updated successfully!');
            } else {
                alert('Error updating order status');
            }
        })
        .fail(function() {
            alert('Error updating order status');
        });
    });

    // Form validation
    $('form').on('submit', function(e) {
        const requiredFields = $(this).find('[required]');
        let isValid = true;

        requiredFields.each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });

    // Clear form validation on input
    $('input, select, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
