# 🚀 INSTRUÇÕES DE DEPLOYMENT - TIJOLO DA FÉ
## Deploy no Hostinger - Passo a Passo

---

## 📦 ARQUIVO PREPARADO

✅ **Arquivo:** `tijolo_da_fe_hostinger.zip` (25MB)
✅ **Localização:** `/tmp/tijolo_da_fe_hostinger.zip`

---

## 🎯 PASSO 1: ACESSAR O HOSTINGER

1. Acesse: https://hpanel.hostinger.com
2. Faça login com suas credenciais
3. Selecione seu plano de hospedagem

---

## 🗄️ PASSO 2: CRIAR BANCO DE DADOS MYSQL

### 2.1 - Criar o Banco

1. No hPanel, clique em **"Bancos de Dados"** → **"MySQL Databases"**
2. Clique em **"Criar novo banco de dados"**
3. Preencha:
   - **Nome do banco:** `tijolodafe` (ou nome de sua preferência)
   - O sistema criará automaticamente: `u123456789_tijolodafe`
4. Anote as informações:
   ```
   Host: localhost
   Database: u123456789_tijolodafe
   Username: u123456789_user
   Password: [senha gerada automaticamente]
   ```

### 2.2 - Importar Estrutura do Banco

⚠️ **IMPORTANTE:** Você precisa ter o arquivo .sql do seu banco de dados atual!

1. No hPanel, clique em **"phpMyAdmin"**
2. Faça login (use as mesmas credenciais do banco)
3. No menu lateral esquerdo, clique no banco que você criou
4. Clique na aba **"Importar"**
5. Clique em **"Escolher arquivo"**
6. Selecione o arquivo `.sql` do seu banco
7. Role até o final e clique em **"Executar"**
8. Aguarde a confirmação: "Importação finalizada com sucesso"

**Se você NÃO tem o arquivo SQL:**
- Você precisará exportar do seu ambiente local primeiro
- Use phpMyAdmin local ou comando: `mysqldump -u usuario -p banco > backup.sql`

---

## 📁 PASSO 3: FAZER UPLOAD DOS ARQUIVOS

### 3.1 - Acessar Gerenciador de Arquivos

1. No hPanel, clique em **"Arquivos"** → **"Gerenciador de Arquivos"**
2. Navegue até a pasta do seu domínio:
   - Se for domínio principal: **`public_html/`**
   - Se for subdomínio: **`public_html/subdominio/`**

### 3.2 - Limpar Arquivos Padrão

1. Selecione todos os arquivos existentes (index.html, etc.)
2. Clique em **"Excluir"** (ícone de lixeira)
3. Confirme a exclusão

### 3.3 - Fazer Upload do ZIP

1. Clique no botão **"Enviar"** (ícone de upload)
2. Clique em **"Selecionar arquivo"**
3. Selecione o arquivo: **`tijolo_da_fe_hostinger.zip`**
4. Aguarde o upload completar (pode levar alguns minutos)

### 3.4 - Extrair o Arquivo

1. Após o upload, localize o arquivo **`tijolo_da_fe_hostinger.zip`**
2. Clique com botão direito no arquivo
3. Selecione **"Extrair"** ou **"Extract"**
4. Escolha: **"Extrair aqui"** (Extract here)
5. Aguarde a extração (pode levar 1-2 minutos)
6. Após extrair, DELETE o arquivo ZIP para economizar espaço

---

## ⚙️ PASSO 4: CONFIGURAR CONEXÃO DO BANCO DE DADOS

### 4.1 - Editar arquivo conexao.php

1. No Gerenciador de Arquivos, localize: **`conexao.php`**
2. Clique com botão direito → **"Editar"** (Edit)
3. Localize as linhas 2-5:

```php
$host = 'localhost';
$db   = 'sql_raspinha_pix';
$user = 'sql_raspinha_pix';
$pass = 'sql_raspinha_pix';
```

4. **SUBSTITUA** pelas credenciais do Hostinger que você anotou:

```php
$host = 'localhost';
$db   = 'u123456789_tijolodafe';  // Nome do banco no Hostinger
$user = 'u123456789_user';        // Usuário do banco no Hostinger
$pass = 'sua_senha_aqui';         // Senha do banco no Hostinger
```

5. Clique em **"Salvar"** (Save)

### 4.2 - Atualizar .user.ini (se existir)

1. Localize o arquivo **`.user.ini`**
2. Edite e atualize o caminho:

```ini
open_basedir=/home/u123456789/public_html/:/tmp/
```

Substitua `u123456789` pelo seu ID de usuário no Hostinger.

---

## 🔐 PASSO 5: CONFIGURAR PERMISSÕES

