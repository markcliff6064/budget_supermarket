/**
 * Budget Supermarket Management System - Main JavaScript File
 * Contains all client-side functionality for the system
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality when DOM is loaded
    initTabSwitching();
    initFormValidation();
    initDeleteConfirmations();
    initRealTimeSearch();
    initPrintFunctions();
    initAutoFocus();
    initModalHandlers();
    initNotificationSystem();
});

/**
 * Initialize tab switching functionality for auth pages
 */
function initTabSwitching() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    if (tabButtons.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Hide all tabs
                const tabs = document.querySelectorAll('.auth-form');
                tabs.forEach(tab => tab.classList.remove('active'));
                
                // Deactivate all tab buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                
                // Show the selected tab and activate its button
                const tabName = this.getAttribute('onclick').match(/'(.*?)'/)[1];
                document.getElementById(tabName).classList.add('active');
                this.classList.add('active');
                
                e.preventDefault();
            });
        });
    }
}

/**
 * Initialize form validation for all forms
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredInputs = this.querySelectorAll('[required]');
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#e74c3c';
                    isValid = false;
                    
                    // Add error message if not already present
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
                        const errorMsg = document.createElement('span');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'This field is required';
                        errorMsg.style.color = '#e74c3c';
                        errorMsg.style.fontSize = '0.8em';
                        errorMsg.style.display = 'block';
                        input.insertAdjacentElement('afterend', errorMsg);
                    }
                } else {
                    input.style.borderColor = '';
                    const errorMsg = input.nextElementSibling;
                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill all required fields', 'error');
            }
        });
    });
}

/**
 * Initialize confirmation dialogs for delete actions
 */
function initDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.delete-btn, a[onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to perform this action?')) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Initialize real-time search functionality
 */
function initRealTimeSearch() {
    const searchInputs = document.querySelectorAll('input[type="text"][name="search"]');
    searchInputs.forEach(input => {
        let timeoutId;
        
        input.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            }, 500);
        });
    });
}

/**
 * Initialize print-related functionality
 */
function initPrintFunctions() {
    // Print buttons
    const printButtons = document.querySelectorAll('.print-btn, [onclick*="print"]');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            window.print();
        });
    });
    
    // Print receipt specifically
    const printReceiptButtons = document.querySelectorAll('[onclick*="printReceipt"]');
    printReceiptButtons.forEach(button => {
        button.addEventListener('click', function() {
            const saleId = this.dataset.saleId;
            printReceipt(saleId);
        });
    });
}

/**
 * Print a receipt for a specific sale
 * @param {string} saleId - The ID of the sale to print
 */
function printReceipt(saleId) {
    const url = `../cashier/receipt.php?sale_id=${saleId}`;
    const printWindow = window.open(url, '_blank');
    
    // Ensure the window is loaded before printing
    printWindow.onload = function() {
        printWindow.print();
    };
}

/**
 * Initialize auto-focus for first input in forms
 */
function initAutoFocus() {
    const forms = document.querySelectorAll('form');
    for (let i = 0; i < forms.length; i++) {
        const inputs = forms[i].querySelectorAll('input, select, textarea');
        if (inputs.length > 0 && inputs[0].type !== 'hidden') {
            inputs[0].focus();
            break;
        }
    }
}

/**
 * Initialize modal/dialog handlers
 */
function initModalHandlers() {
    // Modal open buttons
    const modalOpenButtons = document.querySelectorAll('[data-modal-target]');
    modalOpenButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
            }
        });
    });
    
    // Modal close buttons
    const modalCloseButtons = document.querySelectorAll('.modal .close, .modal .cancel-btn');
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
}

/**
 * Initialize notification system
 */
function initNotificationSystem() {
    // Check for session messages/errors
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const message = urlParams.get('message');
    
    if (error) {
        showNotification(error, 'error');
    }
    
    if (message) {
        showNotification(message, 'success');
    }
}

/**
 * Show a notification message
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, warning)
 */
function showNotification(message, type = 'success') {
    // Create notification element if it doesn't exist
    let notification = document.getElementById('global-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'global-notification';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.padding = '15px';
        notification.style.borderRadius = '5px';
        notification.style.color = 'white';
        notification.style.zIndex = '1000';
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.5s';
        document.body.appendChild(notification);
    }
    
    // Set notification content and style
    notification.textContent = message;
    notification.className = `notification ${type}`;
    
    // Show notification
    setTimeout(() => {
        notification.style.opacity = '1';
        
        // Hide after 5 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
        }, 5000);
    }, 100);
}

/**
 * Toggle loading state for buttons
 * @param {HTMLElement} button - The button element
 * @param {boolean} isLoading - Whether to show loading state
 */
function toggleLoading(button, isLoading) {
    if (isLoading) {
        const spinner = document.createElement('span');
        spinner.className = 'spinner';
        button.disabled = true;
        button.insertAdjacentElement('afterbegin', spinner);
        button.dataset.originalText = button.textContent;
        button.innerHTML = button.innerHTML.replace(button.textContent, ' Processing...');
    } else {
        button.disabled = false;
        const spinner = button.querySelector('.spinner');
        if (spinner) {
            spinner.remove();
        }
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
        }
    }
}

/**
 * Format price with currency
 * @param {number} amount - The amount to format
 * @returns {string} Formatted price string
 */
function formatPrice(amount) {
    return `ksh.${parseFloat(amount).toFixed(2)}`;
}

/**
 * Handle AJAX form submissions
 * @param {HTMLElement} form - The form element
 * @param {function} callback - Callback function after success
 */
function submitFormAjax(form, callback) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('[type="submit"]');
    
    toggleLoading(submitButton, true);
    
    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        toggleLoading(submitButton, false);
        
        if (data.success) {
            showNotification(data.message || 'Operation successful', 'success');
            if (typeof callback === 'function') {
                callback(data);
            }
        } else {
            showNotification(data.error || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        toggleLoading(submitButton, false);
        showNotification('Network error occurred', 'error');
        console.error('Error:', error);
    });
}

// Global utility functions
window.bsms = {
    showNotification,
    formatPrice,
    submitFormAjax,
    printReceipt
};