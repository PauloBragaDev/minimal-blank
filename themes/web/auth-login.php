<?php $this->layout("_theme"); ?>
<div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-group d-block d-md-flex row">
                    <div class="card col-md-7 p-4 mb-0">
                        <div class="card-body">
                            <form action="<?= url("/auth"); ?>" method="post">
                                <div class="ajax_response"><?= flash(); ?></div>
                                <?= csrf_input(); ?>
                                <h1>👋🏻<br/>Bem vindo a <?= CONF_SITE_NAME; ?>! </h1>
                                <p class="text-body-secondary">Faça login em sua conta e comece a aventura</p>
                                <div class="input-group mb-3">
                                    <input class="form-control" type="email" name="email"
                                            placeholder="Enter your email or username"
                                            autofocus value="<?php echo $cookie; ?>" />
                                </div>
                                <div class="input-group mb-2">
                                    <input class="form-control" type="password" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="remember-me" <?= (!empty($cookie) ? "checked" : ""); ?> name="save"/>
                                    <label class="form-check-label" for="remember-me"> Lembrar </label>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-primary px-4 submit" type="submit">Entrar</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button class="btn btn-link px-0" type="button">Esqueceu a senha?</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card col-md-5 text-white bg-primary py-5">
                        <div class="card-body text-center">
                    <div>
                    <h2>Sign up</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <a href="<?php echo url("/register"); ?>" class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>