<?php

    declare(strict_types=1);

    return [
        // Site config
        'SITE_NAME' => 'BE.SMART',
        'SITE_SUBNAME' => 'Eletro',
        'SITE_DESC' => 'Bem-vindo à BE.SMART, o seu destino para uma vida inteligente e conectada em sua casa. Nós somos mais do que uma loja de eletrodomésticos; somos seus parceiros na busca por praticidade, eficiência e inovação para tornar o seu lar mais inteligente.',
        'SITE_FONT_NAME' => 'Barlow+Semi+Condensed',
        'SITE_FONT_WEIGHT' => '300;400;600;700',

        // Dados do cliente
        'SITE_ADDR_NAME' => 'BE.SMART',
        'SITE_ADDR_RS' => 'Be Smart Eletro',
        'SITE_ADDR_EMAIL' => 'vendas@besmarteletro.com',
        'SITE_ADDR_SITE' => 'besmarteletro.com',
        'SITE_ADDR_CNPJ' => '',
        'SITE_ADDR_IE' => '',
        'SITE_ADDR_PHONE_A' => '(54) 3358-6530',
        'SITE_ADDR_SAC' => '0800 999 2000',
        'SITE_ADDR_WHATS' => '(54) 99662-1197',
        'SITE_ADDR_WHATS_TRIM' => static fn(): string => \str_replace(
            ' ',
            '',
            '55' . (defined('SITE_ADDR_WHATS') ? SITE_ADDR_WHATS : '')
        ),
        'SITE_ADDR_ADDR' => 'Av. Julio Vanzin, 1600 - Área Industrial',
        'SITE_ADDR_CITY' => 'Lagoa Vermelha',
        'SITE_ADDR_DISTRICT' => 'Área Industrial',
        'SITE_ADDR_UF' => 'RS',
        'SITE_ADDR_ZIP' => '93300-000',
        'SITE_ADDR_COUNTRY' => 'Brasil',

        // Social config
        'SITE_SOCIAL_NAME' => 'BE.SMART',
        'SITE_SOCIAL_FB' => 1,
        'SITE_SOCIAL_FB_APP' => '',
        'SITE_SOCIAL_FB_AUTHOR' => 'MundoBeSmart',
        'SITE_SOCIAL_FB_PAGE' => 'MundoBeSmart',
        'SITE_SOCIAL_FB_PAGE_ID' => '',
        'SITE_SOCIAL_TWITTER' => '',
        'SITE_SOCIAL_YOUTUBE' => '',
        'SITE_SOCIAL_INSTAGRAM' => 'mundobesmart',
        'SITE_SOCIAL_LINKEDIN' => '',
    ];
