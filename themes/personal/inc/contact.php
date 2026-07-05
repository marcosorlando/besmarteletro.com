<section class="contact" id="contact">
    <h2 class="heading">Me <span>Contate!</span> </h2>
    
    
    
    <form action="#" method="post">
        
        <input type="hidden" name="action" value="contact"/>
        <?php
    
            $Contato = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
            if ($Contato && $Contato['action'] == 'contact'):
                unset($Contato['action']);
        
                if (in_array('', $Contato)):
                    Erro("Para enviar seu contato, favor preencha todos os campos!", E_USER_WARNING);
                elseif (!Check::Email($Contato['email']) || !filter_var($Contato['email'], FILTER_VALIDATE_EMAIL)):
                    Erro("Desculpe, mas o e-mail que você informou não tem um formato válido!", E_USER_ERROR);
                else:
                    array_map('strip_tags', $Contato);
            
                    $MailContent = '<table width="550" style="font-family: Verdana, sans-serif;">
                         <tr><td>
                          <font face="Trebuchet MS" size="3">
                           <p>Novo contato de ' . $Contato['nome'] . '</p>
                           <p>Telefone: ' . $Contato['telefone'] . '</p>
                           <p><b>MENSAGEM:</b> ' . $Contato['mensagem'] . ' </p>
                          </font>
                          <p style="font-size: 0.875em;">
                          <img src="' . BASE . '/admin/_img/mail.jpg" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /><br><br>
                           ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
                           <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a><br>' . SITE_ADDR_ADDR . '<br>'
                        . SITE_ADDR_CITY . '/' . SITE_ADDR_UF . ' - ' . SITE_ADDR_ZIP . '<br>' . SITE_ADDR_COUNTRY . '
                          </p>
                          </td></tr>
                        </table>
                        <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';
    
                    $MailReturn = '<table width="550" style="font-family: Verdana, sans-serif;">
                         <tr><td>
                          <font face="Trebuchet MS" size="3">
                           <p>Olá ' . $Contato['nome'] . '</p>
                           <p>Esse e-mail e apenas informar que recebemos sua mensagem! Em breve estaremos respondendo.</p>
                          </font>
                          <p style="font-size: 0.875em;">
                          <img src="' . BASE . '/admin/_img/mail.jpg" alt="Atenciosamente ' . SITE_NAME . '" title="Atenciosamente ' . SITE_NAME . '" /><br><br>
                           ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
                           <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a><br>' . SITE_ADDR_ADDR . '<br>'
                        . SITE_ADDR_CITY . '/' . SITE_ADDR_UF . ' - ' . SITE_ADDR_ZIP . '<br>' . SITE_ADDR_COUNTRY . '
                          </p>
                          </td></tr>
                        </table>
                        <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';
            
                    $Email = new Email;
                    $Email->EnviarMontando(
                        $Contato['assunto'],
                        $MailContent,
                        $Contato['nome'],
                        $Contato['email'],
                        SITE_ADDR_NAME,
                        SITE_ADDR_EMAIL
                    );
            
                    if (!$Email->getError()):
    
                        $Email->EnviarMontando(
                            $Contato['nome'] . ' recebemos seu contato!',
                            $MailReturn,
                            SITE_ADDR_NAME,
                            SITE_ADDR_EMAIL,
                            $Contato['nome'],
                            $Contato['email']
                        );
                        
                        $_SESSION['sucesso'] = "Sua mensagem foi enviada com sucesso!";
                    
                        header('Location: ' . BASE .'#contact');
                    else:
                        Erro(
                            "Desculpe, não foi possível enviar sua mensagem. Entre em contato via " . SITE_ADDR_EMAIL . ". Obrigado!",
                            E_USER_ERROR
                        );
                    endif;
                endif;
            endif;
            
            
            if (!empty($_SESSION['sucesso']) && empty($Contato)):
                Erro($_SESSION['sucesso']);
                unset($_SESSION['sucesso']);
            endif;
        ?>

        <div class="input-box">
            <input name="nome" type="text" placeholder="Nome completo" required >
            <input name="email" type="email" placeholder="Endereco de E-mail" required>
        </div>
        <div class="input-box">
            <input name="telefone" class="maskPhone" type="text" id="telefone" placeholder="Telefone" maxlength="15" />
            <input name="assunto" type="text"  placeholder="Assunto do E-mail" required>
        </div>
        <textarea name="mensagem" id="mensagem" cols="30" rows="6" placeholder="Sua mensagem" required></textarea>
        
        <button class="btn" type="submit"><i class='bx bx-mail-send'></i> Enviar Mensagem</button>
       
    </form>
</section>
