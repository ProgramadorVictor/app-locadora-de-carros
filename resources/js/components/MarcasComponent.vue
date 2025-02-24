<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Buscar de marcas">
                    <template #conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <!-- A utilização do ífen '-' faz com que seja reconhecida a camelCase no vue.js id-help == idHelp -->
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do registro.">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </template>]
                    <template #rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>
                <card-component titulo="Relação de marcas">
                    <template #conteudo>
                        <table-component></table-component>
                    </template>
                    <template #rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template #conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Usuário, informar o nome da marca">
                        <input type="text" class="form-control" id="inputNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                    {{ nomeMarca }}
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione, uma imagem no formato .png">
                        <input type="file" class="form-control-file" id="inputNome" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                    {{ arquivoImagem }}
                </div>
            </template>
            <template #rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: [],
            }
        },
        methods:{
            carregarImagem(e){
                this.arquivoImagem = e.target.files; 
            },
            salvar(){
                console.log(this.nomeMarca, this.arquivoImagem);

                let formData = new FormData(); //Js programando um form.
                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.arquivoImagem[0]);

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        console.log(response);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }
        }
    }
</script>
