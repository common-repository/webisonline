
<div class="webisonline">
    <a style="float:right" href="http://www.WebisOnline.ru" target="_blank">
        <img src="<? echo WEBISONLINE_PLUGIN_URL; ?>img/logo.png" />
    </a>
    <?php echo $error; ?>
<?php 
    if(!$this->site_id){ 
?>
    <div>
        <p>
            <?php _e('Сейчас мы подключим онлайн консультант к вашему сайту.', 'webisonline');?>
        </p>

        <p>
            <?php _e('<h2>Если вы еще не зарегистрированы в системе WebisOnline</h2>введите адрес e-mail и пароль, которые будут использоваться для входа в систему WebisOnline, а также укажите адрес сайта, где будет отображаться язычек онлайн консультанта.', 'webisonline');?>
        </p>  
        <p>
            <?php _e('<i>Если вы зарегистрированы, но вашего сайта нет в нашей системе, заполинте данные для доступа, укажите домен и мы его вам добавим</i>', 'webisonline');?>
        </p>
        <p>
            <?php echo str_replace('%WEBISONLINE_URL%',WEBISONLINE_URL,__('Если вам нужна помощь в установке, задайте вопрос на нашем сайте <a href="%WEBISONLINE_URL%">webisonline.ru</a>','webisonline')); ?>
        </p>
    </div>
    <hr>
    <div>
        <form method="POST">
            <input type='hidden' name='act' value='1'/>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="email"><?php _e('Ваш Email','webisonline'); ?> *</label>
                        </th>
                        <td class='td1'>
                            <input id="email" class="regular-text" type="text" value="<?php echo get_bloginfo( "admin_email");?>" name="email">
                        </td>
                        <td class='td2'><div><?php _e('Укажите email, который вы будете использовать для входа в личный кабинет и в приложение консультанта. Если у вас уже есть аккаунт в системе WebisOnline, введите Email адрес сюда.','webisonline'); ?></div></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="site"><?php _e('Адрес сайта','webisonline'); ?> *</label>
                        </th>
                        <td class='td1'>
                            <input id="url" class="regular-text" type="text" value="<?php echo get_site_url();?>" name="url">
                        </td>
                        <td class='td2'><div><?php _e('Укажите адрес сайта, куда нужно установить онлайн-консультант.','webisonline'); ?></div></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="userPassword"><?php _e('Пароль','webisonline'); ?> *</label>
                        </th>
                        <td class='td1'>
                            <input id="userPassword" class="regular-text" type="password" value="" name="userPassword">
                        </td>
                        <td class='td2'><div><?php _e('Введите пароль для сервиса онлайн-консультаций WebisOnline.','webisonline'); ?></div></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="userPassword"><?php _e('Язык сайта','webisonline'); ?></label>
                        </th>
                        <td class='td1'>
                            <?php
                                echo webisonline::getWebisOnlineLanguagesList();
                            ?>
                            
                        </td>
                        <td class='td2'><div><?php _e('Введите пароль для сервиса онлайн-консультаций WebisOnline.','webisonline'); ?></div></td>
                    </tr>
                    <tr>
                        <td colspan="3"><input class="button button-primary" type="submit" value="<?php _e('Установить WebisOnline прямо сейчас','webisonline'); ?>"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <hr>
    <div>
        <p>
            <?php _e('<h2>Если вы уже зарегистрированы в системе WebisOnline,</h2> то просто укажите адрес вашего сайта или его ID в системе WebisOnline в поле ниже:','webisonline'); ?>
        </p>
        <form method="POST">
            <input type='hidden' name='act' value='2'/>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="bsite"><?php _e('Введите URL или ID сайта...','webisonline'); ?></label>
                        </th>
                        <td class='td1'>
                            <input id="bsite" class="regular-text" type="text" value="<?php echo get_site_url();?>" name="bsite">
                        </td>
                        <td class='td2'>
                            <div><?php _e('Введите URL или ID сайта и виджет будет добавлен на сайт','webisonline'); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><input class="button button-primary" type="submit" value="<?php _e('Установить виджет WebisOnline','webisonline'); ?>"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php 
    } else {
?>
<div>
    <div class="success">
        <?php echo __('Поздравляем! Виджет WebisOnline успешно установлен на ваш сайт.','webisonline'); ?>
    </div>
    <div>
        <p><?php echo str_replace('%WEBISONLINE_URL%',WEBISONLINE_URL,__('<h3>1. Войдите в приложение консультанта</h3><br>Для общения в чате вы можете воспользоваться Веб-версией пульта оператора в личном кабинете <a target="_blank" href="%WEBISONLINE_URL%">webisonline.ru</a>, либо скачать <a target="_blank" href="%WEBISONLINE_URL%/webisonline_setup.exe">приложение консультанта для Windows</a>','webisonline')); ?></p>
        
        <p><?php _e('<h3>2. Настройте дизайн чата в соответствии с цветовой схемой на вашем сайте</h3>','webisonline'); ?>
       
        <?php echo str_replace('%WEBISONLINE_URL%',WEBISONLINE_URL,__('После установки виджета на сайт, вы можете настроить дизайн чата посетителя в соответствии с цветовой схемой, используемой на вашем сайте. Также вы можете настроить автоприглашения и добавить дополнительных операторов. <a target="_blank" href="%WEBISONLINE_URL%">Перейти в личный кабинет</a>','webisonline')); ?> 
        </p>
        <hr>
        <p>
            <?php echo str_replace('%WEBISONLINE_URL%',WEBISONLINE_URL,__('Если вам нужна помощь в установке, задайте вопрос на нашем сайте <a target="_blank" href="%WEBISONLINE_URL%">webisonline.ru</a>','webisonline')); ?>
        </p>
        <p>
            <form method="POST">
            <input type='hidden' name='mode' value='reset'/>
            <input style="float:right" class="button button-warning" type="submit" value="<?php _e('Сбросить настройки виджета','webisonline'); ?>">
            </form>
        </p>
    </div>
    
</div>
<?php } ?>
</div>