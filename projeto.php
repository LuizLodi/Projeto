<?php
class Cliente {
    private $id;
    private $nome;
    private $email;

    public function __construct($nome, $email, $id = null) {
        $this->nome = $nome;
        $this->email = $email;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }
}

class Produto {
    private $id;
    private $nome_produto;
    private $preco;

    public function __construct($nome_produto, $preco, $id = null) {
        $this->nome_produto = $nome_produto;
        $this->preco = $preco;
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getNomeProduto() {
        return $this->nome_produto;
    }

    public function getPreco() {
        return $this->preco;
    }
}

$dsn = "pgsql:host=localhost;port=5432;dbname=projeto";
$conexao = new PDO($dsn, "postgres", "postgres");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['salvar_cliente'])) {

        $cliente = new Cliente(
            $_POST['nome'],
            $_POST['email']
        );

        $sql = "INSERT INTO cliente(nome, email)
                VALUES (?, ?)";

        $conexao->prepare($sql)->execute([
            $cliente->getNome(),
            $cliente->getEmail()
        ]);
    }

    if (isset($_POST['salvar_produto'])) {

        $produto = new Produto(
            $_POST['nome_produto'],
            $_POST['preco']
        );

        $sql = "INSERT INTO produto(nome_produto, preco)
                VALUES (?, ?)";

        $conexao->prepare($sql)->execute([
            $produto->getNomeProduto(),
            $produto->getPreco()
        ]);
    }

    header("Location: projeto.php");
    exit;
}

$clientes = [];

foreach ($conexao->query("SELECT * FROM cliente") as $row) {

    $clientes[] = new Cliente(
        $row['nome'],
        $row['email'],
        $row['id']
    );
}

$produtos = [];

foreach ($conexao->query("SELECT * FROM produto") as $row) {

    $produtos[] = new Produto(
        $row['nome_produto'],
        $row['preco'],
        $row['id']
    );
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema Loja</title>

    <style>

    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
    }

    .container {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    section {
        background: white;
        padding: 20px;
        width: 320px;
        border: 1px solid #ccc;
    }

    h2 {
        margin-top: 0;
        text-align: center;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 10px;
        background: #ddd;
        border: 1px solid #999;
        cursor: pointer;
    }

    button:hover {
        background: #ccc;
    }

    table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #999;
        padding: 6px;
        text-align: center;
    }

    th {
        background: #eaeaea;
    }

</style>
</head>

<body>

    <h1>Sisteminha da Loja do Luizão</h1>

    <div class="container">

        <section>

            <h2>Cadastro de Cliente</h2>

            <form method="post">

                <input type="text"
                       name="nome"
                       placeholder="Nome do Cliente"
                       required>

                <input type="email"
                       name="email"
                       placeholder="E-mail"
                       required>

                <button type="submit"
                        name="salvar_cliente">
                    Salvar Cliente
                </button>

            </form>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                </tr>

                <?php foreach ($clientes as $c): ?>

                <tr>
                    <td><?= $c->getId() ?></td>
                    <td><?= $c->getNome() ?></td>
                    <td><?= $c->getEmail() ?></td>
                </tr>

                <?php endforeach; ?>

            </table>

        </section>

        <section>

            <h2>Cadastro de Produto</h2>

            <form method="post">

                <input 
                type="text"
                name="nome_produto"
                placeholder="Nome do Produto"
                required>
                <input 
                type="number"
                step="0.01"
                name="preco"
                placeholder="Preço"
                required>

                <button 
                type="submit"
                name="salvar_produto">
                Salvar Produto
                </button>

            </form>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Preço</th>
                </tr>

                <?php foreach ($produtos as $p): ?>

                <tr>
                    <td><?= $p->getId() ?></td>
                    <td><?= $p->getNomeProduto() ?></td>
                    <td>R$ <?= $p->getPreco() ?></td>
                </tr>

                <?php endforeach; ?>

            </table>

        </section>

    </div>

</body>
</html>
