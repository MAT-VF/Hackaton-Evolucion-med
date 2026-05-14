CREATE DATABASE farmacia_db;
USE farmacia_db;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    usuario VARCHAR(100),
    contraseña VARCHAR(255),
    rol VARCHAR(50),
    estado BOOLEAN DEFAULT 1
);

CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(100),
    telefono VARCHAR(30),
    direccion VARCHAR(150),
    correo VARCHAR(100)
);

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100),
    descripcion TEXT
);

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio_compra DECIMAL(10,2),
    precio_venta DECIMAL(10,2),
    stock INT DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    fecha_vencimiento DATE,
    codigo_barras VARCHAR(100),
    lote VARCHAR(100),
    id_categoria INT,
    id_proveedor INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

CREATE TABLE compras (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    fecha_compra DATE,
    total DECIMAL(10,2),
    id_usuario INT,
    id_proveedor INT,
    estado_compra VARCHAR(50),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor)
);

CREATE TABLE detalle_compra (
    id_detalle_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_compra INT,
    id_producto INT,
    cantidad INT,
    precio DECIMAL(10,2),
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_compra) REFERENCES compras(id_compra),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE movimientos_inventario (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    tipo_movimiento VARCHAR(50),
    cantidad INT,
    fecha DATE,
    id_producto INT,
    observacion TEXT,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

INSERT INTO usuarios(nombre, usuario, contraseña, rol)
VALUES('Administrador', 'admin', '123456', 'ADMIN');

INSERT INTO proveedores(nombre_empresa, telefono, direccion, correo)
VALUES
('Tijera de Mayo', 'Cirugía', 120, 180, 10, 3, '2027-01-01', '10003', 'LT003', 1, 1);