class Comprar 
{
    constructor(){
        this.front = 0;
        this.itemsProductos = [];
    }
    
    setProducto(producto){
        statusExiste = 0;
        this.itemsProductos.forEach(existe => {
            if (existe.id == producto.id) {
                statusExiste = 1;
            }
        });

        if (statusExiste == 0) {
            this.itemsProductos[this.front] = {
                id:producto.id,
                clave:producto.clave,
                producto:producto.descripcion,
                unidad_compra: producto.unidad_compra,
                cantidad:0,
                costo:0
            }
            this.front++;
            return true;
        }else{
            return false;
        }
    }
    
    popProducto(id){
        this.itemsProductos.forEach((producto,index) => {
            if(producto.id == id){
                this.itemsProductos.splice(index,1);
            }
        });
        this.front--;
        return true;
    }

    setCantidad(producto_id,cantidad){
        this.itemsProductos.forEach(producto => {
            if (producto.id == producto_id) {
                producto.cantidad = cantidad;
            }
        });
    }

    setCosto(producto_id,costo){
        this.itemsProductos.forEach(producto => {
            if (producto.id == producto_id) {
                producto.costo = costo;
            }
        });
    }
    
    verificaCantidades(){
        statusCantidades = true;
        this.itemsProductos.forEach(producto => {
            if (producto.cantidad == 0) {
                statusCantidades = false;
            }
        });
        return statusCantidades;
    }
    
    getProductosComprar(){
        return this.itemsProductos;
    }
    
    getTotalProductos(){
        return this.front;
    }

    getTotalPagar() {
        let total = 0;
        this.itemsProductos.forEach(item => {
            if (item.costo != "" && item.cantidad != "") {
                total += item.cantidad * item.costo;
            }
        });

        return total;
    }
}