### 5.1 - Configurar Permissões de Pastas

No Gerenciador de Arquivos:

1. **Pasta `/assets/upload/`:**
   - Clique com botão direito → **Permissões** → **755**

2. **Pasta `/logs/`:**
   - Clique com botão direito → **Permissões** → **755**

### 5.2 - Verificar Permissões Gerais

- Arquivos `.php`: **644**
- Pastas: **755**

---

## 🐘 PASSO 6: CONFIGURAR PHP

1. No hPanel, vá em **"Avançado"** → **"Configuração PHP"**
2. Selecione **PHP 8.0** ou superior
3. Verifique se estas extensões estão ativadas:
   - ✅ PDO
   - ✅ PDO_MySQL
   - ✅ mbstring
   - ✅ curl
   - ✅ zip
   - ✅ gd
   - ✅ intl

---

## 🔒 PASSO 7: ATIVAR SSL/HTTPS

1. No hPanel, vá em **"Segurança"** → **"SSL"**
2. Clique em **"Instalar SSL"**
3. Aguarde alguns minutos para ativação
4. Verifique se o cadeado verde aparece no navegador

---

## 🧪 PASSO 8: TESTAR O SITE

### 8.1 - Acessar o Site

1. Abra o navegador
2. Acesse: `https://seudominio.com`
3. O site deve carregar com o tema azul/branco do **Tijolo da Fé**

### 8.2 - Checklist de Testes

- [ ] ✅ Página inicial carrega corretamente
- [ ] ✅ Logo e imagens aparecem
- [ ] ✅ Menu de navegação funciona
- [ ] ✅ Botões funcionam (Início, Tijolos, Aviator, Roleta)
- [ ] ✅ SSL ativo (cadeado verde)

### 8.3 - Testar Login de Admin

1. Acesse: `https://seudominio.com/admin`
2. Faça login com suas credenciais
3. Verifique se o painel admin carrega

---

## ⚙️ PASSO 9: CONFIGURAÇÕES PÓS-DEPLOYMENT

### 9.1 - Atualizar Configurações do Site

1. Acesse: **`https://seudominio.com/admin/config.php`**
2. Atualize:
   - **Nome do Site:** Tijolo da Fé
   - **Logo:** Faça upload do logo oficial
   - **Depósito Mínimo:** Configure conforme desejado
   - **Saque Mínimo:** Configure conforme desejado

### 9.2 - Configurar Gateway de Pagamento

1. Acesse: **`https://seudominio.com/admin/gateway.php`**
2. Configure suas credenciais de pagamento
3. Teste depósito e saque

---

## 🚨 SOLUÇÃO DE PROBLEMAS

### Erro: "Database connection failed"

**Solução:**
- Verifique se editou corretamente o arquivo `conexao.php`
- Confirme as credenciais do banco no hPanel → Bancos de Dados
- Certifique-se de que importou o SQL no phpMyAdmin

### Erro: "500 Internal Server Error"

**Solução:**
- Verifique permissões das pastas (755) e arquivos (644)
- Verifique se o arquivo `.htaccess` existe
- Ative display_errors no PHP temporariamente

### Página em branco (White Screen)

**Solução:**
- Ative exibição de erros: hPanel → PHP Configuration → display_errors: On
- Verifique logs de erro: hPanel → Arquivos → error_log

### Upload não funciona

**Solução:**
- Verifique permissões da pasta `/assets/upload/` (deve ser 755)
- Verifique se a pasta existe

---

## 📞 SUPORTE

- **Hostinger Support:** https://www.hostinger.com.br/contato
- **Documentação Hostinger:** https://support.hostinger.com

---

## ✅ CHECKLIST FINAL

- [ ] Banco de dados criado no Hostinger
- [ ] Arquivo SQL importado via phpMyAdmin
- [ ] Arquivo ZIP enviado e extraído
- [ ] `conexao.php` atualizado com credenciais corretas
- [ ] Permissões configuradas (755 para pastas, 644 para arquivos)
- [ ] PHP 8.0+ configurado
- [ ] SSL/HTTPS ativado
- [ ] Site acessível e carregando
- [ ] Login de admin funcionando
- [ ] Configurações do sistema atualizadas
- [ ] Gateway de pagamento configurado

---

## 🎉 SITE NO AR!

Seu site **Tijolo da Fé** está pronto para uso!

**Acesse:**
- Site: https://seudominio.com
- Admin: https://seudominio.com/admin

---

**Data de Deploy:** 2025-11-21
**Versão:** 1.0 - Redesign Igreja
**Branch:** claude/church-scratchcard-redesign-01M8q1WVEn7TYyzDLePtL5uV
