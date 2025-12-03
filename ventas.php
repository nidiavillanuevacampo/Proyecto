<?php
// Datos de ejemplo para las ventas
$ventas = [
    ['fecha' => '29/10/2025', 'nota' => '-', 'cantidad' => 1, 'descripcion' => 'Pizza Pepperoni', 'importe' => 150.00],
    ['fecha' => '29/10/2025', 'nota' => '2', 'cantidad' => 1, 'descripcion' => 'Pizza Hawaiana', 'importe' => 160.00],
    ['fecha' => '29/10/2025', 'nota' => '3', 'cantidad' => 2, 'descripcion' => 'Pizza Mexicana', 'importe' => 180.00],
    ['fecha' => '29/10/2025', 'nota' => '4', 'cantidad' => 2, 'descripcion' => 'Pizza Pepperoni', 'importe' => 300.00],
    ['fecha' => '29/10/2025', 'nota' => '5', 'cantidad' => 1, 'descripcion' => 'Pizza Pastor', 'importe' => 200.00],
    ['fecha' => '30/10/2025', 'nota' => '1', 'cantidad' => 1, 'descripcion' => 'Pizza Pepperoni', 'importe' => 150.00],
    ['fecha' => '30/10/2025', 'nota' => '2', 'cantidad' => 2, 'descripcion' => 'Pizza Mexicana', 'importe' => 360.00],
    ['fecha' => '30/10/2025', 'nota' => '3', 'cantidad' => 1, 'descripcion' => 'Pizza Mexicana', 'importe' => 180.00],
    ['fecha' => '30/10/2025', 'nota' => '4', 'cantidad' => 1, 'descripcion' => 'Pizza Pepperoni', 'importe' => 150.00],
];

$usuario = "Nidia Villanueva";
$paginaActual = 2;
$totalPaginas = 3;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - Sistema de Gestión</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="user-profile">
            <div class="avatar">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($usuario); ?>&background=6366f1&color=fff" alt="Avatar">
            </div>
            <span class="user-name"><?php echo htmlspecialchars($usuario); ?></span>
        </div>

        <nav class="nav-menu">
            <a href="dashboard.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="ventas.php" class="nav-item active">
                <span class="nav-icon">🛒</span>
                <span class="nav-text">Ventas</span>
            </a>
            <a href="compras.php" class="nav-item">
                <span class="nav-icon">📦</span>
                <span class="nav-text">Compras</span>
            </a>
            <a href="reportes.php" class="nav-item">
                <span class="nav-icon">📈</span>
                <span class="nav-text">Reportes</span>
            </a>
            <a href="usuarios.php" class="nav-item">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Usuarios</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="configuracion.php" class="footer-link">
                <span class="footer-icon">⚙️</span>
                <span>Configuración</span>
            </a>
            <a href="logout.php" class="footer-link">
                <span class="footer-icon">🚪</span>
                <span>Salir</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Header -->
            <header class="page-header">
                <h1 class="page-title">Ventas</h1>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" placeholder="Buscar..." class="search-input">
                        <span class="search-icon">🔍</span>
                    </div>
                    <button class="btn-primary">Agregar</button>
                </div>
            </header>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nota</th>
                            <th>Cant</th>
                            <th>Descripción</th>
                            <th>Importe</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $index => $venta): ?>
                        <tr class="table-row">
                            <td class="fecha-cell"><?php echo htmlspecialchars($venta['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($venta['nota']); ?></td>
                            <td><?php echo htmlspecialchars($venta['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($venta['descripcion']); ?></td>
                            <td class="importe-cell">$<?php echo number_format($venta['importe'], 2); ?></td>
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" <?php echo $paginaActual <= 1 ? 'disabled' : ''; ?>>‹</button>
                <button class="pagination-btn">1</button>
                <button class="pagination-btn active">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn" <?php echo $paginaActual >= $totalPaginas ? 'disabled' : ''; ?>>›</button>
            </div>
        </div>
    </main>

    <!-- Modal Agregar Venta -->
    <div id="modalAgregarVenta" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Agregar Venta</h2>
                <button class="modal-close" id="closeModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <form id="formAgregarVenta" class="modal-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-input" placeholder="Ej: Pizza Pepperoni" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nota" class="form-label">Nota:</label>
                        <input type="text" id="nota" name="nota" class="form-input" placeholder="Nota opcional">
                    </div>
                    
                    <div class="form-group">
                        <label for="importe" class="form-label">Importe:</label>
                        <div class="input-with-icon">
                            <span class="input-icon">$</span>
                            <input type="number" id="importe" name="importe" class="form-input input-with-prefix" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-input" placeholder="1" min="1" value="1" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelarModal">Cancelar</button>
                    <button type="submit" class="btn-submit">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
