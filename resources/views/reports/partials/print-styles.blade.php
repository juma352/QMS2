@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area, .printable-area * {
            visibility: visible;
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .btn, footer, .sidebar, .topbar, .d-flex.justify-content-between {
            display: none !important;
        }
        a {
            text-decoration: none;
            color: #000 !important;
        }
        .table {
            font-size: 12px;
        }
        .report-section h2 {
            margin-top: 2rem;
        }
    }
</style>
@endsection