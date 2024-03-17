<template>
    <div>
        <div class="card">
            <Toolbar class="mb-4">
                <template #start>
                    <Button label="Nuevo" icon="pi pi-plus" severity="constrats" class="mr-2" @click="OpenNew" />
                </template>
                <template #end>
                   <IconField iconPosition="left">
                    <InputIcon>
                        <i class="pi pi-search" />
                    </InputIcon>
                    <InputText v-model="filters['global'].value" placeholder="Buscar..."/>
                   </IconField>
                </template>
            </Toolbar>

            <DataTable ref="dt" :value="zapatos" v-model ="search" datakey="id"
                :paginator="true" :rows="10" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
                currentPageReportTemplate="Mostrar {first} de {last} of {totalRecords} zapatos" size="small" tableStyle="min-with: 50rem">
                <Column field="nombre" header="Nombre" sortable></Column>
                <Column field="precio" header="Precio">
                      <!--
                       <template #body="slotProps">
                       {{formatCurrency(slotProps.data.precio)}}
                    </template>-->
                </Column>
                <Column field="estilo" header="Estilo"></Column>
                <Column field="descripcion" header="Descripcion"></Column>
                <Column field="color" header="Color"></Column>
                <Column field="disponible" header="Dispnible"></Column>
                <Column field="categoria.nombre" header="Categoria"></Column>
                <Column field="marca.nombre" header="Marca"></Column>
                <Column field="talla.talla" header="Talla"></Column>
                <Column :exportable="false">
                <template #body="slotProps">
                    <Button icon="pi pi-images" outlined rounded class="mr-2" severity="info" @click="viewImages(slotProps.data)"/>
                    <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editZapato(slotProps.data)"/>
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="deleteZapato(slotProps.data)"/>
                </template>
                </Column>
            </DataTable>
        </div>
        <!-- Formulario modal para guardar o actualizar un zapato-->

        <Dialog v-model:visible="zapatoDialog" :style="{width: '550px'}" header="Registro de zapatos" :modal="true" class="p-fluid">
            <div class="field">
                <label for="nombre">Nombre</label>
                <InputText id="nombre" v-model="zapato.nombre" required="true" autofocus :class="{'p-invalid': submitted && !zapato.nombre}" />
                <small class="p-error" v-if="submitted && !zapato.nombre">Nombre es requerido.</small>
            </div>
               <div class="field">
                <label for="descripcion">Descripcion</label>
                <Textarea id="descripcion" v-model="zapato.descripcion"
                 required="true" rows="2" cols="20" />
                <small class="p-error" v-if="submitted && !zapato.descripcion">Descripcion es requerido.</small>
            </div>
               <div class="formgrid grid row">
                <div class="field col">
                <label for="precio">Precio</label>
                <InputNumber id="precio" v-model="zapato.precio" mode="currency" currency="USD"  locale="en-US" :class="{'p-invalid': submitted && !zapato.precio}"/>
                <small class="p-error" v-if="submitted && !zapato.precio">Precio es requerido.</small>
                 </div>
                <div class="field col">
                <label for="stock">Stock</label>
                <InputNumber id="disponible" v-model.trim="zapato.disponible"  integeronly 
                :class="{'p-invalid': submitted && !zapato.disponible}"/>
                <small class="p-error" v-if="submitted && !zapato.disponible">Stock es requerido.</small>
              </div>
            </div>
            <div class="formgrid grid row">
                <div class="field col">
                <label for="color">Color</label>
                <InputText id="color" v-model.trim="zapato.color" />
               </div>
                <div class="field col">
                <label for="estilo">Estilo</label>
                <InputText id="estilo" v-model.trim="zapato.estilo" />
               </div>
            </div>
               <div class="formgrif grid row">
                <div class="field col">
                    <label for="marca">Marca</label>
                    <Dropdown v-model="zapato.marca_id" :options="marcas" optionLabel="nombre" optionValue="id" placeholder="Seleccione marca" class="w-full md:w-14rem"></Dropdown>
                    <small class="p-error" v-if="submitted && !zapato.marca_id">Seleccione una marca</small>
                </div>
                <div class="field col">
                      <label for="categoria">Categoria</label>
                    <Dropdown v-model="zapato.categoria_id" :options="categorias" optionLabel="nombre" optionValue="id"  placeholder="Seleccione categoria" class="w-full md:w-14rem"> </Dropdown>
                    <small class="p-error" v-if="submitted && !zapato.categoria_id">Seleccione una Categoria.</small>
                </div>
                <div class="field col">
                      <label for="talla">Talla</label>
                    <Dropdown v-model="zapato.talla_id" :options="tallas" optionLabel="talla" optionValue="id"  placeholder="Seleccione talla" class="w-full md:w-14rem"> </Dropdown>
                    <small class="p-error" v-if="submitted && !zapato.categoria_id">Seleccione una talla.</small>
                </div>
               </div>
               <div class="field">
                <label for="imagenes">Imagenes</label>
                <input type="file" class="form-control" multiple accept="image/*" @change="getImages"/>
                <!--<FileUpload name = "zapato.imagenes[]" @upload="onAdvancedUpload($event)" :multiple="true" accept="image/*" :maxFileSize="1000000">
                <template #empty>
                    <p>Arraste y suelte imagenes aqui para cargarlas</p>
                </template>
                </FileUpload>-->
               </div>

               <template #footer>
                <Button label="Cancelar" icon="pi pi-items" text @click="hideDialog" />
                 <Button label="Guardar" icon="pi pi-check" text @click="saveOrUpdate" />
               </template>
        </Dialog>
        <Dialog v-model:visible="mostrarImagesDialog" :style="{width: '550px'}" header="Imagenes del zapato" :modal="true" class="=p-fluid">
            <Carousel :value="imagenes" :numVisible="1" numScroll="1" :orientation="vertical" verticalViewPortHeight="330px" contentClass="flex-aling-items">
                <template #item="slotProps">
                    <div class="border 1 surface-border border-round m-2 p-3">
                        <div class="mb-3">
                            <div class="relative mx-auto">
                                <img :src="'/images/productos/' + slotProps.data.nombre" :alt="slotProps.data.nombre" class="w-full border-round" />
                            </div>
                        </div>
                    </div>
                </template>
            </Carousel>
        </Dialog>
    </div>
