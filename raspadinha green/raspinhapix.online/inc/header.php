<?php

@session_start();

if (file_exists('./conexao.php')) {
   include('./conexao.php');
} 
elseif (file_exists('../inc/conexao.php')) {
   include('../inc/conexao.php');
}
elseif (file_exists('../../inc/conexao.php')) {
   include('../../inc/conexao.php');
}

// Buscar configurações do site
try {
   $stmt = $pdo->prepare("SELECT * FROM config WHERE id = 1 LIMIT 1");
   $stmt->execute();
   $config = $stmt->fetch(PDO::FETCH_ASSOC);
   
   $nomeSite = $config['nome_site'] ?? 'Raspadinha';
   $logoSite = $config['logo'] ?? null;
   $depositoMin = $config['deposito_min'] ?? 0;
   $saqueMin = $config['saque_min'] ?? 0;
   $cpaPadrao = $config['cpa_padrao'] ?? 0;
   $revshare_padrao = $config['revshare_padrao'] ?? 0;
} catch (PDOException $e) {
   $nomeSite = 'Raspadinha';
   $logoSite = null;
   $depositoMin = 0;
   $saqueMin = 0;
   $cpaPadrao = 0;
   $revshare_padrao = 0;
}

if (isset($_SESSION['usuario_id'])) {
   $usuario_id = $_SESSION['usuario_id'];

   try {
       $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
       $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
       $stmt->execute();

       $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

       if (!$usuario) {
           $_SESSION['message'] = ['type' => 'failure', 'text' => 'Usuário Não existe!'];
       }

       if($usuario['banido'] == 1){
         $_SESSION = [];
         
         session_destroy();
         @session_start();
         $_SESSION['message'] = ['type' => 'failure', 'text' => 'Você está banido!'];
         sleep(2);
         if($_SESSION['message']){
           header("Location: /");
         }
       }

   } catch (PDOException $e) {
       $_SESSION['message'] = ['type' => 'failure', 'text' => 'Erro na consulta!'];
       echo "Erro na consulta: " . $e->getMessage();
   }
} 
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- App Download Banner (optional - like BetSpy) -->
<div class="app-download-banner" id="appBanner" style="display: none;">
    <i class="bi bi-download"></i>
    <span>Baixe nosso app e ganhe muitos pontos!</span>
    <button class="download-btn" onclick="openInstallModal()">
        Baixar
    </button>
    <button class="close-banner" onclick="closeBanner()">
        <i class="bi bi-x"></i>
    </button>
</div>

