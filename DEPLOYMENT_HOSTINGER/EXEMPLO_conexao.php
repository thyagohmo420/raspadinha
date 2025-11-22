<?php
// ============================================
// EXEMPLO DE CONFIGURAÇÃO PARA HOSTINGER
// ============================================
//
// INSTRUÇÕES:
// 1. Após fazer upload dos arquivos no Hostinger
// 2. Edite o arquivo conexao.php (não este exemplo)
// 3. Substitua as credenciais abaixo pelas do seu Hostinger
//
// ONDE ENCONTRAR AS CREDENCIAIS:
// - Acesse hPanel → Bancos de Dados → MySQL Databases
// - Copie: Host, Database, Username, Password
// ============================================

$host = 'localhost';                    // Geralmente é 'localhost' no Hostinger
$db   = 'u123456789_tijolodafe';       // SUBSTITUA: Nome do banco que você criou
$user = 'u123456789_user';             // SUBSTITUA: Usuário do banco
$pass = 'SUA_SENHA_AQUI';              // SUBSTITUA: Senha do banco
$charset = 'utf8mb4';

// ============================================
// EXEMPLO DE CREDENCIAIS DO HOSTINGER:
// ============================================
// Host: localhost
// Database: u987654321_tijolodafe
// Username: u987654321_dbuser
// Password: A1b2C3d4E5f6@
// ============================================

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$site = $pdo->query("SELECT nome_site, logo, deposito_min, saque_min, cpa_padrao, revshare_padrao FROM config LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$nomeSite = $site['nome_site'] ?? '';
$logoSite = $site['logo'] ?? '';
$depositoMin = $site['deposito_min'] ?? 10;
$saqueMin = $site['saque_min'] ?? 50;
$cpaPadrao = $site['cpa_padrao'] ?? 10;
$revshare_padrao = $site['revshare_padrao'] ?? 10;
