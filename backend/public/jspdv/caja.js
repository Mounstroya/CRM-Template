let itemCaja = [];
//let itemsCajas = [];
class Caja{
    constructor(){
        this.status = 0;
        this.itemsCajas = [];
    }
    setCaja(id,status,fecha_apertura,fecha_cierre,cuenta_inicial,cuenta_final){
        itemCaja = {
            'id':id,
            'status':status,
            'fecha_apertura':fecha_apertura,
            'fecha_cierre':fecha_cierre,
            'cuenta_inicial':cuenta_inicial,
            'cuenta_final':cuenta_final
        };
    }
    getCaja2(){
        return itemCaja;
    }

    setItemCaja(caja) {
        this.itemsCajas.push(caja);
    }

    getCajas() {
        return itemsCajas;
    }

    getCaja(cajaID) {
        let responseCaja = null;
        this.itemsCajas.forEach(caja => {
            if (caja.id == cajaID) {
                responseCaja = caja;
            }
        });
console.error(responseCaja);
        return responseCaja;
    }
}