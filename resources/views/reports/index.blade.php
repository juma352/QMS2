@extends('layouts.dashboard')

@section('title', 'Reports Hub')

@section('styles')
<style>
:root {
    --primary: #7c3aed; /* Bright purple */
    --secondary: #06b6d4; /* Cyan */
    --accent: #ea580c; /* Vivid orange */
    --text-dark: #0f172a; /* Deep slate */
    --text-light: #475569; /* Slate gray */
    --bg: #eff6ff; /* Light blue background */
    --radius: 0.8rem;
    --transition: all 0.3s ease;
    --gradient: linear-gradient(135deg, #7c3aed, #c084fc);
}
.report-hub {
    padding: 2rem;
    background: var(--bg);
    min-height: calc(100vh - 65px - 60px);
}
.header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    background: var(--gradient);
    padding: 2rem;
    border-radius: var(--radius);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.header h1 {
    font-size: 2rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
.header p {
    font-size: 1rem;
    opacity: 0.95;
}
.filters {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}
.filters input, .filters select {
    padding: 0.7rem 1.5rem;
    border: 1px solid #bfdbfe;
    border-radius: var(--radius);
    font-size: 0.95rem;
    background: #ffffff;
    transition: var(--transition);
}
.filters input:focus, .filters select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.2);
}
.filters button {
    background: var(--secondary);
    color: #ffffff;
    border: none;
    border-radius: var(--radius);
    padding: 0.7rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}
.filters button:hover {
    background: #0891b2;
    transform: translateY(-3px);
}
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}
.card {
    border: none;
    border-radius: var(--radius);
    background: #ffffff;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: var(--transition);
    animation: slideIn 0.5s ease-out forwards;
}
.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
.card:nth-child(5) { animation-delay: 0.5s; }
.card:nth-child(6) { animation-delay: 0.6s; }
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}
.card-body {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.card-icon {
    font-size: 2rem;
    margin-bottom: 1.25rem;
    background: linear-gradient(45deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.card-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
}
.card-text {
    flex-grow: 1;
    color: var(--text-light);
    font-size: 0.95rem;
    margin-bottom: 1.75rem;
}
.btn-generate {
    align-self: flex-start;
    background: var(--gradient);
    color: #ffffff;
    padding: 0.7rem 1.75rem;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.btn-generate::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    left: -100%;
    top: 0;
    transition: left 0.3s ease;
}
.btn-generate:hover::after {
    left: 0;
}
.btn-generate:hover {
    background: linear-gradient(135deg, #6b21a8, #a78bfa);
    transform: translateY(-3px);
}
.modal-content {
    border-radius: var(--radius);
    border: none;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
}
.modal-header {
    background: var(--gradient);
    color: #ffffff;
    border-bottom: none;
}
.modal-body {
    background: #ffffff;
    padding: 1.5rem;
}
.modal-footer .btn-primary {
    background: var(--secondary);
    border: none;
}
.modal-footer .btn-primary:hover {
    background: #0891b2;
}
@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
@media (max-width: 768px) {
    .report-hub { padding: 1.5rem; }
    .header { flex-direction: column; align-items: flex-start; gap: 1.5rem; padding: 1.5rem; }
    .filters { width: 100%; }
    .card-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<div class="report-hub">
    <div class="header">
        <div>
            <h1>Reports Hub</h1>
            <p>Generate detailed analytics instantly.</p>
        </div>
        <div class="filters">
            <input id="search" placeholder="Search reports..." type="text">
            <select id="type">
                <option value="">All Types</option>
                <option value="Audit">Audit</option>
                <option value="Standards">Standards</option>
                <option value="Programs">Programs</option>
                <option value="Staff">Staff</option>
                <option value="CQI">CQI</option>
                <option value="Checklist">Checklist</option>
            </select>
            <button id="apply">Filter</button>
        </div>
    </div>

    <div class="row card-grid" id="cards">
        @forelse($reports as $report)
        <div class="col-xl-4 col-md-6 mb-4 card-wrapper" data-type="{{ explode(' ', $report['name'])[0] }}">
            <div class="card h-100">
                <div class="card-body">
                    <i class="{{ $report['icon'] }} card-icon"></i>
                    <h5 class="card-title">{{ $report['name'] }}</h5>
                    <p class="card-text">{{ $report['desc'] }}</p>
                    <button class="btn-generate" onclick="openModal('{{ $report['name'] }}', '{{ route($report['route']) }}')">
                        <i class="fas fa-calendar-alt me-1"></i>Choose Dates
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center" style="color: var(--text-light)">No reports available.</div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="dateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="dateForm" method="GET" action="">
                    <div class="mb-3">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Run</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('search');
    const type = document.getElementById('type');
    const apply = document.getElementById('apply');
    const cards = document.querySelectorAll('.card-wrapper');
    const dateModal = new bootstrap.Modal(document.getElementById('dateModal'));
    const dateForm = document.getElementById('dateForm');
    const modalLabel = document.getElementById('modalLabel');

    function applyFilters() {
        const q = search.value.toLowerCase();
        const t = type.value;
        cards.forEach(card => {
            const name = card.querySelector('.card-title').textContent.toLowerCase();
            const type = card.dataset.type;
            card.style.display = (!q || name.includes(q)) && (!t || type === t) ? '' : 'none';
        });
    }

    apply.addEventListener('click', applyFilters);
    search.addEventListener('input', applyFilters);
    type.addEventListener('change', applyFilters);

    window.openModal = (name, route) => {
        modalLabel.textContent = `Generate ${name}`;
        dateForm.action = route;
        dateModal.show();
    };

    window.submitForm = () => {
        const from = dateForm.querySelector('input[name="from"]').value;
        const to = dateForm.querySelector('input[name="to"]').value;
        if (from && to) {
            dateForm.submit();
        } else {
            alert('Please select both "From" and "To" dates.');
        }
    };
});
</script>
@endsection