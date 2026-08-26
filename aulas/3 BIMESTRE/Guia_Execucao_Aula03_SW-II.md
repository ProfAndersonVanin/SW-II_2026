# Guia de execução — Aula 3: Testes unitários com mocks

Sistemas Web II — Turma SW-II 3F — Prof. Anderson Vanin

Este guia parte do projeto já concluído na Aula 2 (CRUD completo de Produtos, com
conexão real ao MySQL via SQLAlchemy) e mostra, passo a passo, os comandos e os
arquivos finais necessários para executar a Aula 3.

---

## 1. Estrutura final do projeto ao final desta aula

```
api-produtos/
├── venv/                 (ambiente virtual — criado na Aula 0)
├── database.py           (Aulas 1)
├── models.py              (Aula 1)
├── schemas.py             (Aula 1)
├── main.py                (Aulas 1 e 2)
└── test_produtos.py       (Aula 3 — novo arquivo)
```

---

## 2. Pré-requisitos: ambiente ativo e bibliotecas instaladas

```bash
# 1) Entrar na pasta do projeto
cd api-produtos

# 2) Ativar o ambiente virtual
venv\Scripts\activate      # Windows
source venv/bin/activate   # Linux/Mac

# 3) Instalar as bibliotecas desta aula
pip install pytest httpx
```

---

## 3. Arquivos finais (das aulas 1 e 2 — sem alterações nesta aula)

### database.py

```python
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, declarative_base

DATABASE_URL = 'mysql+pymysql://root:senha@localhost/loja'

engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
```

### models.py

```python
from sqlalchemy import Column, Integer, String, Float
from database import Base

class ProdutoDB(Base):
    __tablename__ = 'produtos'

    id = Column(Integer, primary_key=True, index=True)
    nome = Column(String(100), nullable=False)
    preco = Column(Float, nullable=False)
    quantidade = Column(Integer, nullable=False)
```

### schemas.py

```python
from pydantic import BaseModel

class ProdutoBase(BaseModel):
    nome: str
    preco: float
    quantidade: int

class ProdutoCreate(ProdutoBase):
    pass

class ProdutoResponse(ProdutoBase):
    id: int

    class Config:
        from_attributes = True
```

### main.py

```python
from fastapi import FastAPI, Depends, HTTPException
from sqlalchemy.orm import Session
from database import Base, engine, get_db
from models import ProdutoDB
from schemas import ProdutoCreate, ProdutoResponse

Base.metadata.create_all(bind=engine)

app = FastAPI()

def buscar_produto(db: Session, produto_id: int):
    return db.query(ProdutoDB).filter(ProdutoDB.id == produto_id).first()

@app.get('/produtos', response_model=list[ProdutoResponse])
def listar_produtos(db: Session = Depends(get_db)):
    return db.query(ProdutoDB).all()

@app.post('/produtos', response_model=ProdutoResponse, status_code=201)
def criar_produto(produto: ProdutoCreate, db: Session = Depends(get_db)):
    novo_produto = ProdutoDB(**produto.dict())
    db.add(novo_produto)
    db.commit()
    db.refresh(novo_produto)
    return novo_produto

@app.get('/produtos/{produto_id}', response_model=ProdutoResponse)
def obter_produto(produto_id: int, db: Session = Depends(get_db)):
    produto = buscar_produto(db, produto_id)
    if produto is None:
        raise HTTPException(status_code=404, detail='Produto não encontrado')
    return produto

@app.put('/produtos/{produto_id}', response_model=ProdutoResponse)
def atualizar_produto(produto_id: int, dados: ProdutoCreate, db: Session = Depends(get_db)):
    produto = buscar_produto(db, produto_id)
    if produto is None:
        raise HTTPException(status_code=404, detail='Produto não encontrado')
    produto.nome = dados.nome
    produto.preco = dados.preco
    produto.quantidade = dados.quantidade
    db.commit()
    db.refresh(produto)
    return produto

@app.delete('/produtos/{produto_id}', status_code=204)
def remover_produto(produto_id: int, db: Session = Depends(get_db)):
    produto = buscar_produto(db, produto_id)
    if produto is None:
        raise HTTPException(status_code=404, detail='Produto não encontrado')
    db.delete(produto)
    db.commit()
```