<header class="header">
    <div class="header-container">
        <!-- Logo -->
        <a href="/" class="logo">
            <?php if ($logoSite && file_exists($_SERVER['DOCUMENT_ROOT'] . $logoSite)): ?>
                <img src="<?= htmlspecialchars($logoSite) ?>" alt="<?= htmlspecialchars($nomeSite) ?>" class="logo-image">
            <?php else: ?>
                <div class="logo-icon">
                    <?= strtoupper(substr($nomeSite, 0, 1)) ?>
                </div>
            <?php endif; ?>
        </a>
        
        <!-- Mobile Menu Button -->
        <div class="mobile-menu-btn">
            <i id="menuBtn" class="bi bi-list"></i>
        </div>
        
        <!-- Navigation -->
        <nav>
            <ul class="nav-menu">
                <li><a href="/" class="nav-link">Início</a></li>
                <li><a href="/cartelas" class="nav-link">Tijolos</a></li>
                <li><a href="/aviator" class="nav-link">Aviator Celestial</a></li>
                <li><a href="/roleta" class="nav-link">Roleta da Fé</a></li>
            </ul>
        </nav>
        
        <!-- Header Actions -->
        <div class="header-actions">
            <?php if(!isset($usuario)): ?>
                <!-- Not Logged In -->
                <a href="/login" class="btn-login">
                    <i class="bi bi-person"></i>
                    <span class="login-text">Entrar</span>
                </a>
                <a href="/cadastro" class="btn-register">
                    <i class="bi bi-dice-3-fill"></i>
                    Registrar
                </a>
            <?php else: ?>
                <!-- Logged In -->
                <?php $primeiroNome = explode(' ', $usuario['nome'])[0]; ?>
                
                <!-- Balance Display -->
                <div class="balance-display">
                    <i class="bi bi-wallet2"></i>
                    <span id="headerSaldo">R$ <?php echo number_format($usuario['saldo'], 2, ',', '.'); ?></span>
                </div>
                
                <!-- Deposit Button -->
                <button onclick="openDepositModal()" class="btn-deposit">
                    <i class="bi bi-plus-circle"></i>
                    <span class="deposit-text">Depositar</span>
                </button>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button id="userDropdownBtn" class="user-btn">
                        <i class="bi bi-person-circle"></i>
                        <span class="user-name"><?php echo htmlspecialchars($primeiroNome); ?></span>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </button>

                    <div id="userDropdownMenu" class="dropdown-menu">
                        <?php if($usuario['admin'] == 1): ?>
                            <a href="/admin" class="dropdown-item">
                                <i class="bi bi-gear"></i>
                                Administrador
                            </a>
                        <?php endif; ?>
                        
                        <a href="/cartelas" class="dropdown-item">
                            <i class="bi bi-grid-3x3-gap"></i>
                            Tijolos
                        </a>

                        <a href="/aviator" class="dropdown-item">
                            <i class="bi bi-airplane"></i>
                            Aviator Celestial
                        </a>

                        <a href="/roleta" class="dropdown-item">
                            <i class="bi bi-circle"></i>
                            Roleta da Fé
                        </a>
                        
                        <a href="/perfil" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            Perfil
                        </a>
                        
                        <a href="/afiliados" class="dropdown-item">
                            <i class="bi bi-people"></i>
                            Indique e Ganhe
                        </a>
                        
                        <button onclick="openDepositModal()" class="dropdown-item">
                            <i class="bi bi-plus-circle"></i>
                            Depósito
                        </button>
                        
                        <button onclick="openWithdrawModal(<?php echo $usuario['saldo'];?>)" class="dropdown-item">
                            <i class="bi bi-dash-circle"></i>
                            Saque
                        </button>
                        
                        <a href="/transacoes" class="dropdown-item">
                            <i class="bi bi-arrow-left-right"></i>
                            Transações
                        </a>
                        
                        <a href="/apostas" class="dropdown-item">
                            <i class="bi bi-ticket-perforated"></i>
                            Apostas
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="/logout" class="dropdown-item logout">
                            <i class="bi bi-box-arrow-right"></i>
                            Sair
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Mobile Sidebar -->
<aside id="sidebar" class="mobile-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <?php if ($logoSite && file_exists($_SERVER['DOCUMENT_ROOT'] . $logoSite)): ?>
                <img src="<?= htmlspecialchars($logoSite) ?>" alt="<?= htmlspecialchars($nomeSite) ?>" class="sidebar-logo-image">
            <?php else: ?>
                <div class="sidebar-logo-icon">
                    <?= strtoupper(substr($nomeSite, 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <button id="closeSidebar" class="close-btn">
            <i class="bi bi-x"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <a href="/" class="sidebar-item">
            <i class="bi bi-house"></i>
            <span>Início</span>
        </a>

        <a href="/cartelas" class="sidebar-item">
            <i class="bi bi-grid-3x3-gap"></i>
            <span>Tijolos</span>
        </a>

        <a href="/aviator" class="sidebar-item">
            <i class="bi bi-airplane"></i>
            <span>Aviator Celestial</span>
        </a>

        <a href="/roleta" class="sidebar-item">
            <i class="bi bi-circle"></i>
            <span>Roleta da Fé</span>
        </a>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="sidebar-divider"></div>
            
            <a href="/apostas" class="sidebar-item">
                <i class="bi bi-ticket-perforated"></i>
                <span>Minhas Apostas</span>
            </a>

            <a href="/transacoes" class="sidebar-item">
                <i class="bi bi-arrow-left-right"></i>
                <span>Transações</span>
            </a>

            <button onclick="openDepositModal()" class="sidebar-item">
                <i class="bi bi-plus-circle"></i>
                <span>Depositar</span>
            </button>

            <button onclick="openWithdrawModal(<?php echo $usuario['saldo'];?>)" class="sidebar-item">
                <i class="bi bi-dash-circle"></i>
                <span>Sacar</span>
            </button>
            
            <div class="sidebar-divider"></div>
            
            <a href="/logout" class="sidebar-item logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sair</span>
            </a>
        <?php endif; ?>

        <div class="sidebar-divider"></div>
        
        <a href="https://t.me/daanrox" target="_blank" class="sidebar-item">
            <i class="bi bi-telegram"></i>
            <span>Suporte</span>
        </a>
    </nav>
</aside>

<!-- Mobile Backdrop -->
<div id="backdrop" class="mobile-backdrop"></div>

<!-- Bottom Navigation (NEW) -->
<nav class="bottom-navigation">
    <div class="bottom-nav-container">
        <?php if(!isset($usuario)): ?>
            <!-- Not Logged In -->
            <a href="/" class="bottom-nav-item active">
                <i class="bi bi-house-fill"></i>
                <span>Início</span>
            </a>

            <a href="/cartelas" class="bottom-nav-item">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Tijolos</span>
            </a>

            <a href="/login" class="bottom-nav-item">
                <i class="bi bi-person-fill"></i>
                <span>Entrar</span>
            </a>

            <a href="/cadastro" class="bottom-nav-item register-btn">
                <i class="bi bi-dice-3-fill"></i>
                <span>Registrar</span>
            </a>
        <?php else: ?>
            <!-- Logged In -->
            <a href="/" class="bottom-nav-item active">
                <i class="bi bi-house-fill"></i>
                <span>Início</span>
            </a>
            
            <a href="/cartelas" class="bottom-nav-item">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Tijolos</span>
            </a>

            <button onclick="openDepositModal()" class="bottom-nav-item deposit-btn">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Depositar</span>
            </button>
            
            <a href="/apostas" class="bottom-nav-item">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span>Apostas</span>
            </a>
            
            <a href="/perfil" class="bottom-nav-item">
                <i class="bi bi-person-circle"></i>
                <span>Perfil</span>
            </a>
        <?php endif; ?>
    </div>
</nav>

<!-- Install App Modal -->
<div id="installModal" class="install-modal">
    <div class="install-modal-content">
        <div class="install-modal-header">
            <div class="install-modal-icon">
                <i class="bi bi-phone"></i>
            </div>
            <h2>Instale Nosso App</h2>
            <button class="install-modal-close" onclick="closeInstallModal()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div class="install-benefits">
            <div class="benefits-title">
                <i class="bi bi-gift"></i>
                <span>Vantagens do App</span>
            </div>
            
            <div class="benefits-list">
                <div class="benefit-item">
                    <i class="bi bi-check-circle"></i>
                    <span>Acesso rápido direto da tela inicial</span>
                </div>
                <div class="benefit-item">
                    <i class="bi bi-check-circle"></i>
                    <span>Notificações de promoções exclusivas</span>
                </div>
                <div class="benefit-item">
                    <i class="bi bi-check-circle"></i>
                    <span>Experiência mais fluida e rápida</span>
                </div>
                <div class="benefit-item">
                    <i class="bi bi-check-circle"></i>
                    <span>Funciona mesmo offline</span>
                </div>
                <div class="benefit-item">
                    <i class="bi bi-check-circle"></i>
                    <span>Design otimizado para mobile</span>
                </div>
            </div>
        </div>
        
        <div class="install-instructions">
            <div class="platform-tabs">
                <button class="platform-tab active" data-platform="ios">
                    <i class="bi bi-apple"></i>
                    iOS (iPhone/iPad)
                </button>
                <button class="platform-tab" data-platform="android">
                    <i class="bi bi-android2"></i>
                    Android
                </button>
            </div>
            
            <div class="platform-content active" data-platform="ios">
                <div class="instruction-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Abra no Safari</h4>
                        <p>Este site deve ser aberto no navegador Safari</p>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Toque no botão Compartilhar</h4>
                        <p><i class="bi bi-share"></i> Na barra inferior do Safari</p>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Selecione "Adicionar à Tela de Início"</h4>
                        <p><i class="bi bi-plus-square"></i> Role para baixo se necessário</p>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Confirme tocando em "Adicionar"</h4>
                        <p>O app aparecerá na sua tela inicial</p>
                    </div>
                </div>
            </div>
            
            <div class="platform-content" data-platform="android">
                <div class="instruction-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Abra no Chrome</h4>
                        <p>Recomendamos usar o Google Chrome</p>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Toque no menu (3 pontos)</h4>
                        <p><i class="bi bi-three-dots-vertical"></i> No canto superior direito</p>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Selecione "Instalar app" ou "Adicionar à tela inicial"</h4>
                        <p><i class="bi bi-house-add"></i> Pode aparecer automaticamente uma notificação</p>
                    </div>
                </div>
                
                <div class="instruction-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Confirme a instalação</h4>
                        <p>O app será adicionado ao seu dispositivo</p>
                    </div>
                </div>
            </div>
            
            <div class="desktop-note">
                <div class="platform-tab">
                    <i class="bi bi-display"></i>
                    Desktop
                </div>
                <p>No desktop, procure pelo ícone de instalação na barra de endereço do seu navegador ou um banner de instalação aparecerá automaticamente.</p>
            </div>
        </div>
        
        <div class="install-modal-footer">
            <button class="install-understand-btn" onclick="closeInstallModal()">
                Entendi, obrigado!
            </button>
        </div>
    </div>
</div>

<style>
/* Header Styles */
.header {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(59, 130, 246, 0.2);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    padding: 1.25rem 0;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Logo Styles - Apenas logo, sem texto */
.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.logo:hover {
    transform: scale(1.05);
}

.logo-image {
    height: 45px;
    width: auto;
    max-width: 200px;
    object-fit: contain;
    transition: all 0.3s ease;
}

.logo-image:hover {
    transform: scale(1.02);
}

.logo-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #ffffff;
    font-weight: 900;
    box-shadow:
        0 8px 20px rgba(59, 130, 246, 0.35),
        0 4px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.logo-icon:hover {
    box-shadow:
        0 12px 30px rgba(59, 130, 246, 0.45),
        0 6px 12px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

/* Sidebar Logo Styles - Apenas logo, sem texto */
.sidebar-logo {
    display: flex;
    align-items: center;
    justify-content: center;
}

.sidebar-logo-image {
    height: 40px;
    width: auto;
    max-width: 150px;
    object-fit: contain;
}

.sidebar-logo-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #ffffff;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
}

/* App Download Banner */
.app-download-banner {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    padding: 0.7rem 1rem;
    font-size: 0.9rem;
    font-weight: 700;
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1001;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    border-bottom: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.12);
}

.app-download-banner i {
    font-size: 1rem;
}

.download-btn {
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-left: 0.5rem;
}

.download-btn:hover {
    background: rgba(0, 0, 0, 0.3);
    transform: translateY(-1px);
}

.close-banner {
    position: absolute;
    right: 1rem;
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.3s ease;
    padding: 0.25rem;
    border-radius: 4px;
}

.close-banner:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.1);
}

