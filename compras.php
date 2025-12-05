<?php
// Fetch purchases from database
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Get current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9; // Items per page
$offset = ($page - 1) * $limit;

// Get search parameter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch purchases
$query = "SELECT * FROM compras 
          WHERE descripcion LIKE :search 
             OR categoria LIKE :search 
             OR tipo LIKE :search
          ORDER BY fecha DESC, id DESC 
          LIMIT :limit OFFSET :offset";

$stmt = $db->prepare($query);
$searchParam = "%{$search}%";
$stmt->bindParam(':search', $searchParam);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$compras = $stmt->fetchAll();

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM compras 
              WHERE descripcion LIKE :search 
                 OR categoria LIKE :search 
                 OR tipo LIKE :search";
$countStmt = $db->prepare($countQuery);
$countStmt->bindParam(':search', $searchParam);
$countStmt->execute();
$total = $countStmt->fetch()['total'];
$totalPages = ceil($total / $limit);

$usuario = "Nidia Villanueva";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras - Sistema de Gestión</title>
    <link rel="stylesheet" href="compras.css">
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
            <a href="ventas.php" class="nav-item">
                <span class="nav-icon">🛒</span>
                <span class="nav-text">Ventas</span>
            </a>
            <a href="compras.php" class="nav-item active">
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
                <h1 class="page-title">Compras</h1>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Buscar..." class="search-input" value="<?php echo htmlspecialchars($search); ?>">
                        <span class="search-icon">🔍</span>
                    </div>
                    <button class="btn-primary" id="btnAgregar">Agregar</button>
                </div>
            </header>

            <!-- Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compras as $compra): ?>
                        <tr class="table-row" data-id="<?php echo $compra['id']; ?>">
                            <td class="fecha-cell"><?php echo date('d/m/Y', strtotime($compra['fecha'])); ?></td>
                            <td class="monto-cell">$<?php echo number_format($compra['monto'], 2); ?></td>
                            <td><?php echo htmlspecialchars($compra['categoria'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($compra['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($compra['tipo']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-edit" title="Modificar" data-id="<?php echo $compra['id']; ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-action btn-delete" title="Eliminar" data-id="<?php echo $compra['id']; ?>">
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
                <button class="pagination-btn" <?php echo $page <= 1 ? 'disabled' : ''; ?> onclick="window.location.href='?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>'">‹</button>
                <?php for ($i = 1; $i <= min($totalPages, 3); $i++): ?>
                <button class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>" onclick="window.location.href='?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>'"><?php echo $i; ?></button>
                <?php endfor; ?>
                <button class="pagination-btn" <?php echo $page >= $totalPages ? 'disabled' : ''; ?> onclick="window.location.href='?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>'">›</button>
            </div>
        </div>
    </main>

    <!-- Modal Agregar/Editar Compra -->
    <div id="modalCompra" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Agregar Compra</h2>
                <button class="modal-close" id="closeModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <form id="formCompra" class="modal-form">
                <input type="hidden" id="compraId" name="id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-input" placeholder="Ej: Jabon, limpiador, cloro" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="monto" class="form-label">Monto:</label>
                        <div class="input-with-icon">
                            <span class="input-icon">$</span>
                            <input type="number" id="monto" name="monto" class="form-input input-with-prefix" placeholder="0.00" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select id="tipo" name="tipo" class="form-input" required>
                            <option value="">Seleccionar...</option>
                            <option value="Diario">Diario</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Mensual">Mensual</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria" class="form-label">Categoría:</label>
                        <input type="text" id="categoria" name="categoria" class="form-input" placeholder="Ej: Limpieza, Mat. Prima">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelarModal">Cancelar</button>
                    <button type="submit" class="btn-submit">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="compras.js"></script>
</body>
</html>
