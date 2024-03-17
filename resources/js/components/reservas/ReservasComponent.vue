<template>
    <div>
        <div class="card">
            <Toolbar class="mb-4">
                <template #start>
                   <!--colocar radio buton para filtrar reservas por estado-->
                   <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio"  v-model="estado" name="reserva.estado" id="inlineRadio1" value="R" checked>
                      <label class="form-check-label" for="inlineRadio1"> Reservas Recibidas</label>
               </div>
               <div class="form-check form-check-inline">
                     <input class="form-check-input" type="radio"   v-model="estado" name="reserva.estado" id="inlineRadio2" value="D">
                     <label class="form-check-label" for="inlineRadio2">Reservas Atendidas</label>
             </div>
              <div class="form-check form-check-inline">
                   <input class="form-check-input" type="radio"  v-model="estado" name="reserva.estado" id="inlineRadio3" value="A">
                   <label class="form-check-label" for="inlineRadio3">Reservas Anuladas</label>
               </div>
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

            <DataTable ref="dt" :value="filteredReservas" v-model ="search" datakey="id"
                :paginator="true" :rows="10" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
                currentPageReportTemplate="Mostrar {first} de {last} of {totalRecords} reservas" size="small" tableStyle="min-with: 50rem">
                <Column field="id" header="Orden No" sortable></Column>
                <Column field="fecha" header="Fecha"></Column>
                <Column field="usuario.name" header="Cliente"></Column>
                <Column field="usuario.email" header="Email"></Column>
                <Column field="usuario.telefono" header="Telefono"></Column>
                <Column field="estado" header="Estado"></Column>
                <Column :exportable="false">
                <template #body="slotProps">
                    <Button icon="pi pi-list" outlined rounded class="mr-2" severity="info" @click="viewDetails(slotProps.data)" v-tooltip="{ value: 'Ver Detalle', showDelay: 100, hideDelay: 300 }"/>
                    <Button icon="pi pi-check" outlined rounded class="mr-2" @click="changeReserva(slotProps.data,'D')" v-if="slotProps.data.estado == 'R'" v-tooltip="{ value: 'Despachar Reserva', showDelay: 100, hideDelay: 300 }"/>
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="changeReserva(slotProps.data,'A')" v-if="slotProps.data.estado == 'R'" v-tooltip="{ value: 'Anular Reserva', showDelay: 100, hideDelay: 300 }"/> 
                </template>
                </Column>
            </DataTable>
        </div>
        <!-- Formulario modal para mostrar el detalle de orden-->

        <Dialog v-model:visible="mostrarDetalleDialog" :style="{width: '550px'}" header="Detalle de Reserva" :modal="true" class="p-fluid">
            <div class="row">
                <div class ="col">
                <label>Reserva:{{reserva.id}}</label>
                </div>
                <div class ="col">
                    <label>Fecha Orden:{{reserva.fecha}}</label>
            </div>
            </div>
            <div class="row">
                <label>Cliente:{{reserva.usuario.name}}</label>
            </div>
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Zapatos</td>
                            <td>Marca</td>
                            <td>Cantidad</td>
                            <td>Precio</td>
                            <td>Subtotal</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in reserva.detalle_reservas" :key="item.id">
                            <td>{{item.zapato.nombre}},{{item.zapato.descripcion}}, modelo: {{item.zapato.estilo}}</td>
                            <td>{{item.zapato.marca.nombre}}</td>
                            <td>{{item.cantidad}}</td>
                            <td>${{item.zapato.precio}}</td>
                            <td>${{item.cantidad * item.zapato.precio}}</td>
                       </tr>
                       <tr>
                        <td colspan="4">Total de la reserva</td>
                        <td><b>${{reserva.monto}}</b> </td>
                       </tr>
                    </tbody>
                </table>
            </div>
             <template #footer>
                <Button label="Cerrar" icon="pi pi-items" text @click="hideDialog" />
               </template>
        </Dialog>
    </div>
</template>
<script>
     import {ref, onMounted} from 'vue';
     import {FilterMatchMode} from 'primevue/api';
     import {useToast} from 'primevue/usetoast';
    export default {
       data(){
           return{
              reservas:[],
              reserva:null,
                editedReserva:-1,
                search:'',
                submitted:false,
                mostrarDetalleDialog: ref(false),
                estado:'R' 
           }
        },
        computed:{
           filteredReservas(){
        if(this.estado === 'R'){
            return this.reservas.filter(reser => reser.estado === 'R');
        }else if(this.estado === 'D'){
            return this.reservas.filter(reser => reser.estado === 'D');
        }else if(this.estado === 'A'){
            return this.reservas.filter(reser => reser.estado === 'A');
        }
        return this.reservas;
     }
        },
        methods:{
               async fetchreservas(){
                await this.axios.get(`/api/reservas`)
                .then(response => {
                    this.reservas = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            hideDialog(){
                this.mostrarDetalleDialog = false;
                this.submitted = false;
             
            },
            
            viewDetails(reserva){
                this.reserva = {...reserva};
                this.mostrarDetalleDialog= true;
              
            },

            changeReserva(reserva, estado){
               let textEstado = estado == 'D' ? 'Despachar' : 'Anular';
               this.$swal.fire({
                title: `Seguro/a de ${textEstado} la reserva no: ${reserva.id}?`,
                text: "No podras revertir la accion",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor:'#d33',
                confirmButtonText: 'SI',
                cancelButtonText: 'No',
               }).then((result) => {
                  if(result.value){
                    //definiendo constante para crear mensaje tipo toast
                    const Toast = this.$swal.mixin({
                        toast: true,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer:2000,
                        timerProgressBar: true
                    });
                    this.reserva = {...reserva};
                    this.reserva.estado = estado;
                    this.editedReserva = this.reservas.indexOf(reserva);
                    this.axios.put(`/api/reservas/${reserva.id}`,this.reserva)
                    .then(response => {
                        let estadoText = response.data.data.estado == 'D' ? 'Despachada' : 'Anulada';
                        if(response.status == 202){
                            Object.assign(this.reservas[this.editedReserva],response.data.data);
                            Toast.fire({
                                icon:'success',
                                'title': `response.data.message a: ${estadoText}`
                            });
                        }
                    }).catch(errors => {
                         console.log(errors);
                    })
                }
               })
            }
     },
     mounted(){
            this.fetchreservas();
        }
    }
    const filters = ref({
        'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
    });

    const formatCurrency = (value) => {
    if(value)
        return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
    return;
    };
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