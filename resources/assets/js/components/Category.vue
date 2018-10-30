<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="openModal('category', 'register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="name">Nombre</option>
                                      <option value="description">Numero de empleado</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listCategory(1, search, criteria)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listCategory(1, search, criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Número de empleado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in arrayCategory" :key="category.id">
                                    <td>
                                        <button type="button" @click="openModal('category', 'update', category)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="category.condition">
                                            <button type="button" class="btn btn-danger btn-sm" @click="deactivateCategory(category.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activateCategory(category.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>                                        
                                    </td>
                                    <td v-text="category.name"></td>
                                    <td v-text="category.description"></td>
                                    <td>
                                        <div v-if="category.condition">
                                            <span class="badge badge-success">Confirmado</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Sin confirmar</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1, search, criteria)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page, search, criteria)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1, search, criteria)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titleModal"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre del empleado">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Número de empleado</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese el número de empleado">
                                    </div>
                                </div>
                                <div v-show="errorCategory" class="from-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in arrayShowMsjCategory" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" @click="registerCategory()" class="btn btn-primary">Guardar</button>
                            <button type="button" v-if="typeAction==2" @click="updateCategory()" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->

            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                category_id : 0,
                name : '',
                description : '',
                arrayCategory : [],
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorCategory : 0,
                arrayShowMsjCategory : [],
                pagination : {
                    'total'         : 0,
                    'current_page'  : 0,
                    'per_page'      : 0,
                    'last_page'     : 0,
                    'from'          : 0,
                    'to'            : 0
                },
                offset : 3,
                criteria : 'name',
                search : ''
            }
        },
        computed : {
            isActived : function(){
                return this.pagination.current_page
            },
            //Calcula los elementos de pagínacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++
                }
                return pagesArray;
            }
        },
        methods : {
            listCategory (page, search, criteria){ //No es necesario el mismo nombre pero para evitar confución
                let me=this;

                var url = '/category?page=' + page + '&search=' + search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    var res = response.data;
                    me.arrayCategory = res.categories.data;
                    me.pagination = res.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;
                //Actualiza la pagína actual
                me.pagination.current_page = page;
                //Enviar la petición para visualizar la información
                me.listCategory(page, search, criteria);
            },
            registerCategory(){
                if(this.validateCategory()){
                    return;
                }
                let me = this;
                
                axios.post('/category/registrar',{
                    'name' : this.name,
                    'description' : this.description
                }).then(function (response) {
                    me.closeModal();
                    me.listCategory(1, '', 'name');
                }).catch(function (error){
                    console.log(error);
                });
            },
            deactivateCategory(id){
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                    title: '¿Estas seguro de desactivar la categoría?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios.put('/category/desactivar',{
                            'id' : id
                        }).then(function (response){
                            me.listCategory(1, '', 'name');
                            swalWithBootstrapButtons(
                                'Sin confirmar',
                                'El registro esta sin confirmar',
                                'success'
                            )
                        }).catch(function (error){
                            console.log(error);
                        })
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ){}
                })
            },
            activateCategory(id){
                const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                    title: '¿Estas seguro de activar la categoría?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me = this;

                        axios.put('/category/activar',{
                            'id' : id
                        }).then(function (response){
                            me.listCategory(1, '', 'name');
                            swalWithBootstrapButtons(
                                'Confirmado',
                                'El registros se a confirmado con éxito',
                                'success'
                            )
                        }).catch(function (error){
                            console.log(error);
                        })
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ){}
                })
            },
            validateCategory(){
                this.errorCategory = 0;
                this.arrayShowMsjCategory = [];
                
                if(!this.name){ 
                    this.arrayShowMsjCategory.push("El nombre no puede estar vacio.");
                }

                if(this.arrayShowMsjCategory.length){ 
                    this.errorCategory = 1;
                }

                return this.errorCategory;
            },
            updateCategory() {
                if(this.validateCategory()){
                    return;
                }

                let me = this;

                axios.put('/category/actualizar',{
                    'name' : this.name,
                    'description' : this.description,
                    'id' : this.category_id
                }).then(function (response){
                    me.closeModal();
                    me.listCategory(1, '', 'name');
                }).catch(function (error){
                    console.log(error);
                })
            },
            openModal(model, action, data = []){
                switch(model){
                    case 'category': {
                        switch(action){
                            case 'register': {
                                this.modal = 1;
                                this.titleModal = 'Registrar categoría';
                                this.name = '';
                                this.description = '';
                                this.typeAction = 1;
                                break;
                            }
                            case 'update':{
                                this.modal = 1;
                                this.titleModal = 'Actualizar categoría';
                                this.category_id= data.id;
                                this.name = data.name;
                                this.description = data.description;
                                this.typeAction = 2;
                                break;

                            }
                        }
                    }
                }
            },
            closeModal(){
                this.modal = 0;
                this.titleModal = '';
                this.name = '';
                this.description = '';
            }
        },
        mounted() {
            this.listCategory(1, this.search, this.criteria);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important; 
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>