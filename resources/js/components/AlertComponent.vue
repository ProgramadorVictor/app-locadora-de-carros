<template>
    <!-- <div :class="`alert alert-${tipo}`" role="alert"> -->
    <div :class="estilo" role="alert">
        {{ titulo }}
        <hr>
        <!-- {{ detalhes.data.message }} -->
        <!-- <span v-if="detalhes.data.message">{{ detalhes.data.message }}</span> -->
        <span v-if="detalhes.data.id">{{ 'O registro foi cadastro. ID: ' + detalhes.data.id }}</span>
        <ul v-if="detalhes.data.errors">
            <li v-for="error, key in detalhes.data.errors" :key="key">{{ error[0] }}</li>
        </ul>
    </div>
</template>
<!-- 
    IMPORTANTE: Para usamos os componentes Vue.js, temos que colocar aqui em resources/js/app.js, para que fiquem disponivel no contexto do blade.
    Assim podemos usar o Vue.js juntamente com o motor de visualização do Laravel.
-->
<script>
    export default {
        props:{
            tipo: String,
            titulo: String,
            detalhes: Array,
        },
        mounted() {
            console.log('Component mounted.')
        },
        computed:{
            estilo(){
                return 'alert alert-'+this.tipo
            }
        }
    }
</script>
