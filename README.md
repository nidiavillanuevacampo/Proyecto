# Setup Instructions - Sistema de Gestión

## Prerequisites
- XAMPP installed and running
- Apache and MySQL services started

## Database Setup

### Step 1: Import Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click on "Import" tab
3. Click "Choose File" and select `database.sql`
4. Click "Go" to import

**OR** use command line:
```bash
mysql -u root -p < database.sql
```

### Step 2: Verify Database
1. In phpMyAdmin, you should see a database named `sistema_gestion`
2. Verify the following tables exist:
   - `usuarios`
   - `categorias`
   - `compras`
   - `ventas`

## Accessing the Application

### Compras (Purchases) Page
Navigate to: **http://localhost/Pruebas/compras.php**

### Ventas (Sales) Page
Navigate to: **http://localhost/Pruebas/ventas.php**

## Features

### Compras Page
- ✅ View all purchases in a paginated table
- ✅ Search purchases by description, category, or type
- ✅ Add new purchases via modal form
- ✅ Edit existing purchases
- ✅ Delete purchases with confirmation
- ✅ Automatic pagination (9 items per page)

### API Endpoints

#### Compras API (`api/compras.php`)
- `GET` - Fetch purchases with pagination and search
- `POST` - Create new purchase
- `PUT` - Update existing purchase
- `DELETE` - Delete purchase

#### Ventas API (`api/ventas.php`)
- `GET` - Fetch sales with pagination and search
- `POST` - Create new sale
- `PUT` - Update existing sale
- `DELETE` - Delete sale

## Testing the System

### 1. View Purchases
- Navigate to http://localhost/Pruebas/compras.php
- You should see sample data in the table

### 2. Add a Purchase
- Click the "Agregar" button
- Fill in the form:
  - **Fecha**: Select a date
  - **Descripción**: Enter description (e.g., "Papel higiénico")
  - **Monto**: Enter amount (e.g., 150.00)
  - **Tipo**: Select type (Diario, Semanal, or Mensual)
  - **Categoría**: Enter category (e.g., "Limpieza")
- Click "Agregar"
- The new purchase should appear in the table

### 3. Edit a Purchase
- Click the green edit icon (🖊️) on any row
- Modify the data in the modal
- Click "Actualizar"
- Changes should be reflected in the table

### 4. Delete a Purchase
- Click the red delete icon (🗑️) on any row
- Confirm the deletion
- The row should disappear from the table

### 5. Search
- Type in the search box (e.g., "Limpieza")
- The table will filter to show only matching results

### 6. Pagination
- Click the page numbers at the bottom
- Navigate between pages of results

## Troubleshooting

### Database Connection Error
- Verify MySQL is running in XAMPP
- Check database credentials in `config/database.php`
- Default credentials: username=`root`, password=`` (empty)

### API Not Working
- Ensure Apache is running
- Check browser console for errors (F12)
- Verify API files are in the correct location: `api/compras.php` and `api/ventas.php`

### Modal Not Opening
- Check browser console for JavaScript errors
- Ensure `compras.js` is loaded correctly
- Clear browser cache

## Next Steps

You now have a complete backend structure ready for expansion:

1. **Add Authentication**: Use the `usuarios` table to implement login/logout
2. **Connect Ventas Page**: Update `ventas.php` to use the API like `compras.php`
3. **Add Reports**: Create a reports page using the data
4. **Add Dashboard**: Create summary statistics and charts
5. **Add Categories Management**: CRUD interface for the `categorias` table

## File Structure
```
Pruebas/
├── api/
│   ├── compras.php      # Purchases API
│   └── ventas.php       # Sales API
├── config/
│   └── database.php     # Database connection
├── compras.php          # Purchases page
├── compras.css          # Purchases styles
├── compras.js           # Purchases JavaScript
├── ventas.php           # Sales page (existing)
├── login.php            # Login page (existing)
└── database.sql         # Database schema
```