/* Header position adjustments */
.header {
    top: 0;
    position: fixed;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(59, 130, 246, 0.2);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    left: 0;
    right: 0;
    z-index: 1000;
    padding: 1.25rem 0;
    transition: top 0.3s ease;
}

.header.with-banner {
    top: 45px;
}

/* Install Modal Styles */
.install-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 1rem;
}

.install-modal.active {
    display: flex;
}

.install-modal-content {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    border: 2px solid rgba(59, 130, 246, 0.3);
    border-radius: 24px;
    max-width: 520px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.install-modal-header {
    padding: 2.25rem 2.25rem 1.25rem;
    text-align: center;
    position: relative;
    border-bottom: 2px solid rgba(59, 130, 246, 0.15);
}

.install-modal-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.35);
}

.install-modal-header h2 {
    color: #1e293b;
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0;
}

.install-modal-close {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    background: none;
    border: none;
    color: #64748b;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.5rem;
    border-radius: 8px;
}

.install-modal-close:hover {
    color: #1e293b;
    background: rgba(59, 130, 246, 0.1);
}

.install-benefits {
    padding: 1.75rem 2.25rem;
    border-bottom: 2px solid rgba(59, 130, 246, 0.15);
}

.benefits-title {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    color: #2563eb;
    font-weight: 700;
    margin-bottom: 1.25rem;
    font-size: 1.2rem;
}

