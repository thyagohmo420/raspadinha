-- ============================================
-- COMANDOS SQL ÚTEIS - TIJOLO DA FÉ
-- Use no phpMyAdmin do Hostinger se necessário
-- ============================================

-- ============================================
-- 1. VERIFICAR CONEXÃO E TABELAS
-- ============================================

-- Listar todas as tabelas do banco
SHOW TABLES;

-- Verificar estrutura da tabela config
DESCRIBE config;

-- Verificar estrutura da tabela usuarios
DESCRIBE usuarios;


-- ============================================
-- 2. VERIFICAR CONFIGURAÇÕES DO SITE
-- ============================================

-- Ver configurações atuais do site
SELECT * FROM config LIMIT 1;

-- Atualizar nome do site para "Tijolo da Fé"
UPDATE config SET nome_site = 'Tijolo da Fé' WHERE id = 1;

-- Atualizar valores mínimos de depósito e saque
UPDATE config SET deposito_min = 10.00, saque_min = 50.00 WHERE id = 1;


-- ============================================
-- 3. GERENCIAR USUÁRIOS ADMIN
-- ============================================

-- Listar todos os administradores
SELECT id, nome, email, cpf, admin, data_criacao
FROM usuarios
WHERE admin = 1;

-- Tornar um usuário administrador (substitua ID)
UPDATE usuarios SET admin = 1 WHERE id = 1;

-- Remover privilégios de admin de um usuário
UPDATE usuarios SET admin = 0 WHERE id = 999;

-- Alterar senha de usuário (senha: "novaSenha123")
UPDATE usuarios
SET senha = MD5('novaSenha123')
WHERE id = 1;

-- Criar novo usuário admin (ajuste os dados)
INSERT INTO usuarios (nome, email, cpf, telefone, senha, admin, saldo, data_criacao)
VALUES ('Admin', 'admin@tijolodafe.com', '12345678900', '11999999999', MD5('senha123'), 1, 0, NOW());


-- ============================================
-- 4. VERIFICAR DADOS FINANCEIROS
-- ============================================

-- Total de depósitos
SELECT COUNT(*) as total_depositos, SUM(valor) as valor_total
FROM depositos
WHERE status = 'aprovado';

-- Total de saques
SELECT COUNT(*) as total_saques, SUM(valor) as valor_total
FROM saques
WHERE status = 'aprovado';

-- Usuários com maior saldo
SELECT id, nome, email, saldo
FROM usuarios
ORDER BY saldo DESC
LIMIT 10;


-- ============================================
-- 5. VERIFICAR RASPADINHAS/CARTELAS
-- ============================================

-- Listar todas as cartelas ativas
SELECT id, titulo, preco, premio_maximo, ativa
FROM cartelas
WHERE ativa = 1;

-- Desativar uma cartela específica
UPDATE cartelas SET ativa = 0 WHERE id = 1;

-- Ativar todas as cartelas
UPDATE cartelas SET ativa = 1;


-- ============================================
-- 6. SISTEMA DE AFILIADOS
-- ============================================

-- Ver configuração de afiliados
SELECT cpa_padrao, revshare_padrao FROM config;

-- Listar afiliados com mais indicações
SELECT
    u.id,
    u.nome,
    u.email,
    COUNT(i.id) as total_indicacoes
FROM usuarios u
LEFT JOIN usuarios i ON i.afiliado_id = u.id
GROUP BY u.id
HAVING total_indicacoes > 0
ORDER BY total_indicacoes DESC;


-- ============================================
-- 7. LIMPAR DADOS DE TESTE (CUIDADO!)
-- ============================================

-- ATENÇÃO: Use apenas se quiser limpar dados de teste!

-- Excluir todos os depósitos de teste
-- DELETE FROM depositos WHERE status = 'pendente';

-- Excluir todos os saques de teste
-- DELETE FROM saques WHERE status = 'pendente';

-- Zerar saldo de todos os usuários (exceto admins)
-- UPDATE usuarios SET saldo = 0 WHERE admin = 0;


-- ============================================
-- 8. BACKUP E MANUTENÇÃO
-- ============================================

-- Verificar tamanho das tabelas
SELECT
    table_name AS 'Tabela',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamanho (MB)'
FROM information_schema.TABLES
WHERE table_schema = DATABASE()
ORDER BY (data_length + index_length) DESC;

-- Contar registros em cada tabela importante
SELECT 'usuarios' as tabela, COUNT(*) as total FROM usuarios
UNION ALL
SELECT 'depositos', COUNT(*) FROM depositos
UNION ALL
SELECT 'saques', COUNT(*) FROM saques
UNION ALL
SELECT 'cartelas', COUNT(*) FROM cartelas
UNION ALL
SELECT 'apostas', COUNT(*) FROM apostas;


-- ============================================
-- 9. RESETAR CONFIGURAÇÕES PADRÃO
-- ============================================

-- Resetar configurações para valores padrão
UPDATE config SET
    deposito_min = 10.00,
    saque_min = 50.00,
    cpa_padrao = 5.00,
    revshare_padrao = 10.00
WHERE id = 1;


-- ============================================
-- 10. VERIFICAR ÚLTIMAS ATIVIDADES
-- ============================================

-- Últimos 10 usuários cadastrados
SELECT id, nome, email, data_criacao
FROM usuarios
ORDER BY data_criacao DESC
LIMIT 10;

-- Últimos 10 depósitos
SELECT id, usuario_id, valor, status, data_criacao
FROM depositos
ORDER BY data_criacao DESC
LIMIT 10;

-- Últimas 10 apostas
SELECT id, usuario_id, cartela_id, valor_aposta, premio, data_criacao
FROM apostas
ORDER BY data_criacao DESC
LIMIT 10;


-- ============================================
-- 11. RELATÓRIOS ÚTEIS
-- ============================================

-- Resumo financeiro geral
SELECT
    (SELECT SUM(saldo) FROM usuarios) as saldo_total_usuarios,
    (SELECT SUM(valor) FROM depositos WHERE status = 'aprovado') as total_depositado,
    (SELECT SUM(valor) FROM saques WHERE status = 'aprovado') as total_sacado,
    (SELECT COUNT(*) FROM usuarios) as total_usuarios,
    (SELECT COUNT(*) FROM usuarios WHERE admin = 1) as total_admins;


-- ============================================
-- NOTAS IMPORTANTES:
-- ============================================
-- 1. Sempre faça backup antes de executar UPDATE ou DELETE
-- 2. Use WHERE em comandos UPDATE/DELETE para evitar alterar tudo
-- 3. Teste comandos SELECT antes de executar UPDATE/DELETE
-- 4. Comandos comentados (--) são apenas exemplos
-- 5. Substitua IDs e valores conforme sua necessidade
-- ============================================
