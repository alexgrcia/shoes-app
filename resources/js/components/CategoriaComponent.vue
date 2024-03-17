<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Listado de categorias</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods:{
            async fetchCategorias(){
                await this.axios.get('/api/categorias')
                .then(response => {
                    console.log(response.data)
                })
            }
        },
        mounted() {
            this.fetchCategorias();
            console.log('Component mounted.')
        }
    }
</script>
