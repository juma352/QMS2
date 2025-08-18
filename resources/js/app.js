require('./bootstrap');
require('./checklist-auto-populate');

// Global openModal function for reports
window.openModal = function(name, route) {
    const modalLabel = document.getElementById('modalLabel');
    const dateForm = document.getElementById('dateForm');
    
    if (modalLabel && dateForm) {
        modalLabel.textContent = `Generate ${name}`;
        dateForm.action = route;
        
        // Show the modal
        const dateModal = new bootstrap.Modal(document.getElementById('dateModal'));
        dateModal.show();
    } else {
        console.error('Modal elements not found. Make sure the modal HTML is included.');
    }
};

// Global submitForm function for reports
window.submitForm = function() {
    const dateForm = document.getElementById('dateForm');
    if (dateForm) {
        const from = dateForm.querySelector('input[name="from"]').value;
        const to = dateForm.querySelector('input[name="to"]').value;
        
        if (from && to) {
            dateForm.submit();
        } else {
            alert('Please select both "From" and "To" dates.');
        }
    }
};
