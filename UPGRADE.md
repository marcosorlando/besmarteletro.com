# MIGRAÇÃO WC PHP --VERSION (7.3 - 8.3)

## [START]: CRIE ARQUIVO DE CONFIGURAÇÃO .env dentro da pasta _app/Config

- Esse arquivo deve armazenar todas as constantes de configuração do projeto deve ficar como o exemplo a seguir.

### Exemplo:

```
# Configurações da aplicação
APP_NAME="Sistema Travi"
APP_ENV=local
APP_URL=https://localhost/cli_travi
APP_DEBUG=true
APP_THEME=travi
APP_TIMEZONE=America/Sao_Paulo
APP_LOCALE=pt_BR

# Configurações de banco de dados
DB_HOST_DEV=localhost
DB_NAME_DEV=cli_travi
DB_USER_DEV=root
DB_PASSWORD_DEV=root
DB_HOST_PRODUCTION=localhost
DB_NAME_PRODUCTION=cli_travi
DB_USER_PRODUCTION=root
DB_PASSWORD_PRODUCTION=root
DB_DRIVER=mysql
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_PORT=3306

# Configurações de e-mail
MAIL_HOST=smtp.example.com #mail host
MAIL_PORT=465 #porta SMTP
MAIL_USER=user@example.com
MAIL_SMTP=user@example.com #E-mail autenticador do envio
MAIL_PASS=password
MAIL_SENDER=password
MAIL_ENCRYPTION=tls
MAIL_MODE=ssl #Encriptação para envio de e-mail [0 não parametrizar / tls / ssl] (Padrão = tls)
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_TESTER=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

# APIs de Pagamento
PAYPAL_CLIENT_ID=seu_client_id
PAYPAL_SECRET=seu_secret
MERCADOPAGO_PUBLIC_KEY=sua_chave_publica
MERCADOPAGO_ACCESS_TOKEN=seu_token_acesso
PAGSEGURO_EMAIL=seu_email
PAGSEGURO_TOKEN=seu_token

# Outras chaves de API
GOOGLE_MAPS_API_KEY=sua_chave_google_maps
RECAPTCHA_SITE_KEY=sua_chave_recaptcha
RECAPTCHA_SECRET_KEY=seu_secret_recaptcha

# Configurações de armazenamento
STORAGE_DRIVER=local
STORAGE_PATH=/uploads

```

## [STEP 1]: UNIFIQUE O CONTEUDO DOS ARQUIVOS:

- _app/Config.inc.php (copie o conteudo e cole no arquivo citado abaixo)
- _app/Config/Config.inc.php

### 1.1

```
```

## [STEP 1.1]: INSTALAR VIA COMPOSER AS SEGUINTES DEPENDENCIAS

```
{
  "name": "localhost/cli_travi",
  "description": "Projeto migrado para autoload via Composer",
  "autoload": {
    "psr-4": {
      "App\\": "_app/"
    },
    "files": [
      "_app/Config/Config.inc.php"
    ]
  },
  "autoload-dev": {
    "classmap": [
      "_app/Library/"
    ]
  },
  "require": {
    "league/glide": "^1.7",
    "phpmailer/phpmailer": "^6.10",
    "vlucas/phpdotenv": "^5.6"
  },
  "require-dev": {
    "rector/rector": "^2.1",
    "phpstan/phpstan": "^2.1",
    "phpstan/extension-installer": "^1.4",
    "phpstan/phpstan-strict-rules": "^2.0",
    "phpcompatibility/php-compatibility": "^9.3",
    "squizlabs/php_codesniffer": "^3.13"
  },
  "config": {
    "allow-plugins": {
      "phpstan/extension-installer": true
    }
  }
}
```

---

## [STEP 2]: ATUALIZE O ARQUIVO rector.php PARA COBRIR TODAS AS PASTAS E REGRAS

- Certifique-se de que todos os diretórios relevantes do projeto estão incluídos em `$rectorConfig->paths`.
- Inclua os sets de qualidade e estilo de código.
- Adicione regras específicas para propriedades dinâmicas, offsets de string e funções removidas.
- Exclua pastas irrelevantes como `vendor`, `uploads`, `var`, `cache`, `node_modules`, `storage`.

### Exemplo de trecho atualizado:

```php
$rectorConfig->paths([
    __DIR__ . '/_api',
    __DIR__ . '/_app',
    __DIR__ . '/_cdn',
    __DIR__ . '/admin',
    __DIR__ . '/themes',
    __DIR__ . '/index.php',
    __DIR__ . '/tests', // se existir
    __DIR__ . '/public', // se scripts PHP existirem aqui
]);

$rectorConfig->sets([
    SetList::PHP_74,
    SetList::PHP_80,
    SetList::PHP_81,
    SetList::PHP_82,
    SetList::PHP_83,
    SetList::CODE_QUALITY,
    SetList::CODING_STYLE,
]);

$rectorConfig->skip([
    __DIR__ . '/vendor',
    __DIR__ . '/uploads',
    __DIR__ . '/var',
    __DIR__ . '/cache',
]);
```

