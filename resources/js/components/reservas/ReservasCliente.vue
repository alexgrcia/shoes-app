
<template>
    <div class="card">
        <DataView :value="productos" :layout="layout"  paginator :rows="6">
            <template #header>
                <div class="flex justify-content-end">
                    <Button label="Ver Orden" link @click="viewOrden" v-if="orden.detalleOrden.length > 0" ></Button>
                    <DataViewLayoutOptions v-model="layout" />
                </div>
            </template>

            <template #list="slotProps">
                <div class="grid grid-nogutter">
                    <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                        <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3" :class="{ 'border-top-1 surface-border': index !== 0 }">
                            <div class="md:w-10rem relative">
                               <!-- <img class="block xl:block mx-auto border-round w-full" :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`" :alt="item.name" />-->
                               <img class="block xl:block mx-auto border-round w-full" src="/images/productos/1710551306_tablet.jpeg" :alt="item.nombre" />

                               <Tag :value="item.stock" class="absolute" style="left: 4px; top: 4px"></Tag>
                            </div>
                            <div class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                <div class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                    <div>
                                        <span class="font-medium text-secondary text-sm">{{ item.categoria.nombre }}</span>
                                        <div class="text-lg font-medium text-900 mt-2">{{ item.nombre }}</div>
                                    </div>
                                    <div class="surface-100 p-1" style="border-radius: 30px">
                                        <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2" style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                            <!--Mostrar informacion del producto-->
                                     
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-column md:align-items-end gap-5">
                                    <span class="text-xl font-semibold text-900">${{ item.precio }}</span>
                                    <div class="flex flex-row-reverse md:flex-row gap-2">
                                         <div>
                                            <label>Cantidad</label>
                                            <InputNumber v-model="item.cantidad" inputId="horizontal-buttons" showButtons buttonLayout="horizontal" :step="1" :min="1">
                                              <template #incrementbuttonicon>
                                                 <span class="pi pi-plus" />
                                             </template>
                                                <template #decrementbuttonicon>
                                                     <span class="pi pi-minus" />
                                                </template>
                                            </InputNumber>

                                        </div>
                                        <Button icon="pi pi-shopping-cart" label="Agregar a Orden"  @click="addToOrden(item)" :disabled="item.stock === '0'" class="flex-auto md:flex-initial white-space-nowrap"></Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template #grid="slotProps">
                <div class="grid grid-nogutter">
                    <div v-for="(item, index) in slotProps.items" :key="index" class="col-12 sm:col-6 md:col-4 xl:col-6 p-2">
                        <div class="p-4 border-1 surface-border surface-card border-round flex flex-column">
                            <div class="surface-50 flex justify-content-center border-round p-3">
                                <div class="relative mx-auto">
                                   <!-- <img class="border-round w-full" :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`" :alt="item.name" style="max-width: 300px"/>-->
                                    <img class="border-round w-full" src="/images/productos/1710551306_tablet.jpeg" :alt="item.nombre" style="max-width: 300px" />
                                  <Tag :value="item.stock" class="absolute" style="left: 4px; top: 4px"></Tag>
                                </div>
                            </div>
                            <div class="pt-4">
                                <div class="flex flex-row justify-content-between align-items-start gap-2">
                                    <div>
                                        <span class="font-medium text-secondary text-sm">{{ item.categoria.nombre }}</span>
                                        <div class="text-lg font-medium text-900 mt-1">{{ item.nombre }}</div>
                                    </div>
                                    <div class="surface-100 p-1" style="border-radius: 30px">
                                        <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2" style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                         
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-column gap-4 mt-4">
                                    <span class="text-2xl font-semibold text-900">${{ item.precio }}</span>
                                    <div v-if="user.length > 0">
                                        <label>Cantidad</label>
                                            <InputNumber v-model="item.cantidad" inputId="horizontal-buttons" showButtons buttonLayout="horizontal" :step="1" :min="1">
                                              <template #incrementbuttonicon>
                                                 <span class="pi pi-plus" />
                                             </template>
                                                <template #decrementbuttonicon>
                                                     <span class="pi pi-minus" />
                                                </template>
                                            </InputNumber>
                                    </div>
                                    <div class="flex gap-2"> 
                                        <Button icon="pi pi-shopping-cart" label="Agregar a Orden" :disabled="item.stock === '0'" class="flex-auto white-space-nowrap" @click="addToOrden(item)" ></Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
     </DataView>
        <!--Modal para mostrar la orden y despues hacerla efectiva-->
        <Dialog v-model:visible="mostrarOrdenDialog" :style="{width: '650px'}" header="Detalle de Orden" :modal="true" class="p-fluid">"
         <div class="row">
            <div class="col">
                <label>Fecha Orden: {{ orden.fecha }}</label>
            </div>
         </div>
          <div class="row">
                <label>Cliente: {{ user.name }}</label>
            </div>
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Producto</td>
                            <td>Marca</td>
                            <td>Cantidad</td>
                            <td>Precio</td>
                            <td>Subtotal</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in orden.detalleOrden" :key="item.id">
                            <td>{{ item.producto.nombre }},{{ item.producto.descripcion }}, modelo: {{item.producto.modelo }}</td>
                            <td>{{item.producto.marca.nombre}}</td>
                            <td>{{item.cantidad}}</td>
                            <td>${{item.producto.precio}}</td>
                            <td>${{item.cantidad * item.producto.precio}}</td>
                            <td>
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="deleteItem(item)" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Total de la Orden</td>
                            <td><b>${{ total }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <template #footer>
                <Button label="Cerrar" icon="pi pi-items" text @click="hideDialog" />
                 <Button label="Confirmar Orden" icon="pi pi-check" text @click="saveOrden" v-if="orden.detalleOrden.length > 0" />
               </template>
        </Dialog>
    </div>
</template>
<script>

     import {ref, onMounted} from 'vue';  
    export default {
        props:['user'],
       data(){
           return{
              productos:[],
              orden:{
                correlativo:"",
                fecha:new Date(Date.now() - new Date().getTimezoneOffset()*60000),
                fecha_despacho:null,
                estado:'R',
                monto: new Number("0").toFixed(2),
                user:null,
                detalleOrden:[]
              },
                editedOrden: -1,
                search:'',
                submitted:false,
                mostrarOrdenDialog: ref(false),
                layout : ref('grid')
           }
        },
        computed:{ 
            total(){
                var totalOrder = 0;
                this.order.detalleOrden.forEach(element => {
                    totalOrder += (element.producto.precio * element.cantidad);
                });
                this.orden.monto = totalOrder;
                return totalOrder;
            }
        },
        methods:{
               async fetchProductos(){
                await this.axios.get(`/api/productos`)
                .then(response => {
                    this.productos = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            addToOrden(item){
                this.orden.detalleOrden.push({
                    id:null,
                    cantidad: item.cantidad,
                    precio: item.precio,
                    producto: item
                });
            },
            hideDialog(){
                this.mostrarOrdenDialog = false;
                this.submitted = false;
            },
            viewOrden(){
                 this.mostrarOrdenDialog = true;
                this.submitted = true;
            },
            deleteItem(item){
                //primero obtenemos el indice el item a eliminar
                let index = this.orden.detalleOrden.indexOf(item);
                //quitamos el item del arreglo
                this.orden.detalleOrden.splice(index,1);

            },
            async saveOrden(){
                if(this.orden.detalleOrden.length > 0){
                    //setear datos faltantes de la orden
                    this.orden.user =  this.user;
                    var f = new Date();
                    this.orden.fecha = f.getFullYear() + "-" + f.getMonth() + "-" + f.getDate();
                    
                    await this.axios.post(`/api/ordenes`, this.orden)
                    .this(response => {
                     if(response.status == 201){
                         this.$swal.fire("success",`Su orden ha sido registrada con No. ${response.data.correlativo} pronto nos comunicaremos con usted `);
                         this.orden.detalleOrden = [];
                         this.mostrarOrdenDialog = false;
                     }
                    }).catch(err => {
                        console.log(err);
                    })
                }else{
                    this.$swal.fire("warning","Agregue al menos  un producto a la orden porfavor");
                }
            }
        },
        mounted(){
            this.fetchProductos();
            console.log(this.user);
        }
     }
  
</script>