---

## 4. Arquivo novo desta aula: test_produtos.py

```python
from unittest.mock import MagicMock
from fastapi.testclient import TestClient
from main import app, get_db
from models import ProdutoDB

client = TestClient(app)


def test_listar_produtos_com_mock():
    db_mock = MagicMock()
    db_mock.query.return_value.all.return_value = [
        ProdutoDB(id=1, nome='Teclado', preco=89.90, quantidade=15)
    ]
    app.dependency_overrides[get_db] = lambda: db_mock

    resposta = client.get('/produtos')

    assert resposta.status_code == 200
    assert resposta.json()[0]['nome'] == 'Teclado'

    app.dependency_overrides.clear()


def test_criar_produto_com_mock():
    db_mock = MagicMock()

    def simular_refresh(produto):
        produto.id = 1  # simula o banco atribuindo um id ao registro

    db_mock.refresh.side_effect = simular_refresh
    app.dependency_overrides[get_db] = lambda: db_mock

    novo_produto = {'nome': 'Monitor', 'preco': 799.90, 'quantidade': 5}
    resposta = client.post('/produtos', json=novo_produto)

    assert resposta.status_code == 201
    db_mock.add.assert_called_once()
    db_mock.commit.assert_called_once()

    app.dependency_overrides.clear()
```

> **Por que o `side_effect` é necessário:** quando a sessão inteira é mockada, nenhuma
> operação de banco acontece de verdade — inclusive a atribuição automática do `id`,
> que normalmente é feita pelo MySQL (auto-increment) no momento do `commit`/`refresh`.
> Sem o `side_effect`, o objeto `novo_produto` continua com `id=None`, e o FastAPI falha
> ao tentar validar a resposta contra `ProdutoResponse` (que exige `id: int`), lançando
> um `ResponseValidationError`. O `side_effect` faz o mock imitar esse comportamento
> específico do banco real, resolvendo o problema.

---

## 5. Comando para executar os testes

```bash
pytest -v
```

### Saída esperada no terminal

```
test_produtos.py::test_listar_produtos_com_mock PASSED
test_produtos.py::test_criar_produto_com_mock PASSED

======================== 2 passed in 0.15s ========================
```

Os dois testes devem passar quase instantaneamente — **mesmo com o MySQL desligado** —
pois a sessão do banco foi totalmente simulada com `MagicMock`. Isso é o que comprova,
na prática, que o teste está isolado da dependência real.

---

## 6. Erros comuns e como resolver

| Sintoma | Causa provável | Solução |
|---|---|---|
| `ModuleNotFoundError: No module named 'httpx'` | httpx não instalado | `pip install httpx` |
| Um teste passa sozinho, mas falha quando os dois rodam juntos | `app.dependency_overrides.clear()` não foi chamado no teste anterior | Garantir o `clear()` ao final de cada teste |
| `AssertionError` no `resposta.json()[0]['nome']` | O mock de `db.query.return_value.all.return_value` não foi configurado antes da chamada | Configurar o mock antes de chamar `client.get(...)` |
| Testes tentando conectar ao MySQL de verdade | `app.dependency_overrides[get_db]` não foi definido antes da chamada ao endpoint | Confirmar que a sobrescrita ocorre antes de `client.get`/`client.post` |
| `fastapi.exceptions.ResponseValidationError: ... 'id' ... Input should be a valid integer` | `db.refresh()` está mockado e não atribui um `id` real ao objeto criado, mas o `response_model` exige `id: int` | Configurar `db_mock.refresh.side_effect` para atribuir um `id` ao objeto, simulando o comportamento do banco real (ver seção 4 acima) |