.benefits-title i {
    font-size: 1.2rem;
}

.benefits-list {
    display: grid;
    gap: 0.75rem;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    color: #475569;
    font-size: 1rem;
}

.benefit-item i {
    color: #3b82f6;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.install-instructions {
    padding: 1.5rem 2rem;
}

.platform-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.platform-tab {
    flex: 1;
    background: rgba(59, 130, 246, 0.05);
    border: 2px solid rgba(59, 130, 246, 0.2);
    color: #64748b;
    padding: 0.875rem 1.125rem;
    border-radius: 14px;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.625rem;
}

.platform-tab.active {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-color: rgba(59, 130, 246, 0.6);
    color: white;
}

.platform-tab:hover:not(.active) {
    background: rgba(59, 130, 246, 0.1);
    color: #1e293b;
    border-color: rgba(59, 130, 246, 0.3);
}

.platform-content {
    display: none;
}

.platform-content.active {
    display: block;
}

.instruction-step {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    align-items: flex-start;
}

.step-number {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.step-content h4 {
    color: #1e293b;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 0.375rem 0;
}

.step-content p {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.step-content p i {
    color: #3b82f6;
}

.desktop-note {
    margin-top: 1.5rem;
    padding: 1rem;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
}

.desktop-note .platform-tab {
    background: none;
    border: none;
    color: #60a5fa;
    padding: 0;
    margin-bottom: 0.5rem;
    justify-content: flex-start;
    cursor: default;
}

.desktop-note p {
    color: #e5e7eb;
    font-size: 0.9rem;
    margin: 0;
    line-height: 1.4;
}

.install-modal-footer {
    padding: 1.5rem 2rem 2rem;
    text-align: center;
}

.install-understand-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border: none;
    padding: 1rem 2.25rem;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35);
}

