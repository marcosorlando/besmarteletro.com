<?php

    use App\Helpers\Check;

    //DEFINE O CALLBACK E RECUPERA O POST
    require_once '../../../_app/Config.inc.php';
    $jSON = null;
    $CallBack = 'contacts';
    $PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    //VALIDA AÇÃO
    if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack){
        //PREPARA OS DADOS
        $Case = $PostData['callback_action'];
        unset($PostData['callback'], $PostData['callback_action']);
        //ELIMINA CÓDIGOS
        $PostData = array_map('strip_tags', $PostData);
        //SELECIONA AÇÃO
        switch ($Case) {
            //EXECUTA DE ACORDO COM CALLBACK-ACTION
            //CITIES
            case 'state':
                $jSON['cities'] = null;
                $PostData['city'] = !empty($PostData['city']) ? $PostData['city'] : null;

                if (!$PostData['uf']){
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Selecione um estado por favor!', E_USER_NOTICE);

                } elseif ($PostData['uf'] && !$PostData['city']) {

                    $Read->FullRead("SELECT id, name, uf FROM " . DB_CITIES . " WHERE uf = :uf ORDER BY name ASC", "uf={$PostData['uf']}");

                    $jSON['cities'] .= "<option value=''>- Selecione a cidade -</option>";
                    if ($Read->getResult()){
                        foreach ($Read->getResult() as $Cities) {
                            extract($Cities);
                            $jSON['cities'] .= "<option value = '{$name}'>{$name} - {$uf}</option> ";
                        }
                    }

                    //var_dump($Read->getResult());

                } else {
                    $jSON['rep'] = null;

                    $Read->ExeRead(DB_REPRESENTATIVES, "WHERE rep_cities LIKE '%' :ct '%' AND rep_uf = :uf", "ct={$PostData['city']}&uf={$PostData['uf']}");


                    if ($Read->getResult()){
                        foreach ($Read->getResult() as $Reps) {
                            extract($Reps);
                            $whats = Check::WHATS($rep_cellphone);

                            $jSON['rep'] .= "<article class='box'>";
                            $jSON['rep'] .= "<h2>{$rep_company}</h2>";
                            $jSON['rep'] .= "<ul><li><h3>{$rep_city} - {$rep_uf}</h3></li>";
                            $jSON['rep'] .= "<li><h4>{$rep_name}</h4></li>";
                            $jSON['rep'] .= "<li><a class='btn' href='tel:{$rep_phone}' title='Clique para Ligar'><i class='icon icon-phone-hang-up'></i>{$rep_phone}</a>";
                            $jSON['rep'] .= "<li><a class='btn btn_whats' target='_blank' href='https://wa.me/{$whats}?text=' title='Enviar mensagem pelo WhatsApp'><i class='icon icon-whatsapp'></i>{$rep_cellphone}</a></li>";
                            $jSON['rep'] .= "<a class='btn btn_blue' href='mailto:{$rep_email}'><i class='icon icon-envelop'></i> ENVIAR E-MAIL</a>";
                            $jSON['rep'] .= "<li><b>Cidades Atendidas:</b> {$rep_cities}</li>";
                            $jSON['rep'] .= "</article>";
                        }
                    } else {
                        $jSON['rep'] = AjaxErro('<b>OPPSSS:</b> Ainda não temos um representante atendendo está cidade, tente uma nas proximidades!', E_USER_NOTICE);
                    }
                }
                break;

            //REPRESENTATIVES
            case 'cities':
                if (in_array('', $PostData)){
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Selecione uma cidade por favor!', E_USER_NOTICE);
                } else {
                    $Read->FullRead("SELECT name FROM " . DB_CITIES . " WHERE id = :ct ORDER BY name", "ct={$PostData['city']}");

                    if ($Read->getResult()){
                        $jSON['trigger'] .= "<fom class='j_submit_cities' name='cities' method='post' enctype='multipart/form-data'>";
                        $jSON['trigger'] .= "<input class='callack' type='hidden' name='callback' value='representatives'>";
                        $jSON['trigger'] .= "<input class='callback-action' type='hidden' name='callback_action' value='cities'>";
                        $jSON['trigger'] .= "<label>Escolha a Cidade no {$PostData['UF']}:</label>";
                        $jSON['trigger'] .= "<select id = 'city' name = 'city' required > ";
                        $jSON['trigger'] .= "<option value = ''>- Selecione a cidade - </option> ";

                        foreach ($Read->getResult() as $Cities) {
                            extract($Cities);

                            $jSON['trigger'] .= "<option value = '{$id}'>{$name} - {$uf}</option> ";
                        }

                        $jSON['trigger'] .= " </select >";
                        $jSON['trigger'] .= "<figure class='form_spinner'> <img class='form_load none' src='" . INCLUDE_PATH . "/images/loading.gif'/> <br> <p class='form_load none'>Carregando...</p> </figure>";
                        $jSON['trigger'] .= " </form > ";

                    }
                }
                break;
            case 'news2':
                if (in_array('', $PostData)){
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Informe seu e-mail por favor!', E_USER_NOTICE);
                } else {
                    if (!Check::Email($PostData['email']) || !filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)){
                        $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> E-mail informado não é válido!', E_USER_NOTICE);
                    } else {
                        $LeadData = [
                            'lead_name' => $PostData['name'], 'lead_email' => $PostData['email'], 'lead_conversion' => $Case
                        ];
                        $Read->FullRead("SELECT lead_email FROM " . DB_LEADS . " WHERE lead_email = :mail", "mail ={$LeadData['lead_email']}");
                        if ($Read->getResult()){
                            $jSON['trigger'] = AjaxErro(" < b>{$LeadData['lead_name']}</b > Seu e - mail já está registrado em nossa Newsletter!", E_USER_NOTICE);
                        } else {
                            $Create = new Create;
                            $Create->ExeCreate(DB_LEADS, $LeadData);
                            $jSON['trigger'] = AjaxErro(" < b>Obrigado!</b > Seu e - mail foi registrado com Sucesso!");
                        }
                    }
                }
                break;

            case 'sac':

                if (in_array('', $PostData)){
                    $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> Para enviar sua mensagem, por favor preencha todos os campos!', E_USER_NOTICE);
                } else {
                    if (!Check::Email($PostData['email']) || !filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)){
                        $jSON['trigger'] = AjaxErro('<b>OPPSSS:</b> E-mail informado não é válido!', E_USER_NOTICE);
                    } else {
                        //$Create = new Create;
                        //$Create->ExeCreate(DB_LEADS, $PostData);
                        $Hora = date('H');
                        $Saudacao = (($Hora > 0) && ($Hora <= 12)) ? 'Bom dia!' : ((($Hora > 12) && ($Hora <= 18)) ? 'Boa tarde! ' : 'Boa noite!');

                        //DISPARA OS ALERTAS POR E-MAIL
                        $MailContent = '
                            <table width="550" style="font-family: Tahoma, sans-serif">
                                <tr><td>
                                    <p>Olá! Meu nome é <b> ' . $PostData['name'] . '</b></p>
                                    <p><br>' . $PostData['message'] . ' </p>
                                                                        
                                    <p style="font-size: 1.2em"><b>Dados para Resposta</b></p>
                                    <p><b>Nome:</b> ' . $PostData['name'] . ' </p>
                                    <p><b>E-mail:</b> ' . $PostData['email'] . ' </p>
                                    <p><b>Telefone:</b> ' . $PostData['telephone'] . '</p>                                    
                              
                                    
                                </td></tr>
                            </table>
                            <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

                        $Agradecimento = '
                            <table width="550" style="font-family: Tahoma, sans-serif;">
                                <tr><td>
                                    <p>' . $Saudacao . ' <b>' . $PostData['name'] . '</b></p>
                                    <p><br>Em breve estaremos respondendo sua mensagem. <br><br> Obrigado! </p><br>
                                                                        
                                    <p style="font-size: 1em;">
                                        <img src="' . INCLUDE_PATH . '/images/logo-mail.png" alt="Atenciosamente, ' .
	                        SITE_NAME . '" title="Atenciosamente, ' . SITE_NAME . '" />
                                        <br><br>
                                        ' . SITE_ADDR_NAME . '<br>Telefone: ' . SITE_ADDR_PHONE_A . '<br>E-mail: ' . SITE_ADDR_EMAIL . '<br><br>
                                        Visite nosso site: <a title="' . SITE_NAME . '" href="' . BASE . '">' . SITE_ADDR_SITE . '</a>
                                    </p>
                                </td></tr>
                            </table>
                        <style>body, img{max-width: 550px !important; height: auto !important;} p{margin-botton: 15px 0 !important;}</style>';

                        $Email = new Email;

                        $Email->EnviarMontando('Contato realizado pelo site', $MailContent, $PostData['name'],
	                        $PostData['email'], SITE_ADDR_NAME, 'sac@besmarteletro.com');

                        if (!$Email->getError()){
                            $Email->EnviarMontando('BE.SMART - Confirmação de recebimento', $Agradecimento,
	                            SITE_ADDR_NAME, 'sac@besmarteletro.com', $PostData['name'], $PostData['email']);
                            $jSON['trigger'] = AjaxErro(" <b>Obrigado!</b> Sua mensagem foi recebida com <i class='icon icon-checkmark'></i>Sucesso!");
                        } else {
                            $jSON['trigger'] = Erro("Desculpe, não foi possível enviar sua mensagem . Entre em contato via por E - mail: " . SITE_ADDR_EMAIL . " . Obrigado!", E_USER_ERROR);
                        }
                    }
                }
                break;

               }
        //RETORNA O CALLBACK
        if ($jSON){
            echo json_encode($jSON);
        } else {
            $jSON['trigger'] = AjaxErro('<b class="icon - warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
            echo json_encode($jSON);
        }
    } else {
        //ACESSO DIRETO
        die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
    }