</template>
<script>
     import {ref, onMounted} from 'vue';
     import {FilterMatchMode} from 'primevue/api';
     import {useToast} from 'primevue/usetoast';
 //import Column from 'primevue/column';
 //import FileUpload from 'primevue/fileupload';

    export default {
       data(){
           return{
              zapatos:[],
              zapato:{
                id:null,
                nombre:"",
                descripcion:"",
                precio:0,
                disponible:0,
                estado:"",
                estilo:"",
                marca_id:null,
                categoria_id:null,
                talla_id:null,
              },
              marcas:[],
              catrgorias:[],
              editedZapato: -1, //almacenar un indice de una categoria en un arreglo
              search:'',
              submitted:false,
              zapatoDialog:ref(false),
              mostrarImagesDialog: ref(false),
              imagenes:[]
           }
        },
        computed:{
            formTitle(){
               return  this.zapato.id == null ? "Agregar Zapato" : "Actualizar Zapato";
            },
            btnTitle(){
               return this.zapato.id == null ? "Guardar" : "Actualizar";
            }
        },
        methods:{
               async fetchzapatos(){
                await this.axios.get(`/api/zapatos`)
                .then(response => {
                    this.zapatos = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            async fetchCategorias(){
                await this.axios.get(`/api/categorias`)
                .then(response => {
                    this.categorias = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            async fetchMarcas(){
                await this.axios.get(`/api/marcas`)
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            async fetchTallas(){
                await this.axios.get(`/api/tallas`)
                .then(response => {
                    this.tallas = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            OpenNew(){
                this.zapato = {}
                this.submitted = false;
                this.zapatoDialog = true;
            },
            hideDialog(){
                this.zapatoDialog = false;
                this.submitted = false;
            },
            //metodo para carar el dialog con la categoria a editar
            editZapato(zapato){
                this.zapato = {...zapato};
                this.imagenes = zapato.imagenes;
                this.zapatoDialog = true;
                this.editedZapato = this.zapatos.indexOf(zapato);
            },
            createFormData(){
                this.zapato.estado = 'D';
                 let formData = new FormData();
                 formData.append("nombre",this.zapato.nombre);
                 formData.append("descripcion",this.zapato.descripcion);
                 formData.append("precio",this.zapato.precio);
                 formData.append("disponible",this.zapato.disponible);
                 formData.append("estilo",this.zapato.estilo);
                 formData.append("color",this.zapato.color);
                 formData.append("marca_id",this.zapato.marca_id);
                 formData.append("categoria_id",this.zapato.categoria_id);
                 formData.append("talla_id",this.zapato.talla_id);
                 //verificamos si hay imagenes asociadas al zapato
                 if(this.imagenes != null){
                        for(let i=0; i<this.imagenes.length; i++){
                            formData.append("imagenes[]",this.imagenes[i]);
                        }
                 }
                 return formData;

            },
            getImages(event){
                let files = event.target.files;
                this.imagenes = files;
            },
              viewImages(zapato){
                this.imagenes = zapato.imagenes;
                this.mostrarImagesDialog = true;
            },
        async saveOrUpdate(){
            let me = this;
            me.submitted = true;
            if(me.zapato.nombre.trim()){
                let accion = me.zapato.id == null ? "add" : "upd";
                const headers = {
                    headers:{
                        'Content-Type':'multipart/form-data'
                    }
                }
                if (accion == "add"){
                    //insertar una categoria
                    await this.axios.post(`/api/zapatos`,this.createFormData(), headers)
                    .then(response => {
                        if (response.status == 201){
                            me.verificarAccion(response.data, response.status,accion,response.data.message);
                        }
                    }).catch(errors => {
                        console.log(errors);
                        if (errors.response.status == 409){
                            this.$swal.fire({
                                title: "precaucion...",
                                'text': errors.response.data.message,
                                icon: 'warning'
                            })
                        }
                    })
                }else{
                    await this.axios.put(`/api/zapatos/${me.zapato.id}`,this.zapato)
                    .then(response => {
                        if(response.status == 202){
                            me.verificarAccion(response.data.data,response.status,accion,response.data.message);
                        }
                    }).catch(errors => {
                        console.log(errors);
                        if(errors.response.status == 409){
                            this.$swal.fire({
                                title: "Precaucion...}",
                                text: errors.response.data.message,
                                icon: "warning"
                            });
                        }
                    })

                    
                }
                me.zapatoDialog = false;
                me.zapato = {};
            }

        },
        async deleteZapato(zapato){
            let me = this;
            this.$swal.fire({
                title: 'Seguro/a de eliminar este registro?',
                text: "No podras revertir la accion",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
            }).then((result) =>{
                if(result.value){
                    me.editedZapato = me.zapatos.indexOf(zapato);
                    this.axios.delete(`/api/zapatos/${zapato.id}`)
                    .then(response => {
                        me.verificarAccion(null,response.status,"del",response.data.message);
                    }).catch(errors =>{
                        console.log(errors)
                    })
                }
            })
        },
        verificarAccion(zapato,statusCode,accion,message){
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position:'top-right',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            switch(accion){
                case "add":
                    //agregamos al principio del arreglo categorias
                    me.zapatos.unshift(zapato.original);
                    Toast.fire({
                        icon: 'success',
                        'title':message
                    });
                    break;
                    case "upd":
                        Object.assign(me.zapatos[me.editedZapato],zapato);
                        Toast.fire({
                            icon:'success',
                            'title':message
                        });
                        break;
                     case "del":
                        if (statusCode == 205){
                            me.zapatos.splice(me.editedZapato,1);
                            Toast.fire({
                                icon: 'success',
                                'title': 'Zapatos Eliminado...!'
                            });
                        }else{
                            this.$swal.fire({
                                icon: 'error',
                                text: 'No se puede eliminar , ya  existen reservas registradas en este zapato'
                            });
                        } 
                       break;
                }  
                this.fetchzapatos();      
            }
        },
        mounted(){
            this.fetchzapatos();
            this.fetchMarcas();
            this.fetchTallas();
            this.fetchCategorias();
        }
    }
    const filters = ref({
        'global': {value: null,matchMode: FilterMatchMode.CONTAINS},
    });
    const formatCurrency = (value) => {
    if(value)
        return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
    return;
    }

</script>

<script setup>

/*

<script setup>
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import { useToast } from 'primevue/usetoast';
import { ProductService } from '@/service/ProductService';

onMounted(() => {
    ProductService.getProducts().then((data) => (products.value = data));
});

const toast = useToast();
const dt = ref();
const products = ref();
const productDialog = ref(false);
const deleteProductDialog = ref(false);
const deleteProductsDialog = ref(false);
const product = ref({});
const selectedProducts = ref();
const filters = ref({
    'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
});
const submitted = ref(false);
const statuses = ref([
    {label: 'INSTOCK', value: 'instock'},
    {label: 'LOWSTOCK', value: 'lowstock'},
    {label: 'OUTOFSTOCK', value: 'outofstock'}
]);

const formatCurrency = (value) => {
    if(value)
        return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
    return;
};
const openNew = () => {
    product.value = {};
    submitted.value = false;
    productDialog.value = true;
};
const hideDialog = () => {
    productDialog.value = false;
    submitted.value = false;
};
const saveProduct = () => {
    submitted.value = true;

    if (product.value.name.trim()) {
        if (product.value.id) {
            product.value.inventoryStatus = product.value.inventoryStatus.value ? product.value.inventoryStatus.value : product.value.inventoryStatus;
            products.value[findIndexById(product.value.id)] = product.value;
            toast.add({severity:'success', summary: 'Successful', detail: 'Product Updated', life: 3000});
        }
        else {
            product.value.id = createId();
            product.value.code = createId();
            product.value.image = 'product-placeholder.svg';
            product.value.inventoryStatus = product.value.inventoryStatus ? product.value.inventoryStatus.value : 'INSTOCK';
            products.value.push(product.value);
            toast.add({severity:'success', summary: 'Successful', detail: 'Product Created', life: 3000});
        }

        productDialog.value = false;
        product.value = {};
    }
};
const editProduct = (prod) => {
    product.value = {...prod};
    productDialog.value = true;
};
const confirmDeleteProduct = (prod) => {
    product.value = prod;
    deleteProductDialog.value = true;
};
const deleteProduct = () => {
    products.value = products.value.filter(val => val.id !== product.value.id);
    deleteProductDialog.value = false;
    product.value = {};
    toast.add({severity:'success', summary: 'Successful', detail: 'Product Deleted', life: 3000});
};
const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < products.value.length; i++) {
        if (products.value[i].id === id) {
            index = i;
            break;
        }
    }

    return index;
};
const createId = () => {
    let id = '';
    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for ( var i = 0; i < 5; i++ ) {
        id += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return id;
}
const exportCSV = () => {
    dt.value.exportCSV();
};
const confirmDeleteSelected = () => {
    deleteProductsDialog.value = true;
};
const deleteSelectedProducts = () => {
    products.value = products.value.filter(val => !selectedProducts.value.includes(val));
    deleteProductsDialog.value = false;
    selectedProducts.value = null;
    toast.add({severity:'success', summary: 'Successful', detail: 'Products Deleted', life: 3000});
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'INSTOCK':
            return 'success';

        case 'LOWSTOCK':
            return 'warning';

        case 'OUTOFSTOCK':
            return 'danger';

        default:
            return null;
    }
};

*/

</script>