<template>
    <div class="form-group">
        <label v-bind:for="id" class="form-label">{{ titulo }}:</label>
        <slot></slot> <!-- Pega o conteúdo interno entre a tag e fechamento e jogar para o local respectivo onde a tag slot está localizada. -->
        <div id="idHelp" class="form-text text-muted">{{ textoAjuda }}</div>
    </div>
</template>
<!-- 
    IMPORTANTE: Para usamos os componentes Vue.js, temos que colocar aqui em resources/js/app.js, para que fiquem disponivel no contexto do blade.
    Assim podemos usar o Vue.js juntamente com o motor de visualização do Laravel.
-->
<script>
    export default {
        props: ['id','titulo','idHelp','textoAjuda']
    }
</script>
