# 🚀 Sistemas Web II - APIs & SPAs

![C#](https://img.shields.io/badge/C%23-239120?style=for-the-badge&logo=c-sharp&logoColor=white)
![.NET](https://img.shields.io/badge/.NET-512BD4?style=for-the-badge&logo=dotnet&logoColor=white)
![React](https://img.shields.io/badge/React-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)
![Azure](https://img.shields.io/badge/Azure-0078D4?style=for-the-badge&logo=microsoftazure&logoColor=white)

> **Duração:** 42 Semanas | **Foco:** Backend (.NET), Frontend (React) e Arquitetura Distribuída.

## 📖 Sobre a Disciplina

Este repositório contém o Plano de Ensino e materiais da disciplina de **Sistemas Web II**. O curso avança para o desenvolvimento de sistemas distribuídos, focando na construção de APIs RESTful robustas com .NET e interfaces modernas Single Page Applications (SPA).

### 🎯 Objetivos de Aprendizagem
- **Backend:** Criar APIs escaláveis, seguras (JWT) e testáveis.
- **Frontend:** Desenvolver interfaces reativas que consomem dados externos.
- **Arquitetura:** Compreender a comunicação cliente-servidor, microsserviços e deploy.
- **Prática:** Ciclo completo de desenvolvimento, do banco de dados à publicação na nuvem.

---

## 🛠️ Stack Tecnológica

O curso utiliza um ecossistema moderno para simular o mercado de trabalho:

* **Backend:** C# / .NET 8+ (Minimal APIs e Controllers)
* **Banco de Dados:** SQL Server / SQLite com Entity Framework Core
* **Frontend:** React (Vite), JavaScript/TypeScript
* **Ferramentas:** VS Code, Postman, Swagger/OpenAPI
* **Testes:** xUnit e Moq
* **Deploy:** Azure / Vercel / Netlify

---

## 📅 Cronograma Anual

### Módulo 1: Fundamentos e Revisão
*Nivelamento e compreensão dos protocolos da Web.*

| Semana | Tópico | Detalhes |
| :---: | :--- | :--- |
| **01** | **Ambiente** | Setup (.NET SDK, VS Code), Hello World e Visão de Sist. Distribuídos. |
| **02** | **POO Avançada** | Classes, Herança e Interfaces: Contratos para componentes. |
| **03** | **HTTP** | Verbos (GET, POST...), Status Codes e Headers no Postman. |
| **04** | **JSON** | Serialização e Desserialização de objetos. |
| **05** | **Arquitetura** | Stateless vs Stateful e Ciclo de vida da requisição. |

### Módulo 2: Construção de APIs RESTful
*Desenvolvimento Backend profissional com .NET.*

| Semana | Tópico | Detalhes |
| :---: | :--- | :--- |
| **06** | **Minimal API** | Criando o primeiro Endpoint GET. |
| **07** | **CRUD (I)** | Leitura (GET) e Criação (POST) com Body. |
| **08** | **CRUD (II)** | Atualização (PUT), Exclusão (DELETE) e Tratamento de Erros. |
| **09** | **Camadas** | Organização de código e Injeção de Dependência (DI). |
| **10** | **Entity Framework** | Persistência de dados (SQL Server/SQLite). |
| **11** | **Modelagem REST** | Boas práticas de URIs, pluralização e recursos. |
| **12** | **Swagger** | Documentação automática com OpenAPI. |
| **13** | **Testes (Teoria)** | Introdução a Testes Unitários e Mocks. |
| **14** | **Testes (Prática)** | Escrevendo testes para regras de negócio (xUnit). |
| **15** | **Segurança** | Autenticação vs Autorização. |
| **16** | **JWT** | Implementação de JSON Web Tokens. |
| **17** | **Proteção** | Decorator `[Authorize]` e proteção de rotas. |
| **18** | **Microsserviços** | Conceitos de Monolito vs Microsserviços. |
| **19** | **Integração** | Consumindo APIs externas (ex: ViaCEP) via C#. |
| **20** | **✅ Review** | **Entrega Prática:** API completa com Banco e Auth. |

### Módulo 3: Front-end SPA (Single Page Applications)
*Desenvolvimento de interfaces modernas e reativas.*

| Semana | Tópico | Detalhes |
| :---: | :--- | :--- |
| **21** | **Intro SPA** | Node.js, Vite e estrutura de projeto React. |
| **22** | **Componentes** | Criação e reutilização (Header, Footer, Cards). |
| **23** | **Estilização** | JSX, CSS Modules e Frameworks (Tailwind/Bootstrap). |
| **24** | **Props** | Comunicação unidirecional (Pai -> Filho). |
| **25** | **State** | O Hook `useState` e reatividade. |
| **26** | **Eventos** | Manipulação de cliques e inputs. |
| **27** | **Listas** | Renderização dinâmica com `.map()`. |
| **28** | **Consumo API** | `fetch` ou `axios` conectando com o Módulo 2. |
| **29** | **Ciclo de Vida** | O Hook `useEffect` e efeitos colaterais. |
| **30** | **Rotas** | React Router: Navegação sem recarregar (SPA). |
| **31** | **Formulários** | Validação e envio de dados para a API. |
| **32** | **Auth Front** | Armazenamento de JWT e Headers. |
| **33** | **UX** | Feedback visual (Loadings e Toasts). |
| **34** | **Integração I** | Fluxo de Login completo. |
| **35** | **Integração II** | Telas de CRUD conectadas ao Backend. |
| **36** | **Deploy** | Publicação (Render/Vercel/Netlify). |

### Módulo 4: Projeto Final (Consolidação)
*Factory de Software: Desenvolvimento de produto completo.*

* **Semana 37:** 💡 **Ideação:** Definição do Projeto e Grupos (Ex: Delivery, Tasks).
* **Semana 38:** 📐 **Modelagem:** Diagrama de Rotas e Wireframes.
* **Semana 39:** 🔙 **Sprint 1:** Desenvolvimento Backend (API & Banco).
* **Semana 40:** ⚛️ **Sprint 2:** Desenvolvimento Frontend (Telas).
* **Semana 41:** 🔄 **Sprint 3:** Integração, Refinamento e Correções.
* **Semana 42:** 🎉 **Demo Day:** Apresentação final dos projetos.

---

## 📦 Como executar os projetos

### Backend (.NET)
1.  Navegue até a pasta da API.
2.  Restaure os pacotes: `dotnet restore`
3.  Configure a Connection String no `appsettings.json`.
4.  Rode as migrações: `dotnet ef database update`
5.  Execute: `dotnet run`

### Frontend (React)
1.  Navegue até a pasta do client.
2.  Instale as dependências: `npm install`
3.  Execute o servidor de desenvolvimento: `npm run dev`

---

## 👨‍🏫 Docente

**Anderson Silva Vanin**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/anderson-vanin)
