<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../html/login.html");
    exit;
}

$host = 'localhost';
$db   = 'cerrado_burguer';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}


$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();


$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    

    $stmt = $pdo->prepare("UPDATE usuario SET nome = ?, telefone = ?, endereco = ? WHERE id = ?");
    if ($stmt->execute([$nome, $telefone, $endereco, $_SESSION['usuario_id']])) {
        $mensagem = "Dados atualizados com sucesso!";
      
        
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        $usuario = $stmt->fetch();
    } else {
        $mensagem = "Erro ao atualizar dados";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil - Cerrado Burguer</title>
    <link rel="stylesheet" href="../css/tela-inicial.css">
    <link rel="stylesheet" href="../css/perfil.css">
</head>
<body class="perfil-body">

<div class="perfil-container">
    <?php if ($mensagem): ?>
        <div class="mensagem mensagem-sucesso"><?php echo htmlspecialchars($mensagem); ?></div>
    <?php endif; ?>

    <div class="perfil-header">
        <img src="../imagens/foto-perfil.png" class="perfil-icon" />
        <h1>Meu Perfil</h1>
    </div>

    <form method="POST" class="perfil-info">
        <div class="info-item">
            <label class="info-label">Nome:</label>
            <input type="text" name="nome" class="info-input" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" placeholder="Digite seu nome">
        </div>

        <div class="info-item">
            <label class="info-label">Email:</label>
            <input type="email" class="info-input" value="<?php echo htmlspecialchars($usuario['email']); ?>">
        </div>

        <div class="info-item">
            <label class="info-label">Telefone:</label>
            <input type="tel" name="telefone" class="info-input" value="<?php echo htmlspecialchars($usuario['telefone']) ; ?>"> 
        </div>

        <div class="info-item">
            <label class="info-label">Endereço:</label>
            <input type="text" name="endereco" class="info-input" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>" placeholder="Rua, Número, Bairro, Cidade">
        </div>

        <div class="botoes-perfil">
            <button type="submit" class="btn-perfil btn-salvar">Salvar Alterações</button>
            <a href="../html/tela-inicial.html" class="btn-perfil btn-voltar">Voltar</a>
        </div>
    </form>
</div>

</body>
</html>
