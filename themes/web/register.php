<?php 
    $this->layout("_theme"); 
    $this->start("styles"); 
?>
<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }
    .register-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
    }
    .register-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .register-header h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .register-header p {
        color: #6c757d;
        margin-bottom: 0;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .btn-register {
        padding: 0.75rem;
        font-weight: 500;
    }
</style>
<?php $this->end(); ?>

<div class="bg-body-tertiary register-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card register-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="register-header">
                            <h1><?= CONF_SITE_NAME; ?> - Criar Conta</h1>
                            <p>Preencha os dados abaixo para se cadastrar</p>
                        </div>

                        <form action="<?= url("/register"); ?>" method="post" id="registerForm">
                            <div class="ajax_response"><?= flash(); ?></div>
                            <?= csrf_input(); ?>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="name" 
                                    name="name" 
                                    placeholder="Digite seu nome completo"
                                    required
                                    autofocus
                                />
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="seu@email.com"
                                    required
                                />
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone</label>
                                <input 
                                    type="tel" 
                                    class="form-control" 
                                    id="phone" 
                                    name="phone" 
                                    placeholder="(00) 00000-0000"
                                    required
                                />
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Mínimo 6 caracteres"
                                    required
                                    minlength="6"
                                />
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">Confirmar Senha</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password_confirm" 
                                    name="password_confirm" 
                                    placeholder="Digite a senha novamente"
                                    required
                                    minlength="6"
                                />
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-register">
                                    Cadastrar
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0">
                                    Já possui uma conta? 
                                    <a href="<?= url("/"); ?>" class="text-decoration-none">Fazer login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start("scripts"); ?>
<script src="<?= url("/node_modules/jquery-mask-plugin/dist/jquery.mask.min.js"); ?>"></script>
<script>
    $(document).ready(function() {
        // Máscara para telefone
        $('#phone').mask('(00) 00000-0000');

        // Validação de confirmação de senha
        $('#password_confirm').on('keyup', function() {
            var password = $('#password').val();
            var passwordConfirm = $(this).val();
            
            if (password !== passwordConfirm) {
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
        });

 
    });
</script>
<?php $this->end(); ?>
