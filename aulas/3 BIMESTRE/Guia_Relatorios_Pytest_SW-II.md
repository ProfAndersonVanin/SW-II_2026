# Guia — Relatórios de testes com pytest

Sistemas Web II — Turma SW-II 3F — Prof. Anderson Vanin

Complemento à Aula 3 (Testes unitários com mocks). Este guia mostra como gerar
relatórios a partir da suíte de testes já construída (`test_produtos.py`),
usando três plugins diferentes do pytest, cada um com uma finalidade distinta.

---

## 1. Relatório de cobertura — pytest-cov

Mostra **quais linhas do código-fonte foram realmente executadas** pelos testes.
É o relatório mais útil neste ponto do curso, pois evidencia que os testes com
mock cobrem GET e POST, mas ainda não cobrem PUT e DELETE.

### Instalação

```bash
pip install pytest-cov
```

### Relatório no terminal

```bash
pytest --cov=main -v
```

Saída esperada (resumida):

```
test_produtos.py::test_listar_produtos_com_mock PASSED                 [ 50%]
test_produtos.py::test_criar_produto_com_mock PASSED                   [100%]

---------- coverage: platform win32, python 3.11 -----------
Name       Stmts   Miss  Cover
------------------------------
main.py       28      6    79%
------------------------------
TOTAL         28      6    79%
```

- **Stmts**: número de linhas de código executáveis no arquivo.
- **Miss**: quantas dessas linhas nenhum teste passou por cima.
- **Cover**: percentual de cobertura (Stmts - Miss) / Stmts.

### Relatório em HTML (navegável, linha a linha)

```bash
pytest --cov=main --cov-report=html
```

Isso gera uma pasta `htmlcov/`. Abra `htmlcov/index.html` no navegador: as
linhas em verde foram executadas pelos testes, as linhas em vermelho não.

---

## 2. Relatório em HTML dos testes — pytest-html

Gera uma página única, fácil de compartilhar, com o resultado de cada teste,
tempo de execução e, em caso de falha, o traceback completo. Útil como
evidência de entrega de uma atividade prática.

### Instalação

```bash
pip install pytest-html
```

### Geração do relatório

```bash
pytest --html=relatorio.html --self-contained-html
```

- `--html=relatorio.html` define o nome do arquivo gerado.
- `--self-contained-html` embute todo o CSS no próprio arquivo, permitindo abri-lo
  diretamente no navegador sem depender de outros arquivos.

Abra `relatorio.html` no navegador para ver a lista de testes, status (passou/falhou),
duração de cada um, e um resumo geral no topo da página.

---

## 3. Relatório em XML (padrão JUnit) — para integração contínua

Formato lido por ferramentas de CI/CD (GitHub Actions, GitLab CI, Jenkins, entre
outras). Não é visual — é pensado para ser processado por outra ferramenta.

```bash
pytest --junitxml=resultado.xml
```

Gera um arquivo `resultado.xml` com o resultado estruturado de cada teste, que
pode ser lido automaticamente por uma esteira de integração contínua.

---

## 4. Combinando relatórios em um único comando

Os três plugins podem ser usados juntos na mesma execução:

```bash
pytest --cov=main --cov-report=html --html=relatorio.html --self-contained-html --junitxml=resultado.xml
```

Isso gera, em uma única execução: o relatório de cobertura em `htmlcov/`, o
relatório de testes em `relatorio.html`, e o relatório XML em `resultado.xml`.

---

## 5. Resumo — qual relatório usar em cada situação

| Situação | Relatório recomendado |
|---|---|
| Quero saber se meus testes realmente cobrem o código (GET, POST, PUT, DELETE) | `pytest --cov=main` (terminal) ou `--cov-report=html` (navegável) |
| Quero entregar uma evidência visual de que os testes passaram | `pytest-html` |
| Vou integrar os testes a uma esteira automatizada (CI/CD) | `pytest --junitxml` |

---

## 6. O que fazer com os arquivos gerados após os testes

Vale separar duas coisas: o **código do teste** e os **relatórios gerados**.

- **`test_produtos.py`** (o código do teste em si) fica no projeto normalmente,
  versionado no Git junto com `main.py`, `models.py` etc.
- **`htmlcov/`, `relatorio.html`, `resultado.xml`, `.coverage`** (os relatórios
  gerados pelos comandos acima) **não devem ser versionados no Git**:
  - Mudam a cada execução, mesmo sem nenhuma alteração de código — gerando
    commits de "ruído" e conflitos desnecessários.
  - São **regeneráveis** a qualquer momento com o mesmo comando — não há
    motivo para guardar o que pode ser recriado em segundos.
  - `htmlcov/` em particular pode chegar a dezenas de arquivos, inflando o
    repositório sem necessidade.

Adicione essas saídas ao `.gitignore` do projeto:

```
# .gitignore
htmlcov/
.coverage
relatorio.html
resultado.xml
```

**Exceção prática:** se o relatório for pedido como evidência de entrega de uma
atividade específica, ele é enviado separadamente (e-mail, upload na plataforma
da ETEC etc.) — não commitado junto ao código-fonte do projeto.
