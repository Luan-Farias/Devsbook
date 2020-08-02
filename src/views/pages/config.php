<?= $render('header', ['loggedUser' => $loggedUser]); ?>

<section class="container main">
    <?= $render('sidebar', ['activeMenu' => 'config']); ?>

    <section class="feed mt-10">
        <h1>Configurações</h1>
        <div class="row">
            <form  enctype="multipart/form-data" action="<?= $base; ?>/config" method="post" class="form-config">
                <?php  if(!empty($flash)): ?>
                    <div class="flash"><?= $flash; ?></div>
                <?php endif; ?>
                <label>
                    <span>Novo Avatar:</span>
                    <input type="file" name="avatar" id="avatar" />
                    <img src="<?= $base; ?>/media/avatars/<?= $loggedUser->avatar; ?>" alt="Seu Avatar" width="200"/>
                </label>

                <label>
                    <span>Nova Capa:</span>
                    <input type="file" name="cover" id="cover" />
                    <img src="<?= $base; ?>/media/covers/<?= $loggedUser->cover; ?>" alt="Seu Avatar" style="width: 320px; margin-bottom: 10px;" />
                </label>

                <hr/>
                
                <label>
                    <span>Nome:</span>
                    <input type="text" name="name" value="<?= $loggedUser->name; ?>" id="name" required/>
                </label>
                
                <label>
                    <span>Data de Nascimento:</span>
                    <input type="text" name="birthdate" value="<?= date('d/m/Y' ,strtotime($loggedUser->birthdate)); ?>" id="birthdate" required  />
                </label>
                
                <label>
                    <span>E-mail:</span>
                    <input type="email" name="email" value="<?= $loggedUser->email; ?>" id="email" required  />
                </label>
                
                <label>
                    <span>Cidade:</span>
                    <input type="text" name="city" id="city" value="<?= $loggedUser->city ?? ''; ?>"  />
                </label>
                
                <label>
                    <span>Trabalho:</span>
                    <input type="text" name="work" id="work" value="<?= $loggedUser->work ?? ''; ?>" />
                </label>

                <hr />
                
                <label>
                    <span>Nova Senha:</span>
                    <input type="password" id="password" name="password" />
                </label>
                
                <label>
                    <span>Confirmar Nova Senha:</span>
                    <input type="password" id="password_confirmation" name="password_confirmation" />
                </label>

                <label class="submit">
                    <input type="submit" value="Salvar" class="button" />
                </label>                
            </form>
        </div>
    </section>

</section>

<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'),
        {
            mask: '00/00/0000'
        }
    );
</script>

<?= $render('footer'); ?>