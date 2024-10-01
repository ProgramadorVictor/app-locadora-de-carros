<template>
    <!-- Movemos de login.blade.php e removemos todo o conteudo que não é HTML ou Js. -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form method="POST" action="">
                            
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Props do Vue</label>

                                <div class="col-md-6">
                                    <input id="props" type="text" class="form-control" name="props" :value="kebabCase +', ' + abcde" disabled autocomplete="off">

                                </div>
                            </div>
                            <input type="hidden" name="_token" :value="csrf_token"><!-- Passando o @csrf_token e padronizando com o que é esperado no back-end da aplicação. -->
                            
                            <div class="text-center">
                                <!-- {{ 'Nome: ' + nome}}, {{ 'UID: ' + uid }}, {{ 'Idade: ' + idade }} -->
                            </div>
                            
                            <!-- Imprimindo os props passados, para o componente. -->
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Endereço de e-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Mantenha-me conectado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="">
                                        Esqueci a senha?
                                    </a>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<!-- 
    IMPORTANTE: Para usamos os componentes Vue.js, temos que colocar aqui em resources/js/components, para que fiquem disponivel no contexto do blade.
    Assim podemos usar o Vue.js juntamente com o motor de visualização do Laravel.
-->
<script>
    export default {
        props:{
            //Utilizando o props desta forma podemos validar, requerir.
            nome: {
                type: String, //Tipado
                required: true, //Requerido
            },
            uid: String, //Tipado
            idade: {
                default: 0, //Valor padrao se nao passado.
            },
            kebabCase: String,
            abcde: String,
            csrf_token: {
                type: String,
                required: true
            },
        },
        // props: ['nome','uid', 'idade'], //Uma forma mais simples sem a validação.
        mounted() {
            console.log('Component mounted.')
            console.log('Vue.js é foda, não tem como Laravel + Vue :D, feliz da vida... feliz da vida... xD');
        }
    }
</script>
