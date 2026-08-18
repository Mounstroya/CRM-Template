let statusExiste = 0;
let statusCantidades = true;
class Pedidos{
    constructor(){
        this.itemsProductos = [];
        this.front=0;
        //PEDIDOS ACTIVOS
        this.itemsPedidosActivos = [];
        this.frontPA = 0;
        //PEDIDOS SURTIDOS
        this.itemsPedidosSurtidos = [];
        this.frontPS = 0;
    }

    constructorArribo(){
        this.itemsArriboProductos = [];
        this.frontArribo = 0;
        this.arriboId = 0;
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
                cantidad:0
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
    getProductosRequisicion(){
        return this.itemsProductos;
    }
    getTotalProductos(){
        return this.front;
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
    /**MODULO DE PEDIDOS ACTIVOS */
    setPedidosActivos(pedido,url){
        let estado_p = this.defineEstadoPedido(pedido.status,pedido.id);
        let accion = this.defineAcciones(pedido.status, pedido.id, pedido.celular, url);
        this.itemsPedidosActivos[this.frontPA] = {
            id:pedido.id,
            proveedor:pedido.proveedor,
            no_requisicion:`<span class="badge badge-pill badge-dark">R-${pedido.no_requisicion}</span>`,
            acciones:accion,
            status:pedido.status,
            celular:pedido.celular,
            url:url,
            estado:estado_p
        }
        this.frontPA++;
    }
    getPedidosActivos(){
        return this.itemsPedidosActivos;
    }
    getTotalPA(){
        return this.frontPA;
    }
    /**MODULO DE PEDIDOS SURTIDOS */
    setPedidosSurtidos(pedido,url){
        let estado_p = this.defineEstadoPedido(pedido.status,pedido.id);
        let accion = this.defineAcciones(pedido.status, pedido.id, pedido.celular, url);
        this.itemsPedidosSurtidos[this.frontPS] = {
            id:pedido.id,
            proveedor:pedido.proveedor,
            no_requisicion:`<span class="badge badge-pill badge-dark">R-${pedido.no_requisicion}</span>`,
            acciones:accion,
            status:pedido.status,
            celular:pedido.celular,
            url:url,
            estado:estado_p
        }
        this.frontPS++;
    }
    getPedidosSurtidos(){
        return this.itemsPedidosSurtidos;
    }
    getTotalPS(){
        return this.frontPS;
    }
    changeAccionesPedido(status,id){
        let acciones;
        this.itemsPedidosActivos.forEach(pedido => {
            if (pedido.id == id) {
                acciones = this.defineAcciones(status, id, pedido.celular, pedido.url);
                pedido.status = status;
                pedido.acciones = acciones;
            }
        });
        return acciones;
    }
    changeEstadoPedido(status,id){
        let estado;
        this.itemsPedidosActivos.forEach(pedido => {
            if (pedido.id == id) {
                estado = this.defineEstadoPedido(status, id);
                pedido.estado = estado;
            }
        });
        return estado;
    }
    defineEstadoPedido(status,id){
        let estado_p = "";
        switch (parseInt(status)) {
            case 0:
                estado_p = `<span class="badge badge-primary" onclick="verRequisicion('${id}')" style="font-size: 18px;cursor:pointer"><img src="data:image/png;base64,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"/> COTIZANDO</span>`;
                break;
            case 1:
                estado_p = `<span class="badge badge-warning" onclick="verRequisicion('${id}')" style="font-size: 18px;cursor:pointer"><img src="data:image/png;base64,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"/> COTIZADO</span>`;
                break;
            case 2:
                estado_p = `<span class="badge badge-dark" onclick="verRequisicion('${id}')" style="font-size: 18px;cursor:pointer"><img src="data:image/png;base64,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"/> AUTORIZADO</span>`;
                break;
            case 3:
                estado_p = `<span class="badge badge-success" onclick="verRequisicion('${id}')" style="font-size: 18px;cursor:pointer"><img src="data:image/png;base64,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"/> SURTIDO</span>`;
                break;
            default:
                estado_p = `<span class="badge badge-danger">CANCELADO</span>`;
                break;
        }
        return estado_p;
    }
    defineAcciones(status,id,celular,url){
        let accion = ``;
        switch (parseInt(status)) {
            case 0:
                accion = `<a title="Enviar requisición al proveedor" href="https://wa.me/52${celular}?text=*Solicitud%20de%20cotización*%0AHola%20requiero%20de%20la%20siguiente%20mercancía%20que%20estás%20vendiendo%20URL:%0A%0A${url}/solicitud-de-requisicion/${id}" target="blank_"><img src="data:image/png;base64,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"/><a>
                        <span style="cursor:pointer" onclick="copyUrl('${url}/solicitud-de-requisicion/${id}')"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFb0lEQVR4nO2XbWxTVRjHq4nv8SXEl4hviZpoiHxQPxA/GI1+MepHTTQxMTFxQO+9Xe/aMhRZ3QTp1nvv+rKW3vbe89y2tltXxstiGA5xgQgYt8GQYkR0s8BIBCMBYS9uu8c8t9R03Wi7QvCDPMlJT3Jvz/ndc87zf/7HZPq/RLNDudO9Inb/fwYg18i3e6yas82u/SmyQLH5eJgI2LV2jyX66DUDcXHhh9vskE1siOnH9nTSi8NddDzbRUcH0rQnkqA+XpsQzPDqNQLRTn+lJuhEdjOdPDG3HelNUW8tTImc+tJVmdTLeW8RWBh0vg+3FoP0kiSdPJ6beGR/J90kxWm7K6YPdLfTyWwXHT8KdGhLmHqtZEzkI4uqhhBY0uhm4Q3siwz5xW1Wlxn95dpDxSCZ3hT11YGuOqOfiwy8E1ilne3ZCPrY4Y0UW7pF1b21WtOCIZzOzptzMOq7uCLYb2aUxU6n80a5Rr4p4IDhHZHEHJDEhtjb+TEka/zBgJ38ldkWNmAOb5NpcBUZqWJF4FeBIx/kIQqfiSwxa03RGTykhSBJV/yt4nGCDnD3BBUD5nifTH08ubhgGLdZXSYwRJjvWbAejg5t7zBATgykLwtCTfQG0kBG9sYjBszPO2QacJAzFUOIDHwqMmQDHtjLvYOpeupA2oDpVZPUb9N0Ty3opEEb626NvpAHSazT9ihrCD13IHdmdkUU2mZTOyqGEVh4WmDJZjenLikFMzqYNvQk2hSjKSFO/8hsoruiSao2kEweRF1D6JnvQgbIqW9DmE3TAhd9tiIQjyXyACppufdCq7Ufv9/STlPuOO0U4vTiSO4QH+pJ0aADDiXXw26lAAR/lY/JjNdKWipfFQbiAgMTEkOeK/WeZIEVEgezQE70p2mbDahkITqsnQvSZtc0XDHTQqL5UvqWrkFkX3tLbLIQJGAHulNWDHE7f3DjlYPkA3VE4oDBrPHwMOGr08Zxa0SWLA/Y1MEOl/Z3MUh/R05L8m10t0wjH10hSDOjLEZB0xpjM5i+pw6m6ejgJopnBLcm5VL180MhOn4sZoB4rTDl5Ynep+ZWZTAdpt1ehXpqybTPqjVWDeK6VGtQWfOChg2zJtUSo53NhCIIfnn2mxAK2JTIaixWZL+NfBFaDYfletIvWcg6aaX6eFUQhSC9BbXmZH/a0JFo41wQPw8zEgdc1RMuBCSzM0W9PJlGdS0GabMRfKaLZvJ6ubFFVn1PZIlPNEeeLAvitsXuCDggi35ksgDEw5NpN0te8VhB/33/bBA8rH2KQgN20l52fIvyjMiA5Gbg5bIwaBXRoeWNUaYABJ8rDTC2MxyhB7vC1F8HtDeUqzN4WOV6OFJq7BZOe0JYSR4xVRroWdEq5o2RpwAEg3xClibWwWi8Cc63WkBHCITBrAmtJgOlxhZZ+FBkyAWJJS+WBfFy8bvQOGO2IExajE+6WZUvfg/TM1gPSbKWzOQFrdur0lYO1s83LoqmyJCl2G+tVZ5qrYV7KoBR7xM5+DeNo59Fz6FDmw8kskbV8xKPgoY6gtsw37hCjXyvwJCzaMzKQhR+AV4n0MUjTP/W5JTEwTA6tMuB4C8qq58nTfMVWswc/J9g0Z6vJNtmRdCudeB1whC4bBf9MgC4xyfR1/jqYJ9SBDJfrcH0FRj1TZHvvE1k4TfRDK+Zqgm3WXkMVwevE+M/EWPSTHdkZnuQjO2NK3reGJUqeiILFpEhW7CPW4dn0VRtoJzjvWZo8+xil29ojJQSRc9VI9/dyuQc3lUJvGB5rWQMrxM/bJVptk82POvXYcVwaH6eNFdd9KoC4iOLPBatMVQPw36eXAg64DR61oqt4vW4HqZrE/8AhmTytzxk7cIAAAAASUVORK5CYII="></span>`;
                break;
            case 1:
                accion=`<img onclick="autorizarCompra('${id}')" style="cursor:pointer" title="Autorizar compra" src="data:image/png;base64,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"/>
                        <img onclick="rechazarCompra('${id}')" style="cursor:pointer" title="Rechazar compra" src="data:image/png;base64,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"/>`;
                break;
            case 2:
                accion = `<img onclick="arribo_productos('${id}')" style="cursor:pointer" src="data:image/png;base64,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"/>
                    <a title="Enviar mensaje de autorización" href="https://wa.me/52${celular}?text=*Solicitud%20de%20mercancía*%0AHola,%20se%20ha%20autorizado%20la%20compra%20de%20mercancía%20que%20tiene%20a%20su%20disposición,%20le%20comparto%20el%20link%20de%20mi%20solicitud:%0A%0A${url}/solicitud-de-requisicion/${id}" target="blank_"><img src="data:image/png;base64,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"/><a>`;
                break;
            case 3:
                accion = `<img src="data:image/png;base64,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"/>`;
                break;
            default:
                break;
        }
        return  accion;
    }
    //ACCIONES ARRIBO DE PRODUCTOS
    setArriboId(id){
        this.arriboId=id;
    }
    getArriboId(){
        return this.arriboId;
    }
    arriboProductos(producto){
        this.itemsArriboProductos[this.frontArribo] = {
            id:producto.id,
            cantidad:producto.cantidad_comprada,
            precio:producto.precio_compra
        };
        this.frontArribo++;
    }
    getArriboProductos(){
        return this.itemsArriboProductos;
    }
    getTotalPagarArribo(){
        let total_pagar=0;
        this.itemsArriboProductos.forEach(producto => {
            total_pagar += (producto.cantidad*producto.precio);
        });
        return total_pagar;
    }
    changeCantidadArribo(id, cantidad){
        let total=0;
        this.itemsArriboProductos.forEach(producto => {
            if (producto.id == id) {
                producto.cantidad = cantidad;
                total = producto.precio*producto.cantidad;
            }
        });
        return total;
    }
}