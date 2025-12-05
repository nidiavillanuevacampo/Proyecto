/**
 * Compras JavaScript
 * Handles modal interactions, AJAX requests, and CRUD operations
 */

// DOM Elements
const modal = document.getElementById('modalCompra');
const btnAgregar = document.getElementById('btnAgregar');
const closeModal = document.getElementById('closeModal');
const cancelarModal = document.getElementById('cancelarModal');
const formCompra = document.getElementById('formCompra');
const modalTitle = document.getElementById('modalTitle');
const searchInput = document.getElementById('searchInput');

// Current edit mode
let isEditMode = false;
let currentEditId = null;

// ============================================
// Modal Functions
// ============================================

function openModal(editMode = false, compraData = null) {
    isEditMode = editMode;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    if (editMode && compraData) {
        modalTitle.textContent = 'Editar Compra';
        document.querySelector('.btn-submit').textContent = 'Actualizar';
        populateForm(compraData);
    } else {
        modalTitle.textContent = 'Agregar Compra';
        document.querySelector('.btn-submit').textContent = 'Agregar';
        formCompra.reset();
        // Set today's date as default
        document.getElementById('fecha').valueAsDate = new Date();
    }
}

function closeModalFunc() {
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    formCompra.reset();
    isEditMode = false;
    currentEditId = null;
}

function populateForm(data) {
    currentEditId = data.id;
    document.getElementById('compraId').value = data.id;
    document.getElementById('fecha').value = data.fecha;
    document.getElementById('monto').value = data.monto;
    document.getElementById('categoria').value = data.categoria || '';
    document.getElementById('descripcion').value = data.descripcion;
    document.getElementById('tipo').value = data.tipo;
}

// ============================================
// Event Listeners
// ============================================

// Open modal for adding
btnAgregar.addEventListener('click', () => {
    openModal(false);
});

// Close modal
closeModal.addEventListener('click', closeModalFunc);
cancelarModal.addEventListener('click', closeModalFunc);

// Close modal when clicking overlay
modal.addEventListener('click', (e) => {
    if (e.target === modal || e.target.classList.contains('modal-overlay')) {
        closeModalFunc();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
        closeModalFunc();
    }
});

// ============================================
// Form Submission
// ============================================

formCompra.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = {
        fecha: document.getElementById('fecha').value,
        monto: parseFloat(document.getElementById('monto').value),
        categoria: document.getElementById('categoria').value,
        descripcion: document.getElementById('descripcion').value,
        tipo: document.getElementById('tipo').value
    };

    try {
        let response;

        if (isEditMode) {
            // Update existing purchase
            formData.id = currentEditId;
            response = await fetch('api/compras.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
        } else {
            // Create new purchase
            response = await fetch('api/compras.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
        }

        const result = await response.json();

        if (result.success) {
            // Show success message
            showNotification(result.message, 'success');

            // Close modal
            closeModalFunc();

            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            showNotification(result.message || 'Error al procesar la solicitud', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error de conexión. Por favor, intente nuevamente.', 'error');
    }
});

// ============================================
// Edit and Delete Buttons
// ============================================

// Edit button handler
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', async function () {
        const compraId = this.getAttribute('data-id');

        try {
            // Fetch purchase data
            const response = await fetch(`api/compras.php?id=${compraId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                const compra = result.data[0];
                openModal(true, compra);
            } else {
                showNotification('No se pudo cargar la información de la compra', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al cargar los datos', 'error');
        }
    });
});

// Delete button handler
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', async function () {
        const compraId = this.getAttribute('data-id');

        if (confirm('¿Está seguro de que desea eliminar esta compra?')) {
            try {
                const response = await fetch('api/compras.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: compraId })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');

                    // Remove row from table with animation
                    const row = this.closest('tr');
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';

                    setTimeout(() => {
                        row.remove();
                    }, 300);
                } else {
                    showNotification(result.message || 'Error al eliminar la compra', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error de conexión. Por favor, intente nuevamente.', 'error');
            }
        }
    });
});

// ============================================
// Search Functionality
// ============================================

let searchTimeout;
searchInput.addEventListener('input', function () {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        const searchValue = this.value;
        window.location.href = `?search=${encodeURIComponent(searchValue)}`;
    }, 500); // Wait 500ms after user stops typing
});

// ============================================
// Notification System
// ============================================

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#6366f1'};
        color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        animation: slideInRight 0.3s ease;
        font-size: 0.875rem;
        font-weight: 500;
    `;

    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
    `;
    document.head.appendChild(style);

    // Add to DOM
    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// ============================================
// Initialize
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    console.log('Compras page initialized');

    // Add smooth transitions to table rows
    document.querySelectorAll('.table-row').forEach(row => {
        row.style.transition = 'all 0.3s ease';
    });
});
