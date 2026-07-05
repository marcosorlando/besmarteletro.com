<?php
    
    if(!$Read):
        $Read = new Read;
    endif;
    
    
    $Read->exeRead(DB_PAGES, "WHERE page_name = :nm AND page_status = 1", "nm={$URL[0]}");
    
    if (!$Read->getResult()):
        require REQUIRE_PATH . '/404.php';
        return;
    else:
        extract($Read->getResult()[0]);
    endif;
    
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
            
            $Email = new Email;
            $Email->EnviarMontando(
                $Contato['assunto'],
                $MailContent,
                $Contato['nome'],
                $Contato['email'],
                SITE_ADDR_NAME,
                MAIL_TESTER
            );
            
            if (!$Email->getError()):
                $_SESSION['sucesso'] = "Sua mensagem foi enviada com sucesso!";
                header('Location: ' . BASE . '/contato');
            else:
                Erro(
                    "Desculpe, não foi possível enviar sua mensagem. Entre em contato via " . SITE_ADDR_EMAIL . ". Obrigado!",
                    E_USER_ERROR
                );
            endif;
        endif;
    endif;
    
    
    
    include_once __DIR__ . "/inc/contact.php";
