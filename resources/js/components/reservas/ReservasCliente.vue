<style>
/* Estilos para el menú horizontal */
nav ul {
    list-style-type: none; /* Quitamos las viñetas de la lista */
    text-align: center; /* Centramos los ítems del menú */
}
nav li {
    display: inline; /* Ítems del menú en una línea horizontal */
    margin-right: 20px; /* Espacio entre los ítems */
}
nav a {
    text-decoration: none; /* Quitamos el subrayado de los enlaces */
    font-size: 18px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
}
.container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
</style>
<template style="max-width:800px;">        
        <nav>
            <ul>
                <li><a href="/">Inicio</a></li>
                <li><a href="/nosotros">Sobre nosotros</a></li>
            </ul>
        </nav>
    <div class="card">

    </div>
    <div class="card">
        <DataView :value="productos" :layout="layout"  paginator :rows="6">
            <template #header>
                <div class="flex justify-content-end">
                    <Button label="Ver Reserva" link @click="viewReserva" v-if="orden.detalleReserva.length > 0" ></Button>
                    <DataViewLayoutOptions v-model="layout" />
                </div>
            </template>

            <template #list="slotProps">
                <div class="grid grid-nogutter">
                    <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                        <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3" :class="{ 'border-top-1 surface-border': index !== 0 }">
                            <div class="md:w-10rem relative">
                               <!-- <img class="block xl:block mx-auto border-round w-full" :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`" :alt="item.name" />-->
                               <img class="block xl:block mx-auto border-round w-full" :src="getImagenSrc(item['imagenes'][0])" :alt="item.nombre" />

                               <Tag :value="item.disponible" class="absolute" style="left: 4px; top: 4px"></Tag>
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
                                        <Button icon="pi pi-shopping-cart" label="Agregar a Reserva"  @click="addToReserva(item)" :disabled="item.stock === '0'" class="flex-auto md:flex-initial white-space-nowrap"></Button>
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
                                    <img class="border-round w-full" :src="getImagenSrc(item['imagenes'][0])" :alt="item.nombre" style="max-width: 300px" />
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
                                        <Button icon="pi pi-shopping-cart" label="Reservar" :disabled="item.stock === '0'" class="flex-auto white-space-nowrap" @click="addToReserva(item)" ></Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
     </DataView>
        <!--Modal para mostrar la orden y despues hacerla efectiva-->
        <Dialog v-model:visible="mostrarOrdenDialog" :style="{width: '650px'}" header="Detalle de Reserva" :modal="true" class="p-fluid">
         <div class="row">
            <div class="col">
                <label>Fecha Orden: {{ orden.fecha }}</label>
            </div>
         </div>
          <div class="row">
            <div class="col">
                <label>Cliente: {{ user.name }}</label>
            </div>
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
                        <tr v-for="item in orden.detalleReserva" :key="item.id">
                            <td>{{ item.zapato.nombre }},{{ item.zapato.descripcion }}, modelo: {{item.zapato.modelo }}</td>
                            <td>{{item.zapato.marca.nombre}}</td>
                            <td>{{item.cantidad}}</td>
                            <td>${{item.zapato.precio}}</td>
                            <td>${{item.cantidad * item.zapato.precio}}</td>
                            <td>
                                <Button icon="pi pi-trash" outlined rounded severity="danger" @click="deleteItem(item)" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Total de la reserva</td>
                            <td><b>${{ total }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <template #footer>
                <Button label="Cerrar" icon="pi pi-items" text @click="hideDialog" />
                 <Button label="Confirmar Reserva" icon="pi pi-check" text @click="saveReserva" v-if="orden.detalleReserva.length > 0" />
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
                fecha:new Date(Date.now() - new Date().getTimezoneOffset()*60000),
                estado:'R',
                monto: new Number("0").toFixed(2),
                user:null,
                detalleReserva:[]
              },
                editedOrden: -1,
                search:'',
                submitted:false,
                mostrarOrdenDialog: ref(false),
                layout : ref('list')
           }
        },
        computed:{ 
            total(){
                var totalOrder = 0;
                this.orden.detalleReserva.forEach(element => {
                    totalOrder += (element.zapato.precio * element.cantidad);
                });
                this.orden.monto = totalOrder;
                return totalOrder;
            }
        },
        methods:{
               async fetchProductos(){
                await this.axios.get(`/api/zapatos`)
                .then(response => {
                    this.productos = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
            },
            addToReserva(item){
                this.orden.detalleReserva.push({
                    id:null,
                    cantidad: item.cantidad,
                    precio: item.precio,
                    zapato_id:item.id,
                    descripcion: "reservado",
                    zapato: item
                });
            },
            hideDialog(){
                this.mostrarOrdenDialog = false;
                this.submitted = false;
            },
            viewReserva(){
                 this.mostrarOrdenDialog = true;
                this.submitted = true;
            },
            deleteItem(item){
                //primero obtenemos el indice el item a eliminar
                let index = this.orden.detalleReserva.indexOf(item);
                //quitamos el item del arreglo
                this.orden.detalleReserva.splice(index,1);

            },
            getImagenSrc(imagen){
                let nombre = imagen != null ? imagen.nombre : ''
                return '/images/productos/' + nombre ;
            }
            ,
            async saveReserva(){
                if(this.orden.detalleReserva.length > 0){
                    //setear datos faltantes de la orden
                    this.orden.user =  this.user;
                    var f = new Date();
                    this.orden.fecha = f.getFullYear() + "-" + f.getMonth() + "-" + f.getDate();
                    console.log(this.orden);
                    await this.axios.post(`/api/reservas`, this.orden)
                    .then(response => {
                     if(response.status == 201){
                         this.$swal.fire("Reserva registrada",`Su reserva ha sido registrada con No. ${response.data.data.id} pronto nos comunicaremos con usted `);
                         this.orden.detalleReserva = [];
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

