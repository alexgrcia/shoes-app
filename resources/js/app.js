/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import axios from 'axios';

//importaciones
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css'

import PrimeVue from 'primevue/config';  
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import Button from 'primevue/button';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import Checkbox from 'primevue/checkbox';
import ColorPicker from 'primevue/colorpicker';
import Dropdown from 'primevue/dropdown';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputMask from 'primevue/inputmask';
import InputNumber from 'primevue/inputnumber';
import InputSwitch from 'primevue/inputswitch';
import InputText from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect'; 
import Password from 'primevue/password';
import RadioButton  from 'primevue/radiobutton';
import Textarea  from 'primevue/textarea';
import SplitButton  from 'primevue/splitbutton';
import Toolbar  from 'primevue/toolbar';
import DataTable  from 'primevue/datatable'; 
import Column  from 'primevue/column'; 
import ColumnGroup  from 'primevue/columngroup';
import Row from 'primevue/row';
import Dialog from 'primevue/dialog';

import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import CategoriaComponent from './components/CategoriaComponent.vue';
app.component('categoria-component', CategoriaComponent);

import MarcaComponent from './components/MarcaComponent.vue';
app.component('marca-component', MarcaComponent);

import TallaComponent from './components/TallaComponent.vue';
app.component('talla-component', TallaComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

//definiendo variables globales
app.config.globalProperties.axios = axios;
app.use(VueSweetalert2);
app.use(PrimeVue);
app.use(ToastService);

//agregamos los componentes de primevue
 //agregamos los componentes de PrimeVue
app.component('Button', Button);
app.component('AutoComplete', AutoComplete);
app.component('Calendar', Calendar);
app.component('Checkbox', Checkbox);
app.component('ColorPicker', ColorPicker);
app.component('Dropdown', Dropdown);
app.component('IconField', IconField);
app.component('InputMask', InputMask);
app.component('InputNumber', InputNumber);
app.component('InputSwitch', InputSwitch);
app.component('InputText', InputText);
app.component('MultiSelect', MultiSelect);
app.component('Password', Password);
app.component('RadioButton', RadioButton);
app.component('Textarea', Textarea);
app.component('SplitButton', SplitButton);
app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('ColumnGroup', ColumnGroup);
app.component('Row', Row);
app.component('Toolbar', Toolbar);
app.component('Dialog', Dialog);
app.component('Toast', Toast);
app.mount('#app');
