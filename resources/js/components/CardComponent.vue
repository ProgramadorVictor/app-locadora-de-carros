<template>
    <div class="card mb-1">
        <div class="card-header">{{ titulo }}</div>
        <div class="card-body">
            <slot name="conteudo"></slot>
        </div>
        <div class="card-footer">
            <slot name="rodape"></slot>
        </div>
    </div>
</template>
<script>
    export default {
        
    }
</script>
