let itemsCarrito = [];
let itemsProductos = [];
let aux_id;
let aux_producto;
let aux_precio;
let aux_total_pagar = 0;
let aux_existencia = 0;
let aux_unidad_venta = "";
let aux_servicio = 0;
let aux_clave = "";
let aux_sim = 0;
/**
 * ventaTipo
 * 0 = De contado
 * 1 = Credito
 */
class PDV{
    constructor(){
        this.frontCarrito = 0;
        this.endCarrito = 0;
        this.totalCart = 0;
        this.ventaTipo = 0;
    }

    setItemsProductos(productos){
        itemsProductos = productos;
        console.log(itemsProductos);
    }

    getItemsProductos(){
        return itemsProductos;
    }

    getTipoVenta() {
        return this.ventaTipo;
    }

    setTipoVenta(tipo) {
        this.ventaTipo = tipo;
    }

    addCarrito(id, nivel){
        let existencia = true;

        itemsProductos.forEach(producto => {

            if (producto.id == id) {
                itemsCarrito.forEach(agregado => {
                    if (producto.id == agregado.id) {
                        existencia = false;
                    }
                });

                if (existencia) {
                    aux_existencia = producto.stock * producto.factor;
                    
                    // Construyendo dinámicamente el nombre de la clave precio_venta
                    const precioKey = `precio_${nivel}`;
                
                    itemsCarrito[this.frontCarrito] = {
                        'id':id,
                        'existencia':aux_existencia,
                        'clave':producto.clave,
                        'producto':producto.descripcion,
                        'cantidad':1,
                        'precio_venta':producto[precioKey],
                        'precio_compra':producto.precio_compra_promedio,
                        'factor':producto.factor,
                        'status_neto': producto.status_neto,
                        'unidad_venta': producto.servicio === 1 ? 'SERVICIO' : producto.unidad_venta,
                        'servicio': producto.servicio,
                        'sim': producto.sim,
                    }
                    this.frontCarrito++;
                    this.totalCart++;
                    this.aux_id = producto.id;
                    this.aux_producto = producto.descripcion;
                    this.aux_precio = producto[precioKey];
                    this.aux_unidad_venta = producto.servicio === 1 ? 'SERVICIO' : producto.unidad_venta,
                    this.aux_servicio = producto.servicio;
                    this.aux_clave = producto.clave;
                    this.aux_sim = producto.sim;
                    aux_total_pagar += parseFloat(producto[precioKey])*1;
                }
            }
        });

        if (existencia) {
            return {
                'id': this.aux_id,
                'item': this.frontCarrito,
                'clave': this.aux_clave,
                'producto': this.aux_producto,
                'precio_venta': this.aux_precio,
                'cantidad': 1,
                'total_pagar': aux_total_pagar,
                'existencia': aux_existencia,
                'unidad_venta': this.aux_unidad_venta,
                'servicio': this.aux_servicio,
                'sim': this.aux_sim
            };
        }else{
            return false;
        }
    }

    changePrecioCarrito(nivel) {
        
        // Construyendo dinámicamente el nombre de la clave precio_venta
        const precioKey = `precio_${nivel}`;
        aux_total_pagar = 0;

        itemsProductos.forEach(producto => {
            itemsCarrito.forEach(productoCarrito => {
                if (productoCarrito.id == producto.id) {
                    productoCarrito.precio_venta = producto[precioKey];
                    
                    aux_total_pagar += parseFloat(producto[precioKey]) * parseFloat(productoCarrito.cantidad);
                }
            });
        });

    }

    getTotalPagar() {
        return aux_total_pagar;
    }

    popCarrito(id){
        itemsCarrito.forEach((producto,index) => {
            if (producto.id == id) {
                aux_total_pagar -= producto.precio_venta*producto.cantidad;
                itemsCarrito.splice(index,1);
            }
        });
        this.frontCarrito--;
        return [true, aux_total_pagar];
    }
    changePrecioVenta(id,precio_venta){
        itemsCarrito.forEach((producto) => {
            if (producto.id == id) {
                producto.precio_venta = precio_venta;
                aux_total_pagar = (producto.precio_venta * producto.cantidad);
            }
        });
        return aux_total_pagar;
    }
    
    addCantidad(id,cantidad){
        itemsCarrito.forEach((producto) => {
            if (producto.id == id) {
                aux_total_pagar -= (producto.cantidad * producto.precio_venta);
                // Asegurar que cantidad sea un número decimal
                cantidad = parseFloat(cantidad);
                aux_total_pagar += (cantidad * producto.precio_venta);
                producto.cantidad = cantidad;
            }
        });
        return aux_total_pagar;
    }

    getItemsCarrito(){
        return itemsCarrito;
    }

    setItemsCarrito(carrito) {
        itemsCarrito = carrito;
        console.log(itemsCarrito);
        this.frontCarrito = carrito.length;
        aux_total_pagar = carrito.reduce((total, producto) => total + (producto.precio_venta * producto.cantidad), 0);
    }

    setVenta(){
        itemsCarrito.forEach(carrito => {
            itemsProductos.forEach(producto => {
                if (carrito.id == producto.id) {
                    producto.stock = (((producto.stock*producto.factor)-carrito.cantidad)/producto.factor);
                }
            });
        });
        this.emptyCarrito();
    }
    emptyCarrito(){
        this.frontCarrito = 0;
        itemsCarrito = [];
        aux_total_pagar = 0;
    }
}