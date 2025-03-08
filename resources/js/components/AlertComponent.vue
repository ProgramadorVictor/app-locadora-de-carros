<template>
    <!-- <div :class="`alert alert-${tipo}`" role="alert"> -->
    <div :class="estilo" role="alert">
        A simple success alert—check it out!
    </div>
</template>
<!-- 
    IMPORTANTE: Para usamos os componentes Vue.js, temos que colocar aqui em resources/js/app.js, para que fiquem disponivel no contexto do blade.
    Assim podemos usar o Vue.js juntamente com o motor de visualização do Laravel.
-->
<script>
    export default {
        props:{
            tipo: String
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
