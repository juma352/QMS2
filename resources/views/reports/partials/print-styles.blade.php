@section('styles')
<style>
    /* This block contains styles that are ONLY applied when the page is printed. */
    @media print {
        /* Hide all elements on the page by default */
        body * {
            visibility: hidden;
        }
        
        /* Make only the designated printable area and its children visible */
        .printable-area, .printable-area * {
            visibility: visible;
        }

        /* Ensure the printable area takes up the full page width */
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Remove borders and shadows from cards for a cleaner print look */
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        /* Hide non-essential elements like buttons, sidebars, etc. */
        .btn, footer, .sidebar, .topbar, .d-flex.justify-content-between {
            display: none !important;
        }
        
        /* Ensure links are black for better readability on paper */
        a {
            text-decoration: none;
            color: #000 !important;
        }
        
        /* Use a smaller font size for tables in the print view */
        .table {
            font-size: 12px;
        }
    }
</style>
@endsection
