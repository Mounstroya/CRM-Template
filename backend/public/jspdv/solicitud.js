class Solicitud {

    constructor() {
        this.productos = [];
        this.solicitar = [];
    }

    nuevaSolicitud() {
        this.solicitar = [];
    }

    setProductos(productos) {
        this.productos = productos;
    }

    // Vacía this.solicitar
    setEmptySolicitud() {
        this.solicitar = [];
    }

    getProductos() {
        //return this.productos;
        return this.solicitar;
    }

    addProducto(producto) {
        // Verificar si el producto ya está en la lista
        const productoExistente = this.solicitar.find(item => item.id == producto.id);
        
        if (!productoExistente) {
            // Si el producto no está en la lista, agregarlo
            this.solicitar.push({
                id: producto.id,
                descripcion: producto.descripcion,
                stock: 0,
                existencia: producto.stock,
                unidad_compra: producto.unidad_compra.unidad_compra
            });
            return true;
        } else {
            // Si el producto ya está en la lista, puedes optar por mostrar un mensaje de error o simplemente no hacer nada
            return false;
        }
    }
    
    deleteProducto(producto) {
        // Elimina el producto por su id
        //this.solicitar.splice(this.solicitar.find(producto => producto.id == producto_id), 1);
        this.solicitar.splice(this.solicitar.indexOf(producto), 1);
    }
    
    updateProducto(producto) {
        this.productos.splice(this.productos.indexOf(producto), 1, producto);
    }
    
    getProducto(id) {
        return this.productos.find(producto => producto.id == id);
    }

    getProductoSolicitud(id) {
        return this.solicitar.find(producto => producto.id == id);
    }

    actualizarStock(productId, newQuantity) {
        const producto = this.solicitar.find(producto => producto.id == productId);
        if (producto) {
            producto.stock = parseInt(newQuantity); // Restamos la nueva cantidad del stock actual
        } else {
            console.log("Producto no encontrado");
        }
    }
}