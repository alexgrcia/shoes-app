<template>
    <div>
        <div class="card">
            <Toolbar class="mb-4">
                <template #start>
                    <Button label="Nuevo" icon="pi pi-plus" severity="contrast" class="mr-2" @click="OpenNew" />
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

            <DataTable ref="dt" :value="tallas" v-model ="search" datakey="id"
                :paginator="true" :rows="10" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
                currentPageReportTemplate="Mostrar {first} de {last} of {totalRecords} tallas" size="small" tableStyle="min-with: 50rem">
                
                <Column field="talla" header="Talla" sortable></Column>
                <Column :exportable="false">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editTalla(slotProps.data)" />
                        <Button icon="pi pi-trash" outlined rounded severity="danger" @click="deleteTalla(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </div>

        <Dialog v-model:visible="tallaDialog" :style="{width: '450px'}" header="Registrar tallas" :modal="true" class="p-fluid">
            <div class="field">
                <label for="name">Nombre</label>
                <InputText id="talla" v-model="talla.talla" required="true" autofocus :class="{'p-invalid': submitted && !talla.talla}" />
                <small class="p-error" v-if="submitted && !talla.talla">Nombre es requerido.</small>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="hideDialog"/>
                <Button label="Guardar" icon="pi pi-check" text @click="saveOrUpdate"/>
            </template>
        </Dialog>
    </div>
</template>
<script>
import { ref, onMounted } from 'vue';
import { FilterMatchMode } from 'primevue/api';
import { useToast } from 'primevue/usetoast';

    export default {
       data(){
           return{
              tallas:[],
              talla:{
                id:null,
                talla:""
              },
              editedTalla: -1, //almacenar el indice de una talla dentro 
              tallaErrors: {
                talla:false
              },
              search:'',
              submitted:false,
              tallaDialog: ref(false)
           }
        },
        computed:{
            formTitle(){
                return  this.talla.id == null ? "Agregar Talla" : "Actualizar Talla";
            },
            btnTitle(){
               return  this.talla.id == null ? "Guardar" : "Actualizar";
            }
        },
        methods:{
            async fetchtallas(){
                await this.axios.get(`/api/tallas`)
                .then(response => {
                    this.tallas = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            OpenNew(){
                this.talla ={}
                this.submitted = false;
                this.tallaDialog = true;
            },
            hideDialog(){
                this.tallaDialog = false;
                this.submitted = false;
            },
            //metodo para carar el dialog con la talla a editar
            editTalla(talla){
                this.talla ={...talla};
                this.tallaDialog = true;
                this.editedTalla = this.tallas.indexOf(talla);
            },
        
        async saveOrUpdate(){
            let me = this;
            me.submitted = true;
            if(me.talla.talla.trim()){
                let accion = me.talla.id == null ? "add" : "upd";
                if (accion == "add"){
                    //insertar una talla
                    await this.axios.post(`/api/tallas`,me.talla)
                    .then(response => {
                        if (response.status == 201){
                            me.verificarAccion(response.data.data, response.status,accion,response.data.message);
                            //toast.add({severity:'success','summary':'Succeesful',details:response.data.mesage,life:3000});
                        }
                    }).catch(errors => {
                        console.log(errors);
                        if (errors.response.status == 409){
                            this.$swal.fire({
                                title: "precaucion...",
                                text: errors.response.data.message,
                                icon: "warning"
                            });
                            //toast.add({severity:'success','summary':'Succeesful',details:response.data.mesage,life:3000});
                        }
                    })
                }else{
                    //actualizar talla
                    await this.axios.put(`/api/tallas/${me.talla.id}`,me.talla)
                    .then(response =>{
                        if(response.status == 202){
                            me.verificarAccion(response.data.data, response.status,accion,response.data.message);
                        }
                    }).catch(errors =>{
                        console.log(errors);
                        if(errors.response.status == 409){
                            this.$swal.fire({
                                title: "Precaucion...",
                                text: errors.response.data.message,
                                icon: "warning"
                            });
                        }
                    })
                }
                me.tallaDialog = false;
                me.talla = {};
            }

        },
        async deleteTalla(talla){
            let me = this;
            this.$swal.fire({
                title: 'Seguro/a de eliminar este registro?',
                text:"No podras revertir la accion",
                icon:'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
            }).then((result) => {
                if(result.value){
                    me.editedTalla = me.tallas.indexOf(talla);
                    this.axios.delete( `/api/tallas/${talla.id}`)
                    .then(response => {
                        me.verificarAccion(null,response.status,"del",response.data.message);
                    }).catch(errors =>{
                        console.log(errors)
                    })
                }
            })
        },
        verificarAccion(talla,statusCode, accion,message){
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            switch(accion){
                case "add":
                    //agregamos al principio del arreglo tallas
                    me.tallas.unshift(talla);
                    Toast.fire({
                        icon: 'success',
                        'title': message
                    });
                    break;
                    case "upd":
                        Object.assign(me.tallas[me.editedTalla],talla);
                        Toast.fire({
                            icon:'success',
                            'title': message
                        });
                        break;
                     case "del":
                        if (statusCode == 205){
                            me.tallas.splice(me.editedTalla,1);
                            Toast.fire({
                                icon: 'success',
                                'title': 'Talla Eliminada...!'
                            });
                        }else{
                            this.$swal.fire({
                                icon: 'error',
                                text: 'No se puede eliminar , ya  existen productos registrados de esta talla'
                            });
                        } 
                       break;
                }        
            }
        },
        mounted(){
            this.fetchtallas();
        }
    }
    const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });
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