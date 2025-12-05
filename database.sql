-- Database Schema for Sistema de Gestión
-- Created for XAMPP/MySQL environment

-- Create database
CREATE DATABASE IF NOT EXISTS sistema_gestion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_gestion;

-- ============================================
-- Table: usuarios
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: categorias
-- ============================================
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    tipo ENUM('compra', 'venta', 'ambos') DEFAULT 'ambos',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: compras
-- ============================================
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    categoria_id INT DEFAULT NULL,
    categoria VARCHAR(50) DEFAULT NULL,
    descripcion TEXT NOT NULL,
    tipo ENUM('Diario', 'Semanal', 'Mensual') NOT NULL,
    usuario_id INT DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_fecha (fecha),
    INDEX idx_categoria (categoria),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: ventas
-- ============================================
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    nota VARCHAR(50) DEFAULT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    descripcion TEXT NOT NULL,
    importe DECIMAL(10, 2) NOT NULL,
    usuario_id INT DEFAULT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_fecha (fecha),
    INDEX idx_nota (nota)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Sample Data
-- ============================================

-- Insert default user
INSERT INTO usuarios (nombre, email, password, avatar_url) VALUES
('Nidia Villanueva', 'nidia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'https://ui-avatars.com/api/?name=Nidia+Villanueva&background=6366f1&color=fff');

-- Insert categories
INSERT INTO categorias (nombre, descripcion, tipo) VALUES
('Limpieza', 'Productos de limpieza', 'compra'),
('Mat. Prima', 'Materia prima', 'compra'),
('Basura', 'Servicio de basura', 'compra'),
('Desechables', 'Productos desechables', 'compra'),
('Renta', 'Pago de renta', 'compra'),
('Sueldo', 'Pago de sueldos', 'compra'),
('Alimentos', 'Productos alimenticios', 'ambos');

-- Insert sample purchases (matching the screenshot)
INSERT INTO compras (fecha, monto, categoria_id, categoria, descripcion, tipo, usuario_id) VALUES
('2025-10-29', 325.00, 1, 'Limpieza', 'Jabon, limpiador, cloro', 'Semanal', 1),
('2025-10-30', 115.00, 2, 'Mat. Prima', 'Verduras', 'Diario', 1),
('2025-11-01', 86.00, 3, 'Basura', 'Mensualidad servicio', 'Mensual', 1),
('2025-11-02', 200.00, 4, 'Desechables', 'Platos, Vasos, Tenedores', 'Diario', 1),
('2025-11-02', 3000.00, 5, 'Renta', 'Pago Noviembre', 'Mensual', 1),
('2025-11-03', 4500.00, 2, 'Mat. Prima', 'Quesos, Pepperoni, Jamón', 'Diario', 1),
('2025-11-03', 760.00, 4, 'Desechables', '100 Cajas', 'Diario', 1),
('2025-11-04', 86.00, 2, 'Mat. Prima', 'Verduras', 'Diario', 1),
('2025-11-05', 1200.00, 6, 'Sueldo', 'Sueldo Cajera', 'Semanal', 1);

-- Insert sample sales (matching ventas.php)
INSERT INTO ventas (fecha, nota, cantidad, descripcion, importe, usuario_id) VALUES
('2025-10-29', '-', 1, 'Pizza Pepperoni', 150.00, 1),
('2025-10-29', '2', 1, 'Pizza Hawaiana', 160.00, 1),
('2025-10-29', '3', 2, 'Pizza Mexicana', 180.00, 1),
('2025-10-29', '4', 2, 'Pizza Pepperoni', 300.00, 1),
('2025-10-29', '5', 1, 'Pizza Pastor', 200.00, 1),
('2025-10-30', '1', 1, 'Pizza Pepperoni', 150.00, 1),
('2025-10-30', '2', 2, 'Pizza Mexicana', 360.00, 1),
('2025-10-30', '3', 1, 'Pizza Mexicana', 180.00, 1),
('2025-10-30', '4', 1, 'Pizza Pepperoni', 150.00, 1);
