/**
 * Checklist Auto-Population Script
 * Automatically populates next questions when a user fills one field
 */

document.addEventListener('DOMContentLoaded', function() {
    const checklistForm = document.querySelector('.checklist-form');
    
    if (!checklistForm) return;

    // Handle input changes for auto-population
    checklistForm.addEventListener('input', function(e) {
        const input = e.target;
        const questionItem = input.closest('.checklist-item');
        
        if (!questionItem) return;

        // Auto-populate next question based on current input
        autoPopulateNextQuestion(questionItem, input);
        
        // Show next question if current is filled
        showNextQuestion(questionItem);
    });

    // Handle select changes
    checklistForm.addEventListener('change', function(e) {
        const select = e.target;
        const questionItem = select.closest('.checklist-item');
        
        if (!questionItem) return;

        // Auto-populate next question based on selection
        autoPopulateNextQuestion(questionItem, select);
        
        // Show next question if current is filled
        showNextQuestion(questionItem);
    });

    /**
     * Auto-populate next question based on current input
     */
    function autoPopulateNextQuestion(currentItem, input) {
        const nextItem = currentItem.nextElementSibling;
        
        if (!nextItem || !nextItem.classList.contains('checklist-item')) return;

        const nextInput = nextItem.querySelector('input, select, textarea');
        if (!nextInput) return;

        // Skip if next input already has value
        if (nextInput.value && nextInput.value.trim() !== '') return;

        // Logic for auto-population based on input type
        const inputType = input.type;
        const inputValue = input.value.trim();

        if (inputType === 'text' || inputType === 'textarea') {
            // For text inputs, use context-based population
            if (inputValue.length > 0) {
                // Example: If current is "Department Name", next might be "Department Code"
                if (input.placeholder && input.placeholder.toLowerCase().includes('name')) {
                    nextInput.value = generateDepartmentCode(inputValue);
                }
            }
        } else if (inputType === 'select-one') {
            // For select dropdowns, populate based on selection
            const selectedOption = input.options[input.selectedIndex];
            if (selectedOption && selectedOption.value) {
                populateNextFromSelect(nextInput, selectedOption.text);
            }
        }
        
        // Trigger change event for any listeners
        nextInput.dispatchEvent(new Event('change', { bubbles: true }));
    }

    /**
     * Show next question with animation
     */
    function showNextQuestion(currentItem) {
        const nextItem = currentItem.nextElementSibling;
        
        if (!nextItem || !nextItem.classList.contains('checklist-item')) return;

        const currentInput = currentItem.querySelector('input, select, textarea');
        if (!currentInput || !currentInput.value || currentInput.value.trim() === '') return;

        // Show next question with smooth transition
        nextItem.style.display = 'block';
        nextItem.style.opacity = '0';
        nextItem.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            nextItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            nextItem.style.opacity = '1';
            nextItem.style.transform = 'translateY(0)';
        }, 100);

        // Focus on next input
        const nextInput = nextItem.querySelector('input, select, textarea');
        if (nextInput) {
            nextInput.focus();
        }
    }

    /**
     * Generate department code from name
     */
    function generateDepartmentCode(name) {
        return name.toUpperCase()
            .split(' ')
            .map(word => word.charAt(0))
            .join('')
            .substring(0, 3);
    }

    /**
     * Populate next input based on select choice
     */
    function populateNextFromSelect(nextInput, selectedText) {
        // Example logic - customize based on your needs
        if (selectedText.toLowerCase().includes('high')) {
            nextInput.value = 'Priority: High';
        } else if (selectedText.toLowerCase().includes('medium')) {
            nextInput.value = 'Priority: Medium';
        } else if (selectedText.toLowerCase().includes('low')) {
            nextInput.value = 'Priority: Low';
        }
    }

    /**
     * Initialize progressive disclosure
     */
    function initializeProgressiveDisclosure() {
        const items = checklistForm.querySelectorAll('.checklist-item');
        
        items.forEach((item, index) => {
            if (index > 0) {
                const prevItem = items[index - 1];
                const prevInput = prevItem.querySelector('input, select, textarea');
                
                if (!prevInput || !prevInput.value || prevInput.value.trim() === '') {
                    item.style.display = 'none';
                }
            }
        });
    }

    // Initialize on page load
    initializeProgressiveDisclosure();
});