.install-understand-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(59, 130, 246, 0.45);
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}

/* Bottom Navigation Styles - NEW */
.bottom-navigation {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-top: 2px solid rgba(59, 130, 246, 0.2);
    padding: 0.625rem 0;
    z-index: 999;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
}

.bottom-nav-container {
    max-width: 100%;
    margin: 0 auto;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 0 1rem;
}

.bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0.625rem 0.375rem;
    text-decoration: none;
    color: #64748b;
    transition: all 0.3s ease;
    border-radius: 14px;
    min-width: 65px;
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    font-family: inherit;
}

.bottom-nav-item i {
    font-size: 1.3rem;
    margin-bottom: 0.375rem;
    transition: all 0.3s ease;
}

.bottom-nav-item span {
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.bottom-nav-item:hover {
    color: #3b82f6;
    transform: translateY(-2px);
}

.bottom-nav-item.active {
    color: #2563eb;
}

.bottom-nav-item.active i {
    transform: scale(1.15);
}

/* Special styling for deposit button */
.bottom-nav-item.deposit-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-radius: 18px;
    padding: 0.875rem 0.625rem;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35);
    transform: translateY(-5px);
}

.bottom-nav-item.deposit-btn:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-7px);
    box-shadow: 0 6px 25px rgba(59, 130, 246, 0.45);
}

