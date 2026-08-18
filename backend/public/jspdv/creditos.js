let itemsCreditos = [];

class Creditos {
    
    constructor(){
        this.itemsCreditos = [];
    }

    setCredito(credito) {
        this.itemsCreditos.push(credito);
    }

    getCreditos() {
        return this.itemsCreditos;
    }

    getCredito(creditoID) {
        let credito = null;
        this.itemsCreditos.forEach(itemCredito => {
            if (itemCredito.id == creditoID) {
                credito = itemCredito;
            }
        });

        return credito;
    }

    updateMontoPagado(creditoID, montoPagado) {

        this.itemsCreditos.forEach(itemCredito => {
            if (itemCredito.id == creditoID) {
                itemCredito.monto_pagado = montoPagado;
            }
        });

    }

}
