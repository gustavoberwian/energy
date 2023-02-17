<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .container{
        margin-top:20px;
    }
    .image-preview-input {
        position: relative;
        overflow: hidden;
        margin: 0px;
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }
    .image-preview-input input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }
    .image-preview-input-title {
        margin-left:2px;
    }
    .image-preview-clear {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

<section role="main" class="content-body">
    <!-- start: page -->
    <div class="row">
        <div class="col-lg-4 col-xl-4 mb-4 mb-xl-0 mt-4 mt-xl-0">
            <section class="card">
                <div class="card-body">
                    <div class="thumb-info mb-3 text-center">
                        <img src="<?php echo avatar($user->avatar); ?>" class="rounded img-fluid" alt="<?php echo $user->username; ?>">
                        <div class="thumb-info-edit">
                            <div class="info-edit"><i class="fas fa-edit"></i></div>
                        </div>
                        <div class="thumb-info-title">
                            <span class="thumb-info-inner"><?php echo $user->nome; ?></span>
                            <span class="thumb-info-type"><?= user_groups_nice($user->id, $this->ion_auth); ?></span>
                        </div>
                    </div>
                    <hr class="dotted short">
                    <h5 class="mb-2 mt-3">Estatísticas</h5>
                    <p class="mb-0">Membro desde <?php echo date("d/m/Y", $user->created_on); ?></p>
                    <p class="mb-0">Último login em <?php echo date("d/m/Y H:i", $_SESSION['old_last_login']); ?></p>
                </div>
            </section>
        </div>
        <div class="col-lg-8 col-xl-8">
            <div class="tabs">
                <ul class="nav nav-tabs tabs-primary">
                    <li class="nav-item active">
                        <a class="nav-link" data-bs-target="#edit" data-bs-toggle="tab">Informações</a>
                    </li>
                    <?php if (!$this->ion_auth->in_group('demo')) : ?>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-target="#image" data-bs-toggle="tab">Imagem</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div id="edit" class="tab-pane active">
                        <?php if (isset($error) && $error) { ?>
                            <div class="alert alert-danger mx-3 mb-0">Não foi possível atualizar os dados. Tente novamente em alguns minutos.</div>
                        <?php } else if (isset($error) && !$error) { ?>
                            <div class="alert alert-success mx-3 mb-0">Seus dados foram atualizados com sucesso.</div>
                        <?php } ?>
                        <form id="profile" class="profile p-3" action="<?php echo site_url('shopping/profile'); ?>" method="post">
                            <input type="hidden" name="user" value="<?php echo md5("easymeter".$user->id."123456"); ?>">
                            <div class="form-row mb-3">
                                <div class="form-group col-md-12">
                                    <label>E-mail principal</label>
                                    <input type="text" class="form-control" value="<?php echo $user->email; ?>" readonly>
                                </div>
                            </div>

                            <hr class="dotted">

                            <h4 class="mb-3">Alterar Senha</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nova Senha</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                    <?php echo form_error('password'); ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirmação</label>
                                    <input type="password" class="form-control"  name="confirm" id="confirm">
                                    <?php echo form_error('confirm'); ?>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12 text-end mt-3">
                                    <button class="d-none" type="reset">Clear</button>
                                    <button class="btn btn-primary modal-confirm" <?= ($this->ion_auth->in_group('demo')) ? "disabled" : ""; ?>>Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php if (!$this->ion_auth->in_group('demo')) : ?>
                        <div id="image" class="tab-pane">
                            <form id="avatar" class="avatar p-3" action="<?php echo site_url('shopping/profile'); ?>" method="post">
                                <input type="hidden" name="user" value="<?php echo md5("easymeter".$user->id."123456"); ?>">
                                <input type="hidden" name="crop-image" id="crop-image" value="">
                                <input type="hidden" name="facebook" value="">
                                <div class="form-group row">
                                    <div class="col-md-12 text-center">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="input-append">
                                                <div class="uneditable-input">
                                                    <i class="fas fa-file fileupload-exists"></i>
                                                    <span class="fileupload-preview"></span>
                                                </div>
                                                <span class="btn btn-default btn-file">
                                                                <span class="fileupload-exists">Mudar</span>
                                                                <span class="fileupload-new">Escolher</span>
                                                                <input type="file" name="upload_image" id="upload_image" accept="image/*" />
                                                            </span>
                                                <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remover</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="img-preview"></div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mt-3">
                                        <?php if($user->fb_id) { ?>
                                            <button class="btn btn-facebook btn-facebook" value="facebook"><i class="fab fa-facebook me-2"></i> Usar perfil do Facebook</button>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6 text-end mt-3">
                                        <button class="d-none" type="reset">Clear</button>
                                        <button class="btn btn-primary btn-update" value="upload">Atualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end: page -->
</section>
<?php if (isset($error) && !$error) { ?>
    <script>
        document.getElementById('profile').reset();
        document.getElementById('avatar').reset();
    </script>
<?php } ?>