.bottom-nav-item.deposit-btn i {
    font-size: 1.5rem;
}

.bottom-nav-item.deposit-btn span {
    font-weight: 700;
    font-size: 0.8rem;
}

/* Special styling for register button */
.bottom-nav-item.register-btn {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border-radius: 18px;
    padding: 0.875rem 0.625rem;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35);
}

.bottom-nav-item.register-btn:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-4px);
    box-shadow: 0 6px 25px rgba(59, 130, 246, 0.45);
}

.bottom-nav-item.register-btn i {
    font-size: 1.5rem;
}

.bottom-nav-item.register-btn span {
    font-weight: 700;
    font-size: 0.8rem;
}

/* Badge for notifications (optional) */
.bottom-nav-item::after {
    content: '';
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.bottom-nav-item.has-notification::after {
    opacity: 1;
}

/* Mobile banner adjustments */
@media (max-width: 768px) {
    .app-download-banner {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        gap: 0.5rem;
    }
    
    .download-btn {
        padding: 0.3rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .close-banner {
        right: 0.75rem;
        font-size: 1.1rem;
    }
    
    .header.with-banner {
        top: 40px;
    }
    
    .install-modal-content {
        margin: 1rem;
        max-height: 85vh;
    }
    
    .install-modal-header {
        padding: 1.5rem 1.5rem 1rem;
    }
    
    .install-benefits,
    .install-instructions,
    .install-modal-footer {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    
    .platform-tabs {
        flex-direction: column;
    }

    /* Adjust body padding for bottom nav */
    body {
        padding-bottom: 85px !important;
    }
}

@media (max-width: 480px) {
    .app-download-banner {
        padding: 0.4rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .app-download-banner span {
        max-width: calc(100% - 120px);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .download-btn {
        padding: 0.25rem 0.6rem;
        font-size: 0.7rem;
        margin-left: 0.25rem;
    }
    
    .close-banner {
        right: 0.5rem;
        font-size: 1rem;
    }
    
    .header.with-banner {
        top: 35px;
    }
    
    .install-modal {
        padding: 0.5rem;
    }
    
    .install-modal-header {
        padding: 1rem;
    }
    
    .install-modal-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .install-modal-header h2 {
        font-size: 1.3rem;
    }
    
    .install-benefits,
    .install-instructions,
    .install-modal-footer {
        padding: 1rem;
    }

    /* Bottom nav adjustments for very small screens */
    .bottom-nav-container {
        padding: 0 0.5rem;
    }
    
    .bottom-nav-item {
        min-width: 50px;
        padding: 0.4rem 0.2rem;
    }
    
    .bottom-nav-item i {
        font-size: 1.1rem;
    }
    
    .bottom-nav-item span {
        font-size: 0.65rem;
    }
    
    .bottom-nav-item.deposit-btn i,
    .bottom-nav-item.register-btn i {
        font-size: 1.3rem;
    }
    
    .bottom-nav-item.deposit-btn span,
    .bottom-nav-item.register-btn span {
        font-size: 0.7rem;
    }

    /* Adjust body padding for bottom nav */
    body {
        padding-bottom: 80px !important;
    }
}

.mobile-menu-btn {
    display: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.mobile-menu-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 2rem;
    list-style: none;
}

.nav-link {
    color: #4b5563;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    color: #2563eb;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 3px;
    background: #3b82f6;
    transition: width 0.3s ease;
    border-radius: 2px;
}

.nav-link:hover::after {
    width: 100%;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-login {
    color: #4b5563;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    transition: color 0.3s ease;
}

.btn-login:hover {
    color: #2563eb;
}

.btn-register {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    text-decoration: none;
    padding: 0.875rem 1.75rem;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-register:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

.balance-display {
    background: rgba(59, 130, 246, 0.08);
    border: 2px solid rgba(59, 130, 246, 0.25);
    border-radius: 14px;
    padding: 0.625rem 1.125rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    color: #2563eb;
    font-weight: 700;
    font-size: 1.05rem;
}

.btn-deposit {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border: none;
    padding: 0.875rem 1.75rem;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-deposit:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

.user-dropdown {
    position: relative;
}

.user-btn {
    background: rgba(59, 130, 246, 0.08);
    border: 2px solid rgba(59, 130, 246, 0.25);
    border-radius: 14px;
    padding: 0.875rem 1.125rem;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

.user-btn:hover {
    background: rgba(59, 130, 246, 0.12);
    border-color: rgba(59, 130, 246, 0.35);
}

.dropdown-arrow {
    transition: transform 0.3s ease;
}

.user-dropdown.active .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: calc(100% + 0.625rem);
    right: 0;
    min-width: 220px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border: 2px solid rgba(59, 130, 246, 0.25);
    border-radius: 14px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 100;
}

.user-dropdown.active .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1.125rem;
    color: #475569;
    text-decoration: none;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
    font-weight: 600;
}

.dropdown-item:hover {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.dropdown-item.logout {
    color: #ef4444;
}

.dropdown-item.logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.dropdown-divider {
    height: 2px;
    background: rgba(59, 130, 246, 0.15);
    margin: 0.625rem 0;
}

/* Mobile Sidebar */
.mobile-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 300px;
    height: 100vh;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-right: 2px solid rgba(59, 130, 246, 0.25);
    box-shadow: 4px 0 30px rgba(0, 0, 0, 0.1);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 1100;
    overflow-y: auto;
}

.mobile-sidebar.active {
    transform: translateX(0);
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.75rem 1.25rem;
    border-bottom: 2px solid rgba(59, 130, 246, 0.2);
}

.close-btn {
    background: rgba(59, 130, 246, 0.1);
    border: none;
    color: #64748b;
    font-size: 1.5rem;
    cursor: pointer;
    border-radius: 8px;
    padding: 0.5rem;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: rgba(59, 130, 246, 0.2);
    color: #2563eb;
}

.sidebar-nav {
    padding: 1rem 0;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1.75rem;
    color: #475569;
    text-decoration: none;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 1.05rem;
}

.sidebar-item:hover {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.sidebar-item.logout {
    color: #ef4444;
}

.sidebar-item.logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.sidebar-divider {
    height: 2px;
    background: rgba(59, 130, 246, 0.15);
    margin: 1.125rem 0;
}

.mobile-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1050;
}

.mobile-backdrop.active {
    opacity: 1;
    visibility: visible;
}

/* Hide bottom navigation on desktop */
@media (min-width: 769px) {
    .bottom-navigation {
        display: none;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header-container {
        padding: 0 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .logo {
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .logo-image {
        height: 35px;
    }
    
    .logo-icon {
        width: 35px;
        height: 35px;
        font-size: 1.1rem;
    }
    
    .mobile-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        color: white;
        font-size: 1.3rem;
        cursor: pointer;
        border-radius: 8px;
        transition: background-color 0.3s ease;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .mobile-menu-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .nav-menu {
        display: none;
    }
    
    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    
    .balance-display {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        min-width: auto;
        border-radius: 8px;
    }
    
    .btn-deposit {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        border-radius: 8px;
        min-width: auto;
    }
    
    .deposit-text {
        display: none;
    }
    
    .user-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        border-radius: 8px;
        min-width: auto;
    }
    
    .user-name {
        display: none;
    }
    
    .dropdown-arrow {
        font-size: 0.8rem;
    }
    
    .btn-login {
        font-size: 0.85rem;
        padding: 0.5rem;
    }
    
    .login-text {
        display: none;
    }
    
    .btn-register {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
    }
    
    .dropdown-menu {
        right: 0;
        min-width: 180px;
        margin-top: 0.25rem;
    }
    
    .dropdown-item {
        padding: 0.6rem 0.8rem;
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .header-container {
        padding: 0 0.75rem;
        gap: 0.5rem;
    }
    
    .logo-image {
        height: 32px;
    }
    
    .logo-icon {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }
    
    .mobile-menu-btn {
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
        margin-right: 0.75rem;
    }
    
    .header-actions {
        gap: 0.4rem;
    }
    
    .balance-display {
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
        min-width: auto;
    }
    
    .btn-deposit,
    .user-btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
        min-width: 36px;
    }
    
    .btn-register {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }
    
    .dropdown-arrow {
        font-size: 0.7rem;
    }
    
    .sidebar-logo-image {
        height: 35px;
    }
    
    .sidebar-logo-icon {
        width: 35px;
        height: 35px;
        font-size: 1.1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if banner was previously closed
    const bannerClosed = localStorage.getItem('appBannerClosed');
    const appBanner = document.getElementById('appBanner');
    const header = document.querySelector('.header');
    
    if (!bannerClosed && appBanner) {
        appBanner.style.display = 'flex';
        header.classList.add('with-banner');
    } else {
        header.style.top = '0';
    }
    
    // Banner close function
    window.closeBanner = function() {
        appBanner.style.display = 'none';
        header.classList.remove('with-banner');
        header.style.top = '0';
        localStorage.setItem('appBannerClosed', 'true');
    };
    
    // Install modal functions
    window.openInstallModal = function() {
        document.getElementById('installModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeInstallModal = function() {
        document.getElementById('installModal').classList.remove('active');
        document.body.style.overflow = '';
    };
    
    // Platform tabs functionality
    const platformTabs = document.querySelectorAll('.platform-tab[data-platform]');
    const platformContents = document.querySelectorAll('.platform-content[data-platform]');
    
    platformTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const platform = tab.dataset.platform;
            
            // Remove active class from all tabs and contents
            platformTabs.forEach(t => t.classList.remove('active'));
            platformContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            tab.classList.add('active');
            document.querySelector(`.platform-content[data-platform="${platform}"]`).classList.add('active');
        });
    });
    
    // Close modal when clicking outside
    document.getElementById('installModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeInstallModal();
        }
    });

    // Mobile menu functionality
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    const closeSidebar = document.getElementById('closeSidebar');

    function openSidebar() {
        sidebar.classList.add('active');
        backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebarFunc() {
        sidebar.classList.remove('active');
        backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuBtn?.addEventListener('click', openSidebar);
    closeSidebar?.addEventListener('click', closeSidebarFunc);
    backdrop?.addEventListener('click', closeSidebarFunc);

    // User dropdown functionality
    const userDropdownBtn = document.getElementById('userDropdownBtn');
    const userDropdown = userDropdownBtn?.closest('.user-dropdown');

    userDropdownBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
    });

    document.addEventListener('click', function(e) {
        if (userDropdown && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });

    // Bottom Navigation Active State
    function setActiveBottomNavItem() {
        const currentPath = window.location.pathname;
        const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
        
        bottomNavItems.forEach(item => {
            item.classList.remove('active');
            
            const href = item.getAttribute('href');
            if (href && (href === currentPath || (currentPath === '/' && href === '/'))) {
                item.classList.add('active');
            }
        });
    }
    
    // Set active state on page load
    setActiveBottomNavItem();
    
    // Update active state when clicking bottom nav items
    document.querySelectorAll('.bottom-nav-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active from all items
            document.querySelectorAll('.bottom-nav-item').forEach(navItem => {
                navItem.classList.remove('active');
            });
            // Add active to clicked item (if it's a link)
            if (this.getAttribute('href')) {
                this.classList.add('active');
            }
        });
    });
});
</script>
<script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool@latest"></script>
<script>
    document.addEventListener("keydown", function (event) {
    if (event.key === "F12") {
        event.preventDefault();
        window.close();
    }

    if (event.ctrlKey && event.shiftKey && event.key === "C") {
        event.preventDefault();
        window.close();
    }

    if (event.ctrlKey && event.key === "U") {
        event.preventDefault();
        window.close();
        }
    });
</script>
<!-- Modais will be included separately -->