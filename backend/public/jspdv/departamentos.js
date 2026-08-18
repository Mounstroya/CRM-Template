let itemsDepartamentos=[];
let itemsCategorias=[];
class Departamentos{
    constructor(){
        itemsDepartamentos=[];
        this.frontDepto = 0;
        this.frontCateg = 0;
        this.end = 0;
    }
    addDepartamentos(departamentos){
        for (let index = 0; index < departamentos.length; index++) {
            itemsDepartamentos[this.frontDepto] = {
                'id':departamentos[index].id,
                'departamento':departamentos[index].departamento,
                'categ':[],
                'status':departamentos[index].status
            };
            this.frontDepto++;
        }
    }
    addDepartamento(id, departamento, status){
        itemsDepartamentos[this.frontDepto] = {
            'id':id,
            'departamento':departamento,
            'categ':[],
            'status':status
        }
        this.frontDepto++;
    }
    addDeptoCategorias(categorias){
        let i = 0;
        for (let index = 0; index < itemsDepartamentos.length; index++) {
            for (let index_catg = 0; index_catg < categorias.length; index_catg++) {
                if(itemsDepartamentos[index]['id'] == categorias[index_catg].departamentos_id){
                    itemsDepartamentos[index]['categ'][i] = [
                        categorias[index_catg].id,
                        categorias[index_catg].categoria
                    ];
                    i++;
                }
            }
            i=0;
        }
        categorias.forEach((categoria) => {
            itemsCategorias[this.frontCateg] = {
                'id':categoria.id,
                'departamentos_id':categoria.departamentos_id,
                'categoria':categoria.categoria
            }
            this.frontCateg++;
        });
    }
    totalDeptos(){
        return this.frontDepto;
    }
    addDeptoCategoria(id, departamentos_id, categoria){
        itemsCategorias[this.frontCateg] = {
            'id':id,
            'departamentos_id':departamentos_id,
            'categoria':categoria
        }
        itemsDepartamentos.forEach(departamento => {
            if (departamentos_id == departamento.id) {
                departamento.categ.push([
                    id,
                    categoria
                ]);
            }
        });
        $('#categorias_id').append(`
        <option value="${id}">${categoria}</option>
		`);
        this.frontCateg++;
        this.parseDeptosTbl();
    }
    parseDeptosTbl(){
        let status;
        let categorias = "";
        $('#tbl_departamentos').empty();
        for (let index = 0; index < itemsDepartamentos.length; index++) {
            status = `<img id="cat_status${itemsDepartamentos[index]['id']}" class="img-button" onclick="status_depto(${itemsDepartamentos[index]['id']})" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAEqklEQVRoge2XS2hcVRjHf+feM/femTuTJpM0bZppbFW0VKXgQoKILhSrFg0uSlcKihtdiCupQl1IbXBpdyIo1AcURU1RfNBKrKW2UKJWbX2WPkLS6iQzNjOZxz33HheT16Uzk8kDN97fZpjvnPPd/3fOdx4fRERERERERET8fxHNGo+dGusTvhxA6B3AJg2ZmUGjwHktxCe+9obuuaP30n8hth51Azh+PNsrdHGvjtmPa4TR1INSrP1gD9ZXB/CVCjWZBmRStd8lUkAzisFZYAiDITFMvqUATg7/vjOwnHcCaVlaawpFRWHao1IN8FSAAUhpYNsGbUmLuCMRAmT+MhsGt1P9+3LInyOhJ7XIUi9OHhgkyWviMyoNAzj15cieSlvPyxgGhaIiO1mi6gVNPfeud3ETsvbH98kM3o8691OoT0ccOpyVRTDDdwgGxFHmUnYugO8/Hn6iuP7mN7UwmMiVmcxX6rtYgGOb9PUmw0bfZ+MLt+Nl51fCELCxbVmpVI+LQL/4hnEAA+DHj47cUOra/AZG6+IBujrsa42myfiLX2Ca5pwp0NCiy1boQ/ChfhAbZgLQXulgYDlmoahaFu/YJolErG6bal+PuntXyDZVAa1XJHweTT8FngUQZ996/bbc1odPBxhcGC0smvOzZHpcEnHZsF34FXqe3kyg/DnbuiS49WNeDjlMrjeEMndrYVIoqpbFxx3ZULzWoPwAbdoY120NtZW8FYteSAeKR4xKOnMvQHG6de+ddXK/VFZcyZb488JVspO1NCxt2x7qU1HXDFsZgkek53alAcqVpc9+qayYKiqmClV8fz7By+Wa0vJNd7IwY1Rrn1gKt0qV7JQAqkXvqaQkO1lhqlDFazDGmwmmsmELqQX21ddP74JEbnxESClIuhbSFPyVLS/qde5yCfxm3VaDQMYKWc9P91mmNAgWbGLTFKSSFilXEndqcV4aK7bkVc7cWPb4ryH76txjC9CMSVnI5kn3dcdtiVJVkokYbSmLRNxEiPmXRrGkKJVb24WOU5Ma/+3bkD1m1uu9Is4Ydu7iYYCuTpsbN62hZ10CNyFD4gEmc61fpW68tnWdHz4P2a3VD2DIwCu9KrSPNA1EgyfjUmY/FjNJuhKhygQXfgm1Nbn3lkMeySFjyzPPnU6dPzXSrOfE5OIbd5butIMQgu53nyfw5zexIaDBy2O57BPD5A0Ao6p3GdVS3SOjOO1RrrR2mqTbHVxXInPjyGPvh9pSFg1XeBmcJMl+mDkYbnls4I/E+Jknhb5W6ESLuZ9ud2o3tO/TM7gd358/0QwB7fFVEQ615/Sjs4XN3Mm2bedDB6zc5ZcI5j9cnFaLzr4VM+hd59KVthGBT2bffXjZK6E+axwwV2f2RxDcNVsLQL2S8sjPO/1E29vatGyta8+Fq1Melao/d/NKU+DYJm4iVtuwQiDzY/S88gDeRFj8KpWUOWol5f6mJeUsR0fG11rF4t7Acp/Swli0qO8+uJvY1+/hq/Bqraioh0vomaLeYUgc5p96HZtOzIkTE5nA9AfQeodGb0aLTG2UHkVzDiE+NQN5qL+/c3TJEiMiIiIiIiIiIlbMv4H7ujTnb/oeAAAAAElFTkSuQmCC">`;
            if (itemsDepartamentos[index]['status'] == 1) {
                status = `<img id="cat_status${itemsDepartamentos[index]['id']}" class="img-button" onclick="status_depto(${itemsDepartamentos[index]['id']})" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABmJLR0QA/wD/AP+gvaeTAAAE9UlEQVRoge2YzW8UZRjAf+/MOzu7O9uP3RZKaSGoiCIHDYECBwEFNIEgHIkeTIwHbwbUhHgBvRiIyl+gB09yMIDoQTSSqNEam+CJmJgQSEtJSbst3d3ux7wzj4ftB2N32+1Hgon7u73vzDzze559v2ahSZMmTZo0adLk/4t6lC//ZWB4owr0MZQcATYJ9E5LDQG3RalvAvGv7OvrGawXo24CF64fb/ftynGl5BVQW4ENgLcUwSAIGRycJDBhpD+mHZ5ac5Bn02+ilF4whiAiKriijXVyz57u24smcPb6/rinvbdBTgPtSxGOvFhgeDhHqehH+jOt6zi4/hyu3Y6IkC8Y8lM+5UqIb0IsQGsL17VoTcVIxDVKQahMWYLSa3t3P/FV3QQu/PRyt1H2JWDXcsVnGB8rkh0vRvp6MlvY330OC4t8wTCaLVLxwzoRpp9Z5+Elq7+SSEhFTZx5sW/rhzPXrX/J96+GfBgIEw9Kkb5MqosXus+jxGI0W2J4pLCofNy1Z+UBlLKISfqDH34feD2SwNnr++OBsi8DG1cqD5DNFglDmW07juZg73kUirHxEtmJckNxOtPuvD6lFK7q+uz6bzc2zSbgOcmTAn2rIR+GwmQuKri18yVcu518wTQsH3dtkkmn5jWLmF1WUxcB1Ec/H0lr5BYrmLAPk8tVuD+Sn21rrTmx5UtAc2cov+iwmaG32yOZWGiFCnkQ3thmaeQ4qyQPzFt1ulo3o5RDvmAalk/EdV15ETBBCFgYgtMa5Ohq7mfliom0e7ydABSm/Fq316SjxtgvlgyTeZ9c3iflOaxbkyBG5yENatvKlKMYXyLtNfFq+FJ56dUvlgy5giGXrxAEc3FLpWqRHNWS0aDWg9QMthzCMCraonsAMKaxBFpSmtFsmVy+gl/nGX86mZhqdRbex5eFonZB6hdJa0XKi6Ftxf3RUt37Hn5DNaJggdxblmcdLCs6n3JmCABbW5F+21a0t7lsWO/x+MZW1nbEKUxF5089tF2NVZFJX6O4ifDkKrhXg2uLIJj76e8Xb5KJPUPC1RhTIZV0aG2JkUzYKDWXbKFoKJYaSyAeryZgJJ+1QF1dLXkA142OyuGpPwDo7HDZvKmN7q4kXlJH5AGy441tcABeorrBlWX0O8t3YpeByZVpz5FI2pH2yOTfiPho20LVWa2XUn3HsUl5GiHAUfZ56/1dl8ZQ8vFKxWdIJl2sh0yNCfhz4vMFnxnLLj5xZ1ibiaOUIhfe6j+8+/BNC6AQ15+guLFM5wiWBa1t0Y3or/vXKIXjNe8vTPmUykFDsTPtcTxPE0g5iMWSJ2DmNLrj6pQVmGMgwyuynyadTkRWI2MM3w++izBfdKzBsZ9pj9ORdhERyoy8cWD79juzCQC8s+/aoG3bfcDAShOwbEVbezzSN5Ef5ce7pwmZW6EKU2bR6scci54uj86MC4SUZOzMoV07v5i5Pv+TcuBoMlUMTgm8B7QuNwkRuHd3ct7kzLR0caD3HHErjcj0GSfnU64EszuvttX0x4xDyquuWIIpiRRffX7X5ksPx6t7ivv015czJrCOgzqq4GlQvSCppSRR76PecTRbOvfzXPotLFX7zD9bCEQEc1Es99TeHWvmbbqP9G+V/v6x3tAOjiFyRJDHENVbtZIhhFso9a0d6q937+4YepSeTZo0adKkSZMm/1X+AdqZ5PUbwGSBAAAAAElFTkSuQmCC">`;
            }
            for (let index_cat = 0; index_cat < itemsDepartamentos[index]['categ'].length; index_cat++) {
                categorias += ` <span class="badge bg-warning text-dark" id="depto_cat${itemsDepartamentos[index]['categ'][index_cat][0]}">${itemsDepartamentos[index]['categ'][index_cat][1]} <i class="fas fa-trash" style="color:red;cursor:pointer" onclick="delete_cat(${itemsDepartamentos[index]['categ'][index_cat][0]})"></i></span> `;
            }
            $('#tbl_departamentos').append(`
            <tr>
                <th scope="row">${index+1}</th>
                <td>${itemsDepartamentos[index]['departamento']}</td>
                <td id="tbl_content_cat${itemsDepartamentos[index]['id']}"><button class="btn btn-default btn-xs" onclick="modal_add_cat(${itemsDepartamentos[index]['id']})"><span class="fa fa-plus-square"></span></button> ${categorias}</td>
                <td>${status}</td>
            </tr>
            `);
            categorias = "";
        }
    }
    getItemsCategorias(){
        return itemsCategorias;
    }
    getItemsDepartamentos(){
        return itemsDepartamentos;
    }
    totalCateg(){
        return this.frontCateg;
    }
}