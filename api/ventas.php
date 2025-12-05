<?php
/**
 * API Endpoint for Ventas (Sales)
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
            // Get all sales with optional search and pagination
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $offset = ($page - 1) * $limit;

            $query = "SELECT * FROM ventas 
                      WHERE descripcion LIKE :search 
                         OR nota LIKE :search 
                      ORDER BY fecha DESC, id DESC 
                      LIMIT :limit OFFSET :offset";

            $stmt = $db->prepare($query);
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $ventas = $stmt->fetchAll();

            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM ventas 
                          WHERE descripcion LIKE :search 
                             OR nota LIKE :search";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':search', $searchParam);
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'data' => $ventas,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
            break;

        case 'POST':
            // Create new sale
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->fecha) && !empty($data->descripcion) && !empty($data->importe)) {
                $query = "INSERT INTO ventas (fecha, nota, cantidad, descripcion, importe, usuario_id) 
                         VALUES (:fecha, :nota, :cantidad, :descripcion, :importe, :usuario_id)";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':fecha', $data->fecha);
                $nota = isset($data->nota) ? $data->nota : '-';
                $stmt->bindParam(':nota', $nota);
                $cantidad = isset($data->cantidad) ? $data->cantidad : 1;
                $stmt->bindParam(':cantidad', $cantidad);
                $stmt->bindParam(':descripcion', $data->descripcion);
                $stmt->bindParam(':importe', $data->importe);
                $usuario_id = isset($data->usuario_id) ? $data->usuario_id : 1;
                $stmt->bindParam(':usuario_id', $usuario_id);

                if ($stmt->execute()) {
                    http_response_code(201);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Venta creada exitosamente',
                        'id' => $db->lastInsertId()
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al crear la venta']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
            break;

        case 'PUT':
            // Update sale
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->id) && !empty($data->fecha) && !empty($data->descripcion) && !empty($data->importe)) {
                $query = "UPDATE ventas 
                         SET fecha = :fecha, 
                             nota = :nota, 
                             cantidad = :cantidad, 
                             descripcion = :descripcion, 
                             importe = :importe 
                         WHERE id = :id";

                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $data->id);
                $stmt->bindParam(':fecha', $data->fecha);
                $nota = isset($data->nota) ? $data->nota : '-';
                $stmt->bindParam(':nota', $nota);
                $cantidad = isset($data->cantidad) ? $data->cantidad : 1;
                $stmt->bindParam(':cantidad', $cantidad);
                $stmt->bindParam(':descripcion', $data->descripcion);
                $stmt->bindParam(':importe', $data->importe);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Venta actualizada exitosamente']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar la venta']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
            break;

        case 'DELETE':
            // Delete sale
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->id)) {
                $query = "DELETE FROM ventas WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $data->id);

                if ($stmt->execute()) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Venta eliminada exitosamente']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al eliminar la venta']);
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
