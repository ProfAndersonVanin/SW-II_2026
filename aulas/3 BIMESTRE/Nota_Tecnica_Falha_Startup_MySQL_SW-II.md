# Nota técnica — Falha ao rodar os testes com o MySQL desligado

Sistemas Web II — Turma SW-II 3F — Prof. Anderson Vanin

Este documento registra um problema encontrado ao validar a suíte de testes da
Aula 3 (testes unitários com mock): mesmo usando `MagicMock` e
`app.dependency_overrides`, os testes falharam quando o servidor MySQL estava
desligado. Aqui está o diagnóstico e a correção necessária no `main.py`.

---

## 1. O erro observado

Ao rodar `pytest -v` com o MySQL desligado, o pytest nem chegou a executar os
testes — ele falhou já na fase de **coleta** (collection), antes de qualquer
`assert` ser avaliado:

```
collected 0 items / 1 error

ERROR collecting test_produtos.py
...
pymysql.err.OperationalError: (2003, "Can't connect to MySQL server on 'localhost' ...")
...
test_produtos.py:4: in <module>
    from main import app, get_db
main.py:9: in <module>
    Base.metadata.create_all(bind=engine)
...
sqlalchemy.exc.OperationalError: (pymysql.err.OperationalError) (2003, "Can't connect to MySQL server ...")

1 warning, 1 error in 5.85s
```

---

## 2. Diagnóstico: por que o mock não impediu esse erro

O mock (`MagicMock` + `dependency_overrides[get_db]`) só substitui a sessão do
banco **durante a execução de uma requisição** — ou seja, ele protege o que
acontece *dentro* de um endpoint quando o `TestClient` faz uma chamada
(`client.get(...)`, `client.post(...)` etc.).

O problema é anterior a isso. `test_produtos.py` começa com:

```python
from main import app, get_db
```

Importar `main.py` executa **todo o conteúdo do módulo**, de cima a baixo —
inclusive esta linha, que estava solta no nível do módulo:

```python
Base.metadata.create_all(bind=engine)
```

Essa linha tenta abrir uma conexão real com o MySQL **no momento em que o
Python importa o arquivo**, muito antes de qualquer teste rodar e muito antes
de qualquer mock entrar em ação. Como o mock só existe depois que o módulo
já foi importado, ele não tem como evitar esse erro.

**Resumo da causa raiz:** `Base.metadata.create_all(bind=engine)` estava
escrito como uma instrução de nível de módulo, e não como parte de uma rotina
de inicialização do servidor — então ela roda sempre que o arquivo é
importado, inclusive durante os testes.

---

## 3. A correção: mover a criação das tabelas para um evento de startup

A prática recomendada é separar duas responsabilidades que estavam
misturadas: **montar o objeto da aplicação** (deve ser seguro em qualquer
contexto, inclusive em testes) e **inicializar a infraestrutura** (só deve
acontecer quando o servidor realmente vai atender requisições).

### main.py — antes (com o problema)

```python
from fastapi import FastAPI, Depends, HTTPException
from sqlalchemy.orm import Session
from database import Base, engine, get_db
from models import ProdutoDB
from schemas import ProdutoCreate, ProdutoResponse

Base.metadata.create_all(bind=engine)  # roda assim que o módulo é importado

app = FastAPI()

# ... endpoints ...
```

### main.py — depois (corrigido)

```python
from fastapi import FastAPI, Depends, HTTPException
from sqlalchemy.orm import Session
from database import Base, engine, get_db
from models import ProdutoDB
from schemas import ProdutoCreate, ProdutoResponse

app = FastAPI()

@app.on_event("startup")
def criar_tabelas():
    Base.metadata.create_all(bind=engine)

# ... endpoints continuam exatamente iguais ...
```

Com esse ajuste, `Base.metadata.create_all(...)` só é executado quando o
evento de `startup` do FastAPI é disparado — e isso só acontece quando a
aplicação é de fato inicializada (por exemplo, via `uvicorn main:app --reload`,
ou quando o `TestClient` é usado como *context manager*).

---

## 4. Procedimento para rodar os testes da Aula 3 sem depender do MySQL

1. **Aplicar a correção acima** no `main.py` (mover `create_all` para dentro
   de `@app.on_event("startup")`).
2. **Manter `test_produtos.py` como está**, criando o cliente sem `with`:
   ```python
   client = TestClient(app)
   ```
   Dessa forma, o evento de `startup` nunca é disparado durante os testes, e
   a simples importação do `main.py` deixa de exigir o MySQL ligado.
3. **Rodar os testes normalmente:**
   ```bash
   pytest -v
   ```
4. **Validar o cenário que gerou o erro original:** desligar o MySQL e rodar
   `pytest -v` novamente — os testes devem passar normalmente, comprovando
   que a suíte está de fato isolada do banco de dados real.

---

## 5. Observação importante para a turma

Esse é um erro didático valioso: ele mostra, na prática, que **mockar uma
dependência não é suficiente se o código que a usa "vaza" para fora do
mecanismo de injeção de dependências do FastAPI**. `Depends(get_db)` é
seguro de mockar porque o FastAPI controla quando ele é chamado (a cada
requisição); já uma chamada solta no nível do módulo, como o
`Base.metadata.create_all(bind=engine)` original, roda no momento da
importação — fora do alcance de `dependency_overrides`.

Vale usar esse caso em aula como exemplo de que **onde** o código roda (nível
de módulo x dentro de uma função/evento) importa tanto quanto o que ele faz,
quando o assunto é testabilidade.
