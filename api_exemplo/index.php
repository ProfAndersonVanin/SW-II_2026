<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$DATA_FILE = __DIR__ . '/data.json';

// ── Funções auxiliares ──────────────────────────────────────────

function lerDados($arquivo) {
    if (!file_exists($arquivo)) return [];
    $conteudo = file_get_contents($arquivo);
    return json_decode($conteudo, true) ?? [];
}

function salvarDados($arquivo, $dados) {
    file_put_contents($arquivo, json_encode(array_values($dados), JSON_PRETTY_PRINT));
}

function responder($dados, $status = 200) {
    http_response_code($status);
    echo json_encode($dados);
    exit();
}

function proximoId($dados) {
    if (empty($dados)) return 1;
    return max(array_column($dados, 'id')) + 1;
}

// ── Roteamento ──────────────────────────────────────────────────

$metodo = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Extrai o ID da URL (ex: /api/usuarios/3 → id = 3)
preg_match('#/usuarios(?:/(\d+))?$#', $uri, $matches);
$id = isset($matches[1]) ? (int)$matches[1] : null;

switch ($metodo) {

    // GET /usuarios         → lista todos
    // GET /usuarios/{id}    → busca um
    case 'GET':
        $dados = lerDados($DATA_FILE);
        if ($id !== null) {
            $item = array_filter($dados, fn($u) => $u['id'] === $id);
            $item ? responder(array_values($item)[0]) : responder(['erro' => 'Não encontrado'], 404);
        }
        responder($dados);

    // POST /usuarios        → cria novo
    case 'POST':
        $body  = json_decode(file_get_contents('php://input'), true);
        if (empty($body['nome']) || empty($body['email'])) {
            responder(['erro' => 'nome e email são obrigatórios'], 422);
        }
        $dados        = lerDados($DATA_FILE);
        $novo         = ['id' => proximoId($dados), 'nome' => $body['nome'], 'email' => $body['email']];
        $dados[]      = $novo;
        salvarDados($DATA_FILE, $dados);
        responder($novo, 201);

    // PUT /usuarios/{id}    → atualiza
    case 'PUT':
        if ($id === null) responder(['erro' => 'ID obrigatório'], 400);
        $body  = json_decode(file_get_contents('php://input'), true);
        $dados = lerDados($DATA_FILE);
        foreach ($dados as &$item) {
            if ($item['id'] === $id) {
                $item['nome']  = $body['nome']  ?? $item['nome'];
                $item['email'] = $body['email'] ?? $item['email'];
                salvarDados($DATA_FILE, $dados);
                responder($item);
            }
        }
        responder(['erro' => 'Não encontrado'], 404);

    // DELETE /usuarios/{id} → remove
    case 'DELETE':
        if ($id === null) responder(['erro' => 'ID obrigatório'], 400);
        $dados    = lerDados($DATA_FILE);
        $filtrado = array_filter($dados, fn($u) => $u['id'] !== $id);
        if (count($filtrado) === count($dados)) responder(['erro' => 'Não encontrado'], 404);
        salvarDados($DATA_FILE, $filtrado);
        responder(['mensagem' => 'Deletado com sucesso']);

    default:
        responder(['erro' => 'Método não permitido'], 405);
}

?>