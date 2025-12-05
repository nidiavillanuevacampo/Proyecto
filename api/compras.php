<?php
/**
 * API Endpoint for Compras (Purchases)
 * Handles CRUD operations
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            // Get all purchases with optional search and pagination
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $offset = ($page - 1) * $limit;

            $query = "SELECT c.*, cat.nombre as categoria_nombre 
                      FROM compras c 
                      LEFT JOIN categorias cat ON c.categoria_id = cat.id 
                      WHERE c.descripcion LIKE :search 
                         OR c.categoria LIKE :search 
                         OR c.tipo LIKE :search
                      ORDER BY c.fecha DESC, c.id DESC 
                      LIMIT :limit OFFSET :offset";

            $stmt = $db->prepare($query);
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $compras = $stmt->fetchAll();

            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM compras 
                          WHERE descripcion LIKE :search 
                             OR categoria LIKE :search 
                             OR tipo LIKE :search";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':search', $searchParam);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $compras,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
            break;

        case 'POST':
            // Create new purchase
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->fecha) && !empty($data->monto) && !empty($data->descripcion) && !empty($data->tipo)) {
                $query = "INSERT INTO compras (fecha, monto, categoria, descripcion, tipo, usuario_id) 
                         VALUES (:fecha, :monto, :categoria, :descripcion, :tipo, :usuario_id)";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':fecha', $data->fecha);
                $stmt->bindParam(':monto', $data->monto);
                $stmt->bindParam(':categoria', $data->categoria);
                $stmt->bindParam(':descripcion', $data->descripcion);
                $stmt->bindParam(':tipo', $data->tipo);
                $usuario_id = isset($data->usuario_id) ? $data->usuario_id : 1;
                $stmt->bindParam(':usuario_id', $usuario_id);

                if ($stmt->execute()) {
                    http_response_code(201);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Compra creada exitosamente',
                        'id' => $db->lastInsertId()
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al crear la compra']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
            break;

        case 'PUT':
            // Update purchase
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->id) && !empty($data->fecha) && !empty($data->monto) && !empty($data->descripcion) && !empty($data->tipo)) {
                $query = "UPDATE compras 
                         SET fecha = :fecha, 
                             monto = :monto, 
                             categoria = :categoria, 
                             descripcion = :descripcion, 
                             tipo = :tipo 
                         WHERE id = :id";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $data->id);
                $stmt->bindParam(':fecha', $data->fecha);
                $stmt->bindParam(':monto', $data->monto);
                $stmt->bindParam(':categoria', $data->categoria);
                $stmt->bindParam(':descripcion', $data->descripcion);
                $stmt->bindParam(':tipo', $data->tipo);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Compra actualizada exitosamente']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar la compra']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
            break;

        case 'DELETE':
            // Delete purchase
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->id)) {
                $query = "DELETE FROM compras WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $data->id);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Compra eliminada exitosamente']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al eliminar la compra']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            break;
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
