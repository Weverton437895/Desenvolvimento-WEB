<?php
require 'config.php';
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nivel'])) {
    header("Location: login.php");
    exit;
}

$query = $pdo->query("SELECT * FROM servico ORDER BY id DESC");
$produtos = $query->fetchAll();

$query = $pdo->query("SELECT * FROM avaliacao ORDER BY id DESC LIMIT 6");
$avaliacoes = $query->fetchAll();

$usuarioId = $_SESSION['usuario_id'];

if (isset($usuarioId)) {
    $query = $pdo->prepare("SELECT nome, email, foto, nivel FROM usuario WHERE id = ?");
    $query->execute([$usuarioId]);
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <title>Tech Innovation - Produtos Premium</title>
    
    <style>
       
    </style>
</head>
<body>
    <?php if (isset($_GET['contato']) && $_GET['contato'] === 'sucesso'): ?>
    <div class="message success-message" id="mensagemSucesso" style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 300px;">
        ✓ Mensagem enviada com sucesso!
    </div>
    
    <script>
        setTimeout(function() {
            const mensagem = document.getElementById('mensagemSucesso');
            if (mensagem) {
                mensagem.style.transition = 'opacity 0.5s ease';
                mensagem.style.opacity = '0';
                
                setTimeout(function() {
                    mensagem.remove();
                    
                    const url = new URL(window.location);
                    url.searchParams.delete('contato');
                    window.history.replaceState({}, '', url);
                }, 500);
            }
        }, 2000);
    </script>
<?php endif; ?>

    <header>
        <div class="header-container">
            <div class="logo">
                <h1>Tech Innovation<?php echo ($usuario['nivel'] === 'admin') ? ' - Admin' : ''; ?></h1>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="#hero">Início</a></li>
                    <li><a href="#quem-somos">Quem Somos</a></li>
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#avaliacoes">Avaliações</a></li>
                    <li><a href="#contato">Contato</a></li>
                    <?php if ($usuario['nivel'] === 'admin'): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="user-info">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="Foto">
                <?php endif; ?>
                <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
                <a href="logout.php" class="btn-login">Sair</a>
            </div>
        </div>
    </header>

    <section id="hero" class="hero-banner-modern" style="background-image: url('images/banner.jpg');">
        <div class="hero-overlay-dark"></div>
        <div class="hero-content-modern">
            <div class="hero-badge">Bem-vindo</div>
            <h1 class="hero-title">Tech Innovation</h1>
            <p class="hero-subtitle">Descubra o futuro da tecnologia com nossos produtos premium</p>
            <div class="hero-buttons">
                <a href="#produtos" class="btn-hero-primary">
                    <span>Explorar Produtos</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#quem-somos" class="btn-hero-secondary">Saiba Mais</a>
            </div>
        </div>
        <div class="scroll-indicator">
            <span>Role para baixo</span>
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- SEÇÃO: QUEM SOMOS -->
    <section id="quem-somos" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 80px 0;">
        <div class="container">
            <h2 style="color: white; margin-bottom: 20px;">Quem Somos</h2>
            <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px;">
                    Somos uma plataforma dedicada a oferecer produtos e serviços de alta qualidade para nossos clientes. 
                    Com anos de experiência no mercado, nos destacamos pela excelência no atendimento e pela confiabilidade 
                    em todas as nossas transações.
                </p>
                <p style="font-size: 1.1rem; line-height: 1.8;">
                    Nossa missão é proporcionar a melhor experiência de compra, conectando pessoas a produtos incríveis 
                    e garantindo satisfação total em cada transação. Contamos com uma equipe especializada pronta para 
                    atender suas necessidades.
                </p>
            </div>
        </div>
    </section>

    <!-- SEÇÃO: PRODUTOS -->
    <section id="produtos" class="section-light">
        <div class="container">
            <h2>Nossos Produtos</h2>
            <p class="section-subtitle">Confira nossos produtos exclusivos e de alta qualidade</p>
            
            <div class="cards-grid">
                <?php if (empty($produtos)): ?>
                    <div class="empty-state">
                        <p>Nenhum produto disponível no momento.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="card">
                            <div class="card-image">
                                <?php if (!empty($produto['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($produto['foto']); ?>" 
                                         alt="<?php echo htmlspecialchars($produto['titulo']); ?>">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/400x300?text=Sem+Imagem" alt="Sem imagem">
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <h3><?php echo htmlspecialchars($produto['titulo']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($produto['descricao'], 0, 120)) . '...'; ?></p>
                            </div>
                            <div class="card-footer">
                                <?php if ($usuario['nivel'] === 'usuario'): ?>         
                                    <a href="checkout.php?id=<?php echo $produto['id']; ?>" class="btn btn-primary">Comprar Agora</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- SEÇÃO: AVALIAÇÕES -->
    <section id="avaliacoes" class="section-dark">
        <div class="container">
            <h2>Avaliações dos Clientes</h2>
            <p class="section-subtitle">Veja o que nossos clientes estão dizendo</p>
            
            <div class="cards-grid">
                <?php if (empty($avaliacoes)): ?>
                    <div class="review-cta" style="grid-column: 1/-1;">
                        <h3>Seja o Primeiro a Avaliar!</h3>
                        <p>Compartilhe sua experiência com nossos produtos e ajude outros clientes.</p>
                        <a href="#produtos" class="btn btn-primary btn-large">Escolher Produto para Avaliar</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($avaliacoes as $avaliacao): ?>
                        <div class="card card-review">
                            <div class="review-header">
                                <div>
                                    <h3><?php echo htmlspecialchars($avaliacao['nome']); ?></h3>
                                    <?php if (!empty($avaliacao['produto_titulo'])): ?>
                                        <p class="review-produto">Produto: <strong><?php echo htmlspecialchars($avaliacao['produto_titulo']); ?></strong></p>
                                    <?php endif; ?>
                                </div>
                                <div class="stars">
                                    <?php echo str_repeat('⭐', $avaliacao['estrelas']); ?>
                                </div>
                            </div>
                            <div class="review-text">
                                <p><?php echo htmlspecialchars($avaliacao['comentario']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- CTA para adicionar avaliação -->
                    <?php if ($usuario['nivel'] === 'usuario'): ?>
                    <div class="review-cta">
                        <h3>Compartilhe Sua Experiência!</h3>
                        <p>Já comprou algum produto? Conte-nos o que achou!</p>
                        <a href="#produtos" class="btn btn-primary btn-large">Avaliar Produto</a>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- SEÇÃO: CONTATO -->
    <footer id="contato">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tech Innovation</h3>
                    <p>Sua plataforma de produtos de qualidade com excelência no atendimento e confiabilidade.</p>
                </div>
                <div class="footer-section">
                    <h3>Entre em Contato</h3>
                    <form action="enviar_contato.php" method="post">
                        <div class="form-group">
                            <input type="text" name="nome" placeholder="Seu nome" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Seu email" required>
                        </div>
                        <div class="form-group">
                            <textarea name="mensagem" placeholder="Sua mensagem" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Tech Innovation. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>