---

## [STEP 3]: EXECUTE OS SCRIPTS DE CHECAGEM E REFACTOR

- Rode os comandos abaixo para garantir compatibilidade e aplicar refactors automáticos:

```sh
brew install php@8.3 #se não tiver instalado
brew link --overwrite --force php@8.3 #forca uso da versao
php -v # conferir versão
composer update
composer lint
composer test
composer phpstan
composer rector
composer phpcs
vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion 8.3- src/
```

---

## [STEP 4]: AJUSTE MANUAL DE BREAKING CHANGES

- Revise e corrija manualmente:
    - Offsets de string usando `{}` → substitua por `[]`
    - Funções removidas: `each()`, `create_function`, `ereg*`, `assert()` dinâmica, `call_user_method`
    - Propriedades dinâmicas (adicione explicitamente ou use atributos)
    - Qualquer acesso a propriedades não declaradas em classes

---

## [STEP 5]: GARANTA QUE TODOS OS TESTES PASSAM

- Execute todos os testes automatizados.
- Corrija eventuais falhas relacionadas à sintaxe, tipagem ou comportamento alterado.

---

## [STEP 6]: DOCUMENTE TODAS AS MUDANÇAS

- Registre cada alteração relevante neste arquivo e no `CHANGELOG.md`.
- Detalhe eventuais decisões técnicas e pontos de rollback.

---

## [STEP 7]: VALIDE EM AMBIENTE DE STAGING

- Suba o projeto em ambiente de homologação/staging com PHP 8.3.
- Valide fluxos críticos e integração com APIs externas.

## [STEP 8]: AJUSTES DE PADRONIZAÇÃO E COMPATIBILIDADE PHP 8.3

### 8.1 Padronização PSR-12 e Modernização de Código

- Todas as classes em `_app/Conn/` foram atualizadas para:
    - Usar `namespace App\Conn;`
    - Métodos e propriedades em camelCase.
    - Tipagem de propriedades e métodos (type hints) compatíveis com PHP 8.3.
    - Remoção de funções e sintaxes depreciadas.
    - Substituição de métodos como `Erro()` por `trigger_error()` ou exceção.
- Classes de configuração e helpers também receberam namespaces e padronização.

### 8.2 Ajustes em Arquivos de Configuração

- Adicionado no topo de `Agency.inc.php` e `Client.inc.php`:
  ```php
  if (!isset($WorkControlDefineConf)) {
      $WorkControlDefineConf = null;
  }
  ```
- Todas as definições de constantes agora usam `if (!defined(...))` para evitar redefinição.
- Corrigido o fluxo de carregamento dos arquivos de configuração para evitar múltiplas execuções.

### 8.3 Composer e Autoload

- Mantido apenas o necessário em `"autoload": { "files": [...] }` no `composer.json`.
- Recomendada a inclusão manual dos arquivos de configuração quando necessário.

### 8.4 Correções Automáticas de Código

- Rodado o comando:
  ```
  vendor/bin/phpcbf --standard=PSR12 _app/
  ```
  para corrigir automaticamente problemas de indentação, chaves, espaços e outros detalhes de formatação.

### 8.5 Ajustes Manuais

- Corrigidos nomes de métodos e propriedades para camelCase.
- Adicionados namespaces em todas as classes.
- Corrigidos avisos de redefinição de constantes.
- Corrigidos avisos de variáveis indefinidas nos arquivos de configuração.

### 8.6 Avisos de Depreciação

- O aviso `auto_detect_line_endings is deprecated` é gerado pelo próprio PHP_CodeSniffer e não afeta o funcionamento do
  projeto.
- Recomenda-se manter o PHPCS atualizado para futuras correções.

### 8.7 index.php

- Garantido que todas as dependências e constantes estejam carregadas corretamente.
- Código revisado para compatibilidade com PHP 8.3.

---

## [CHECKLIST FINAL]

- [x] Código compatível com PHP 8.3.
- [x] Padrão PSR-12 aplicado.
- [x] Sem redefinição de constantes.
- [x] Sem warnings de variáveis indefinidas.
- [x] Classes com namespace e métodos camelCase.
- [x] Configuração do Composer revisada.
- [x] PHPCS e PHPCBF rodando sem erros críticos.

---

**Dica:**  
Após atualizar, rode sempre:

```sh
composer dump-autoload
composer phpcs
```

para garantir que tudo está correto.

---

## [ROLLBACK]: COMO REVERTER

- Cada etapa deve ser commitada separadamente.
- Para rollback, reverta o(s) commit(s) correspondente(s) via `git revert`.
- Mantenha backup do banco de dados e arquivos antes de cada etapa crítica.
