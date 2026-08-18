class Proveedores{
    constructor(){
        this.itemsProveedores=[];
        this.front=0;
        this.end=0;
    }

    addProveedor(proveedor){
        this.itemsProveedores[this.front] = {
            'id':proveedor.id,
            'proveedor':proveedor.nombre,
            'representante':proveedor.representante,
            'status' : proveedor.status
        }
        this.front++;
    }

    getProveedores(){
        return this.itemsProveedores;
    }

    getTotalProveedores(){
        return this.front;
    }

    changeStatus(id,status){
        this.itemsProveedores.forEach(proveedor => {
            if (proveedor.id == id) {
                proveedor.status = status;
            }
        });
    }
}