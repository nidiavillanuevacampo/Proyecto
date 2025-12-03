// Funcionalidad de búsqueda
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');
    const tableRows = document.querySelectorAll('.data-table tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.3s ease-out';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Confirmación para eliminar
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const confirmed = confirm('¿Estás seguro de que deseas eliminar este registro?');
            if (confirmed) {
                // Aquí iría la lógica para eliminar
                const row = this.closest('tr');
                row.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    row.remove();
                }, 300);
            }
        });
    });

    // Funcionalidad de editar
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Aquí iría la lógica para editar
            alert('Función de edición - Aquí se abriría un modal o formulario de edición');
        });
    });

    // Modal functionality
    const modal = document.getElementById('modalAgregarVenta');
    const addButton = document.querySelector('.btn-primary');
    const closeModalBtn = document.getElementById('closeModal');
    const cancelModalBtn = document.getElementById('cancelarModal');
    const modalOverlay = document.querySelector('.modal-overlay');
    const formAgregarVenta = document.getElementById('formAgregarVenta');

    // Función para abrir el modal
    function openModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Set today's date as default
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha').value = today;
    }

    // Función para cerrar el modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        formAgregarVenta.reset();
    }

    // Event listeners para abrir/cerrar modal
    if (addButton) {
        addButton.addEventListener('click', openModal);
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', closeModal);
    }

    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeModal);
    }

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Manejar el envío del formulario
    if (formAgregarVenta) {
        formAgregarVenta.addEventListener('submit', function (e) {
            e.preventDefault();

            // Obtener los valores del formulario
            const fecha = document.getElementById('fecha').value;
            const descripcion = document.getElementById('descripcion').value;
            const nota = document.getElementById('nota').value || '-';
            const importe = parseFloat(document.getElementById('importe').value);
            const cantidad = parseInt(document.getElementById('cantidad').value);

            // Formatear la fecha
            const fechaObj = new Date(fecha + 'T00:00:00');
            const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            // Crear nueva fila
            const tbody = document.querySelector('.data-table tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'table-row';
            newRow.style.animation = 'fadeIn 0.3s ease-out';

            newRow.innerHTML = `
                <td class="fecha-cell">${fechaFormateada}</td>
                <td>${nota}</td>
                <td>${cantidad}</td>
                <td>${descripcion}</td>
                <td class="importe-cell">$${importe.toFixed(2)}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-edit" title="Modificar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button class="btn-action btn-delete" title="Eliminar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            `;

            // Insertar la nueva fila al principio de la tabla
            tbody.insertBefore(newRow, tbody.firstChild);

            // Agregar event listeners a los nuevos botones
            const editBtn = newRow.querySelector('.btn-edit');
            const deleteBtn = newRow.querySelector('.btn-delete');

            editBtn.addEventListener('click', function () {
                alert('Función de edición - Aquí se abriría un modal o formulario de edición');
            });

            deleteBtn.addEventListener('click', function () {
                const confirmed = confirm('¿Estás seguro de que deseas eliminar este registro?');
                if (confirmed) {
                    newRow.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => {
                        newRow.remove();
                    }, 300);
                }
            });

            // Cerrar el modal y resetear el formulario
            closeModal();

            // Mostrar mensaje de éxito
            showSuccessMessage('Venta agregada exitosamente');
        });
    }

    // Función para mostrar mensaje de éxito
    function showSuccessMessage(message) {
        const successMsg = document.createElement('div');
        successMsg.className = 'success-message';
        successMsg.textContent = message;
        successMsg.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideInRight 0.3s ease-out;
            font-weight: 600;
        `;

        document.body.appendChild(successMsg);

        setTimeout(() => {
            successMsg.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                successMsg.remove();
            }, 300);
        }, 3000);
    }


    // Animación de entrada para las filas
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
    });

    // Responsive sidebar toggle (para móviles)
    const createMobileToggle = () => {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            if (!document.querySelector('.mobile-toggle')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'mobile-toggle';
                toggleBtn.innerHTML = '☰';
                toggleBtn.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 20px;
                    z-index: 101;
                    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                    color: white;
                    border: none;
                    padding: 12px 16px;
                    border-radius: 8px;
                    font-size: 1.5rem;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
                `;

                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                });

                document.body.appendChild(toggleBtn);
            }
        }
    };

    createMobileToggle();
    window.addEventListener('resize', createMobileToggle);
});

// Animación fadeOut para eliminar
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }
`;
document.head.appendChild(style);
