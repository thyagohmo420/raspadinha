# ⚡ GUIA RÁPIDO - DEPLOY HOSTINGER
## Tijolo da Fé - Passo a Passo Simplificado

---

## 📋 PRÉ-REQUISITOS

✅ Conta ativa no Hostinger
✅ Arquivo: `tijolo_da_fe_hostinger.zip` (25MB)
✅ Arquivo SQL do banco de dados (backup.sql)

---

## 🚀 DEPLOY EM 9 PASSOS

### ① CRIAR BANCO DE DADOS (5 minutos)

```
hPanel → Bancos de Dados → Criar novo banco
↓
Nome: tijolodafe
↓
ANOTE: u123456789_tijolodafe
        u123456789_user
        senha_gerada
```

---

### ② IMPORTAR SQL (3 minutos)

```
hPanel → phpMyAdmin → Seu Banco
↓
Importar → Escolher arquivo .sql
↓
Executar → Aguardar confirmação
```

---

### ③ ACESSAR FILE MANAGER (1 minuto)

```
hPanel → Arquivos → Gerenciador de Arquivos
↓
Navegar para: public_html/
↓
Excluir arquivos padrão (index.html, etc.)
```

---

### ④ UPLOAD DO ZIP (5 minutos)

```
Clicar em: Enviar (Upload)
↓
Selecionar: tijolo_da_fe_hostinger.zip
↓
Aguardar upload completo
```

---

### ⑤ EXTRAIR ARQUIVOS (2 minutos)

```
Botão direito no ZIP → Extrair
↓
"Extrair aqui" (Extract here)
↓
Aguardar conclusão → Excluir ZIP
```

---

### ⑥ CONFIGURAR BANCO (3 minutos)

```
Editar arquivo: conexao.php
↓
Linha 2: $host = 'localhost';
Linha 3: $db   = 'u123456789_tijolodafe';
Linha 4: $user = 'u123456789_user';
Linha 5: $pass = 'sua_senha_aqui';
↓
Salvar
```

---

### ⑦ CONFIGURAR PERMISSÕES (2 minutos)

```
Pasta: /assets/upload/ → Permissões: 755
Pasta: /logs/ → Permissões: 755
```

---

### ⑧ CONFIGURAR PHP (2 minutos)

```
hPanel → Configuração PHP
↓
Versão: PHP 8.0 ou 8.1
↓
Extensões ativas:
✅ PDO, PDO_MySQL, mbstring, curl, gd
```

---

### ⑨ ATIVAR SSL (1 minuto)

```
hPanel → SSL → Instalar SSL
↓
Aguardar 2-5 minutos
↓
Verificar cadeado verde no site
```

---

## ✅ CHECKLIST DE VERIFICAÇÃO

```
[ ] Banco criado e credenciais anotadas
[ ] SQL importado com sucesso
[ ] Arquivos extraídos no public_html/
[ ] conexao.php editado corretamente
[ ] Permissões configuradas (755/644)
[ ] PHP 8.0+ ativado
[ ] SSL instalado e ativo
[ ] Site carrega: https://seudominio.com
[ ] Admin acessível: https://seudominio.com/admin
```

---

## 🎯 ACESSOS APÓS DEPLOY

**Site Principal:**
🌐 https://seudominio.com

**Painel Admin:**
🔐 https://seudominio.com/admin

**Páginas Importantes:**
- Tijolos: https://seudominio.com/cartelas
- Aviator: https://seudominio.com/aviator
- Roleta: https://seudominio.com/roleta
- Afiliados: https://seudominio.com/afiliados

---

## 🚨 PROBLEMAS COMUNS

### ❌ Erro de conexão com banco

**Verifique:**
- credenciais no conexao.php estão corretas
- banco foi importado no phpMyAdmin
- nome do banco tem o prefixo u123456789_

### ❌ Página em branco

**Verifique:**
- permissões das pastas (755)
- versão do PHP (8.0+)
- arquivo .htaccess existe

### ❌ Upload não funciona

**Verifique:**
- pasta /assets/upload/ tem permissão 755
- PHP tem extensões gd e fileinfo ativas

---

## ⏱️ TEMPO TOTAL ESTIMADO

🕐 **25-30 minutos** (incluindo uploads)

---

## 📞 PRECISA DE AJUDA?

1. Verifique os logs de erro no hPanel
2. Contate suporte Hostinger: https://www.hostinger.com.br/contato
3. Consulte documentação completa: `INSTRUCOES_DEPLOYMENT_HOSTINGER.md`

---

**Versão:** 1.0
**Data:** 2025-11-21
**Projeto:** Tijolo da Fé - Redesign Igreja
