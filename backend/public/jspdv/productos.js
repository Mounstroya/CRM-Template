class Productos{
    constructor(){
        this.itemsProductos = [];
        this.front = 0;
    }
    setProducto(producto){
        this.itemsProductos[this.front] = {
            id:producto.id,
            clave:producto.clave,
            descripcion: producto.descripcion,
            unidad_compra: producto.unidad_compra.unidad_compra
        }
        this.front++;
        return true;
    }
    getProductos(){
        return this.itemsProductos;
    }
    getProducto(id){
        let seleccionado = {};
        this.itemsProductos.forEach(producto => {
            if (producto.id == id) {
                seleccionado = {
                    id:producto.id,
                    clave: producto.clave,
                    descripcion:producto.descripcion,
                    unidad_compra: producto.unidad_compra
                }
            }
        });
        return seleccionado;
    }
    getTotalProductos(){
        return this.front;
    }
}